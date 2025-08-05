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

class OvertimeRequestController extends Controller
{
    public function insertOvertimeRequest(Request $request)
    {

        $rules = [
            'employee_schedule_id' => 'exists:schedules,id|required',
            'date' => 'required|date_format:Y-m-d',
            'reason' => 'required|string|min:10'
        ];

        $withTimeFormatting = false;

        $submittet_time_start_isValid = Carbon::hasFormat($request->start_time, 'H:i');
        $submittet_end_start_isValid = Carbon::hasFormat($request->end_time, 'H:i');

        if (!$submittet_time_start_isValid && !$submittet_end_start_isValid) {
            $withTimeFormatting = true;
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
            // Parse shift times in 12-hour format
            $schdule_start_time = Carbon::createFromFormat('h:i A', trim($request->shift_start_time));
            $schdule_end_time   = Carbon::createFromFormat('h:i A', trim($request->shift_end_time));
            // Difference in minutes
            $start_diff = $schdule_start_time->diffInMinutes($submitted_start_time, false); // negative if earlier
            $end_diff = $schdule_end_time->diffInMinutes($submitted_end_time, false);       // positive if later

            if ($start_diff > -60) {
                $errors->add('start_time', 'Start time should be at least 1 hour before the scheduled start time.');
            }

            if ($end_diff < 60) {
                $errors->add('end_time', 'End time should be at least 1 hour after the scheduled end time.');
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

        $overtimelist = [];
        $overtime_requests = [];
        $overtime = null;
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
                )->where('status', 'PENDING')->whereYear('schedules.date', $year)->where('schedules.week', $week)->orderBy('created_at')->get();

            $required_registered_hours = DB::table('required_hours')->where('year', $year)->where('week', $week)->orderBy('updated_at', 'desc')->select('required_hours.required_hours as hours')->first();
            $totals = DB::table('overtime_requests')
                ->join('schedules', 'schedules.id', '=', 'overtime_requests.employee_schedule_id')
                ->whereYear('schedules.date', $year)
                ->where('schedules.week', $week)
                ->selectRaw("
                    COUNT(*) as total_requests,
                    SUM(CASE WHEN status = 'APPROVED' THEN 1 ELSE 0 END) as total_approved,
                    SUM(CASE WHEN status = 'FILED' THEN 1 ELSE 0 END) as total_filed,
                    SUM(CASE WHEN status = 'PENDING' THEN 1 ELSE 0 END) as total_pending,
                    SUM(CASE WHEN status = 'APPROVED' THEN hours ELSE 0 END) as total_hours")
                ->first();

            // consolidate the counted card countings
            $total_filed = (int)$totals->total_filed;
            $total_requests = $totals->total_requests;
            $total_approved = (int)$totals->total_approved;
            $total_pending = (int)$totals->total_pending;
            $total_hours = $totals->total_hours;
            $required_hours = $required_registered_hours->hours;

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

        return inertia('Approver/Index', [
            'info' => [
                'requests' => $overtimelist,
                'totals' => [
                    'required_hours' => $required_hours ?? 0,
                    'total_hours' => $total_hours,
                    'total_approved' => $total_approved,
                    'total_pending' => $total_pending,
                    'total_filed' => $total_filed,
                    'total_requests' => $total_requests
                ]
            ],
            'success' => $success,
            'message' => $message
        ]);
    }
}
