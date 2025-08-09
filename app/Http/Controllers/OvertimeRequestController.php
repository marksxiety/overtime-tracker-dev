<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class OvertimeRequestController extends Controller
{
    public function insertOvertimeRequest(Request $request)
    {

        $rules = [
            'employee_schedule_id' => 'exists:schedules,id|required',
            'date' => 'required|date_format:Y-m-d',
            'reason' => 'required|string|min:10'
        ];

        $withTimeFormatting = true;

        $submittet_time_start_isValid = Carbon::hasFormat($request->start_time, 'H:i');
        $submittet_end_start_isValid = Carbon::hasFormat($request->end_time, 'H:i');

        if (!$submittet_time_start_isValid && !$submittet_end_start_isValid) {
            $withTimeFormatting = false;
            $rules[] = [
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
            ];
        }




        // Parse actual times in 24-hour format
        $submitted_start_time = Carbon::createFromFormat('H:i', trim($request->start_time));
        $submitted_end_time   = Carbon::createFromFormat('H:i', trim($request->end_time));


        $validator = Validator::make($request->all(), $rules);
        $errors = $validator->errors();

        if ($withTimeFormatting) {

            // GENERAL RULE:
            // You can only file OT outside your normal shift, with at least a 60-minute gap 
            // before your shift starts or after it ends.
            //
            // Example: If your shift is 08:00 to 18:00:
            //   OK: Start at 06:00, end before 08:00
            //   OK: Start after 18:00, end at 19:00 or later
            //   Not OK: Anything that starts or ends during your shift
            //   Not OK: Less than 60 mins before shift start
            //   Not OK: Less than 60 mins after shift end
            //
            // Before shift: start and end must both be before shift start.
            // After shift: end must be later than start and at least 60 mins after shift end.

            // Parse shift times
            $schdule_start_time = Carbon::createFromFormat('h:i A', trim($request->shift_start_time));
            $schdule_end_time   = Carbon::createFromFormat('h:i A', trim($request->shift_end_time));

            // Handle shifts crossing midnight
            if ($schdule_end_time->lessThan($schdule_start_time)) {
                $schdule_end_time->addDay();
            }

            // Also adjust submitted end if crosses midnight
            if ($submitted_end_time->lessThan($submitted_start_time)) {
                $submitted_end_time->addDay();
            }

            // 1. Block if start is within schedule
            if ($submitted_start_time->between($schdule_start_time, $schdule_end_time, false)) {
                $errors->add('start_time', 'Start time cannot be within the scheduled shift.');
            }

            // 2. Block if end is within schedule
            if ($submitted_end_time->between($schdule_start_time, $schdule_end_time, false)) {
                $errors->add('end_time', 'End time cannot be within the scheduled shift.');
            }

            // 3. Enforce 60 minutes before start
            $start_diff = $submitted_start_time->diffInMinutes($schdule_start_time, false); // positive if earlier
            if ($start_diff < 60 && $submitted_start_time->lessThan($schdule_start_time)) {
                $errors->add('start_time', 'Start time must be at least 1 hour before the scheduled start.');
            }

            // 4. Enforce 60 minutes after end
            $end_diff = $schdule_end_time->diffInMinutes($submitted_end_time, false); // positive if later
            if ($end_diff < 60 && $submitted_end_time->greaterThan($schdule_end_time)) {
                $errors->add('end_time', 'End time must be at least 1 hour after the scheduled end.');
            }

            // CASE 1: Start is BEFORE schedule start
            // and at least 60 mins before schedule start
            if ($submitted_start_time->lt($schdule_start_time) &&
                $submitted_start_time->diffInMinutes($schdule_start_time) >= 60) {

                // End must be BEFORE schedule start AND less than submitted start
                if (!($submitted_end_time->lt($schdule_start_time) &&
                    $submitted_end_time->lt($submitted_start_time))) {
                    $errors->add('end_time', 'Invalid range: Filing before schedule start must also end before schedule start and before start time.');
                }
            }

            // CASE 2: Start is AFTER schedule start
            if ($submitted_start_time->gt($schdule_start_time)) {

                // Must be more than 60 mins difference between start & end
                // And end must be GREATER than start
                if (!($submitted_end_time->gt($submitted_start_time) &&
                    $submitted_start_time->diffInMinutes($submitted_end_time) > 60)) {
                    $errors->add('end_time', 'Invalid range: Filing after schedule start must be more than 60 minutes and end after start time.');
                }
            }

            if ($errors->any()) {
                return redirect()->back()->withErrors($errors)->withInput();
            }
        }

        // compute the hours and convert it to float
        $hours = $this->calculateOvertimeHours($submitted_start_time, $submitted_end_time);

        $data = [
            'employee_schedule_id' => $request->employee_schedule_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'hours' => $hours,
            'reason' => $request->reason
        ];

        OvertimeRequest::create($data);
        return redirect()->back()->with(['message' => 'Overtime Request has been filed!']);
    }

    public function calculateOvertimeHours(Carbon $start, Carbon $end)
    {
        if ($end->lt($start)) {
            $end->addDay();
        }

        $minutes = $start->diffInMinutes($end);
        $decimalHours = $minutes / 60;
        return number_format($decimalHours, 2);
    }


    public function updateOvertimeRequestStatus(Request $request)
    {
        try {
            $rules = [];

            // force the user to input remarks if the status is disapproved or declined
            // this will provde the user proper reason why their overtime request has been declined or disapproved
            if (in_array($request->update_status, ['DISAPPROVED', 'DECLINED'])) {
                $rules['remarks'] = 'required|string|min:10';
            } else {
                $rules['current_status'] = ['required', Rule::in(['PENDING', 'APPROVED'])];
            }

            $messages = [
                'current_status.in' => 'The selected status is invalid. Only PENDING or APPROVED are allowed.',
                'current_status.required' => 'The current status field is required.'
            ];

            $validate = Validator::make($request->all(), $rules, $messages);

            if ($validate->fails()) {
                return redirect()->back()
                    ->withErrors($validate)
                    ->withInput();
            }

            OvertimeRequest::where('id', $request->id)->update(['status' => $request->update_status, 'remarks' => $request->remarks]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors("Cancelation failed due to $th");
        }
    }


    public function fetchOvertimeRequestsBySession()
    {

        $overtimelist = [];
        $overtime = null;
        $message = '';
        try {
            $overtimes = OvertimeRequest::with(['schedule' => function ($query) {
                $query->select('id', 'week', 'date', 'user_id');
            }])
                ->whereHas('schedule', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->select('id', 'employee_schedule_id', 'start_time', 'end_time', 'hours', 'reason', 'remarks', 'status', 'created_at')
                ->orderBy('updated_at', 'desc')
                ->get();

            foreach ($overtimes as $overtime) {
                $overtimelist[] = [
                    'week' => $overtime->schedule->week,
                    'date' => $overtime->schedule->date,
                    'id' => $overtime->id,
                    'start_time' => $overtime->start_time,
                    'end_time' => $overtime->end_time,
                    'hours' => $overtime->hours,
                    'reason' => $overtime->reason,
                    'remarks' => $overtime->remarks,
                    'status' => $overtime->status,
                    'created_at' => $overtime->created_at
                ];
            }


            $success = true;
        } catch (\Throwable $th) {
            $success = false;
            $message = "Fetching Failed due to $th";
        }

        return inertia('Employee/Index', [
            'info' => [
                'overtimelist' => $overtimelist
            ],
            'success' => $success,
            'message' => $message
        ]);
    }

    public function fetchTotalOvertimeRequests(Request $request)
    {

        $week = $request->input('week', Carbon::now()->weekOfYear);
        $year = $request->input('year', Carbon::now()->year);
        $message = '';
        try {
            $required_registered_hours = DB::table('required_hours')->where('year', $year)->where('week', $week)->orderBy('updated_at', 'desc')->select('required_hours.required_hours as hours')->first();
            $requests = DB::table('overtime_requests')
                ->join('schedules', 'schedules.id', '=', 'overtime_requests.employee_schedule_id')
                ->join('users', 'users.id', '=', 'schedules.user_id')
                ->select('schedules.date', 'schedules.week', 'overtime_requests.status', 'overtime_requests.remarks', 'overtime_requests.reason', 'users.name', 'overtime_requests.hours')
                ->whereYear('schedules.date', $year)
                ->where('schedules.week', $week)
                ->get();

            $total_filed = 0;
            $total_approved = 0;
            $total_pending = 0;
            $total_declined = 0;
            $total_canceled = 0;
            $total_disapproved = 0;
            $total_requests = 0;
            $total_hours = 0;
            $required_hours = $required_registered_hours->hours ?? 0;

            $result = [
                [
                    'name' => 'PENDING',
                    'value' => 0,
                    'remarks' => []
                ],
                [
                    'name' => 'FILED',
                    'value' => 0,
                    'remarks' => []
                ],
                [
                    'name' => 'APPROVED',
                    'value' => 0,
                    'remarks' => []
                ],
                [
                    'name' => 'DECLINED',
                    'value' => 0,
                    'remarks' => []
                ],
                [
                    'name' => 'CANCELED',
                    'value' => 0,
                    'remarks' => []
                ],
                [
                    'name' => 'DISAPPROVED',
                    'value' => 0,
                    'remarks' => []
                ],
            ];

            foreach ($requests as $request) {
                $status = strtoupper($request->status); // Normalize to uppercase

                // ====== build the data for pie graph ======
                for ($index = 0; $index < count($result); $index++) {
                    if ($request->status === $result[$index]['name']) {
                        $result[$index]['value']++;

                        if ($request->remarks) {
                            $result[$index]['remarks'][] = $request->remarks;
                        }
                    }
                }

                // ====== consolidate all the countings for card dispaly ======
                $total_requests++;
                switch ($status) {
                    case 'FILED':
                        $total_filed++;
                        $total_hours += (float)$request->hours ?? 0;
                        break;
                    case 'APPROVED':
                        $total_approved++;
                        $total_hours += (float)$request->hours ?? 0;
                        break;
                    case 'PENDING':
                        $total_pending++;
                        break;
                    case 'DECLINED':
                        $total_declined++;
                        break;
                    case 'CANCELED':
                        $total_canceled++;
                        break;
                    case 'DISAPPROVED':
                        $total_disapproved++;
                        break;
                }
            }


            // remove the status that does not have any filing.
            // this is to display only the data that has specific value to avoid 
            // including legend in a pie graph that doesn't have a value
            for ($counter = 0; $counter < count($result); $counter++) {
                if ($result[$counter]['value'] === 0) {
                    // after unsetting, reindex and decrement so it wont 
                    // skip the next loop
                    unset($result[$counter]);
                    $result = array_values($result);
                    $counter--;
                }
            }

            // ============= FORMAT FOR BREAKDOWN OVERTIME =============
            // get the first day of the week then get the proceeding days
            $startOfWeek = CarbonImmutable::createFromDate($year, 1, 1, 'Asia/Manila')
                ->startOfWeek(Carbon::SUNDAY)
                ->addWeeks($week - 1);

            // consolidate the dates that will used to join in requests data (format it in Y-m-d since that is the format of date in db)
            $dates = [];
            for ($i = 0; $i < 7; $i++) {
                $dates[] = $startOfWeek->addDays($i)->format('Y-m-d');
            }

            $breakdown = [];

            foreach ($requests as $req) {
                // Find if this name already exists in breakdown
                $index = array_search($req->name, array_column($breakdown, 'name'));

                if ($index === false) {
                    // Create a new entry with 0 hours for all dates
                    $dataPoints = array_fill(0, count($dates), 0);

                    // Fill in the hours for the matching date
                    foreach ($dates as $i => $date) {
                        if ($date === $req->date && in_array($req->status, ['APPROVED', 'FILED'])) {
                            $dataPoints[$i] += $req->hours;
                        }
                    }

                    $breakdown[] = [
                        'name'  => $req->name,
                        'type'  => 'bar',
                        'stack' => 'total',
                        'data'  => $dataPoints
                    ];
                } else {
                    // Update existing entry
                    foreach ($dates as $i => $date) {
                        if ($date === $req->date && in_array($req->status, ['APPROVED', 'FILED'])) {
                            $breakdown[$index]['data'][$i] += $req->hours;
                        }
                    }
                }
            }
            
            $total_computed_hours = array_fill(0, count($dates), 0);

            foreach ($breakdown as $br) {
                foreach ($br['data'] as $i => $hours) {
                    $total_computed_hours[$i] += $hours;
                }
            }

            $rounded_total_computed_hours = array_map(function($num) {
                return round($num, 2);
            }, $total_computed_hours);

            $breakdown[] = [
                'name'  => 'Total',
                'type'  => 'line',
                'data'  => $rounded_total_computed_hours,
                'smooth'=> true
            ];

            $success = true;
        } catch (\Throwable $th) {
            $success = false;
            $message = "Fetching Failed due to $th";
        }

        return inertia('Approver/Index', [
            'info' => [
                'result' => [
                    'requests' => $result,
                    'breakdown' => $breakdown,
                    'totals' => [
                        'FILED' => $total_filed,
                        'APPROVED' => $total_approved,
                        'PENDING' => $total_pending,
                        'DECLINED' => $total_declined,
                        'CANCELED' => $total_canceled,
                        'DISAPPROVED' => $total_disapproved,
                        'TOTAL_REQUESTS' => $total_requests,
                        'TOTAL_HOURS' => $total_hours,
                        'REQUIRED_HOURS' => $required_hours,
                    ]
                ],
                'payload' => [
                    'year' => $year,
                    'week' => $week
                ],
                'test' => [
                    'days' => $dates,
                    'breakdown' => $breakdown
                ]
            ],
            'success' => $success,
            'message' => $message
        ]);
    }

    public function fetchOvertimeRequestsViaStatus(Request $request)
    {
        $week = $request->input('week', Carbon::now()->weekOfYear);
        $year = $request->input('year', Carbon::now()->year);
        $status = $request->input('status', '');
        $page = $request->input('page', null);

        $overtimelist = [];
        $overtime_requests = [];
        $message = '';
        try {
            $overtime_requests = DB::table('overtime_requests')->join('schedules', 'schedules.id', '=', 'overtime_requests.employee_schedule_id')->join('users', 'users.id', '=', 'schedules.user_id')
                ->leftJoin('shift_codes', 'shift_codes.id', '=', 'schedules.shift_id')
                ->select(
                    'overtime_requests.id as request_id',
                    'users.id as user_id',
                    'users.name',
                    'users.employeeid',
                    'users.role',
                    'users.email',
                    'overtime_requests.start_time',
                    'overtime_requests.end_time',
                    'schedules.date',
                    'schedules.week',
                    'shift_codes.code as shift_code',
                    'shift_codes.start_time as shift_start',
                    'shift_codes.end_time as shift_end',
                    'overtime_requests.start_time',
                    'overtime_requests.end_time',
                    'overtime_requests.hours',
                    'overtime_requests.status',
                    'overtime_requests.reason',
                    'overtime_requests.remarks',
                    'overtime_requests.created_at'
                )->where('status', $status)->whereYear('schedules.date', $year)->where('schedules.week', $week)->orderBy('created_at')->get();

            foreach ($overtime_requests as $overtime) {
                // create instance on timestamps
                $overtime_start = Carbon::createFromFormat('H:i:s', $overtime->start_time);
                $overtime_end = Carbon::createFromFormat('H:i:s', $overtime->end_time);

                $schedule_start = $overtime->shift_start === null ? '--' :  Carbon::createFromFormat('H:i:s', $overtime->shift_start);
                $schedule_end = $overtime->shift_start === null ? '--' :  Carbon::createFromFormat('H:i:s', $overtime->shift_end);

                $overtime_created = Carbon::createFromFormat('Y-m-d H:i:s', $overtime->created_at);

                $overtimelist[] = [
                    'id' => $overtime->request_id,
                    'user' => [
                        'name' => $overtime->name,
                        'employee_id' => $overtime->employeeid,
                        'email' => $overtime->email,
                        'role' => $overtime->role,
                    ],
                    'schedule' => [
                        'date' => $overtime->date,
                        'week' => $overtime->week,
                        'shift_code' => $overtime->shift_code ?? 'N/A',
                        'shift_start' => $schedule_start === '--' ? '--' : $schedule_start->format('h:i A'),
                        'shift_end' => $schedule_end === '--' ? '--' : $schedule_end->format('h:i A'),
                    ],
                    'overtime' => [
                        'start_time' => $overtime_start->format('h:i A'),
                        'end_time' =>  $overtime_end->format('h:i A'),
                        'hours' => $overtime->hours,
                        'status' => $overtime->status,
                        'reason' => $overtime->reason,
                        'remarks' => $overtime->remarks,
                        'created_at' => $overtime_created->format('l, jS \of F Y, h:i:s A')
                    ]
                ];
            }
            $success = true;
        } catch (\Throwable $th) {
            $success = false;
            $message = "Fetching Failed due to $th";
        }

        return inertia($page, [
            'info' => [
                'requests' => $overtimelist,
                'payload' => [
                    'year' => $year,
                    'week' => $week,
                    'status' => $status,
                    'page' => $page
                ]
            ],
            'success' => $success,
            'message' => $message
        ]);
    }
}
