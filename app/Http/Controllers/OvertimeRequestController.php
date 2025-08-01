<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class OvertimeRequestController extends Controller
{
    public function insertOvertimeRequest(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'employee_schedule_id' => 'exists:schedules,id|required',
            'date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'required|string|min:10'
        ]);

        // Parse shift times in 12-hour format
        $schdule_start_time = Carbon::createFromFormat('h:i A', trim($request->shift_start_time));
        $schdule_end_time   = Carbon::createFromFormat('h:i A', trim($request->shift_end_time));

        // Parse actual times in 24-hour format
        $submitted_start_time = Carbon::createFromFormat('H:i', trim($request->start_time));
        $submitted_end_time   = Carbon::createFromFormat('H:i', trim($request->end_time));

        // Difference in minutes
        $start_diff = $schdule_start_time->diffInMinutes($submitted_start_time, false); // negative if earlier
        $end_diff = $schdule_end_time->diffInMinutes($submitted_end_time, false);       // positive if later

        $errors = $validator->errors();

        if ($start_diff > -60) {
            $errors->add('start_time','Start time should be at least 1 hour before the scheduled start time.');
        }

        if ($end_diff < 60) {
            $errors->add('end_time','End time should be at least 1 hour after the scheduled end time.');
        }

        if ($errors->any()) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        $data = [
            'employee_schedule_id' => $request->employee_schedule_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason
        ];

        OvertimeRequest::create($data);
        return redirect()->back()->with(['message' => 'Overtime Request has been filed!']);
    }
}
