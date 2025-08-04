<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

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
            $validate = Validator::make(['current_status' => $request->current_status], [
                'current_status' => ['required', Rule::in('PENDING')]
            ]);

            if ($validate->fails()) {
                return redirect()->back()->withErrors(['message' => 'Invalid Status']);
            }

            OvertimeRequest::where('id', $request->id)->update(['status' => $request->update_status]);
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
}
