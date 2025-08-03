<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OvertimeRequest;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ScheduleController extends Controller
{
    public function fetchSchedule(Request $request)
    {
        try {
            $days = [];
            $year = $request->input('year', now()->year);
            $week = $request->input('week', now()->isoWeek);

            $date = CarbonImmutable::now('Asia/Manila')->setISODate($year, $week);

            // Loop through 7 days of the week (data displaying is by week only)
            for ($i = 0; $i < 7; $i++) {
                $current = $date->addDays($i);
                $days[] = [
                    'id' => null,
                    'date' => $current->toDateString(),
                    'week' => $week,
                    'day' => $current->format('l'),
                    'shift_code' => null
                ];
            }

            $schedules = Schedule::where('user_id',  Auth::id())->whereYear('date', $year)->where('week', $week)->get();

            // populate the shift_code value if it matches the date
            // this will identify if the user has a current schedule on the specific day
            foreach ($schedules as &$schedule) {
                for ($j = 0; $j < count($days); $j++) {
                    if ($schedule['date'] === $days[$j]['date']) {
                        $days[$j]['id'] = $schedule['id'];
                        $days[$j]['shift_code'] = $schedule['shift_id'];
                        break;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'proceed',
                'schedules' => $days,
                'id' =>  Auth::id()
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'schedules' => [],
                'success' => false,
                'message' => "Failed to fetch schedules due to $th"
            ]);
        }
    }

    public function submitSchedule(Request $request)
    {
        $info = $request->input('schedule');
        $resultSchedules = [];

        try {
            foreach ($info as $item) {

                if (!empty($item['id'])) {
                    Schedule::where('id', $item['id'])->update([
                        'user_id' => Auth::id(),
                        'shift_id' => $item['shift_code'],
                        'date' => $item['date'],
                        'week' => $item['week'],
                    ]);

                    $updated = Schedule::find($item['id']);
                    $resultSchedules[] = [
                        'id' => $updated->id,
                        'date' => $updated->date,
                        'week' => $updated->week,
                        'day' => $item['day'],
                        'shift_code' => $updated->shift_id,
                    ];
                } else {
                    if (empty($item['shift_code'])) {
                        $resultSchedules[] = [
                            'id' => null,
                            'date' => $item['date'],
                            'week' => $item['week'],
                            'day' => $item['day'],
                            'shift_code' => null
                        ];
                        continue;
                    }

                    $created = Schedule::create([
                        'user_id' => Auth::id(),
                        'shift_id' => $item['shift_code'],
                        'date' => $item['date'],
                        'week' => $item['week'],
                    ]);

                    $resultSchedules[] = [
                        'id' => $created->id,
                        'date' => $created->date,
                        'week' => $created->week,
                        'day' => $item['day'],
                        'shift_code' => $created->shift_id,
                    ];
                }
            }

            $success = true;
            $message = 'Submission Successful';
        } catch (\Throwable $th) {
            $success = false;
            $message = 'Submission Failed';
            $resultSchedules = [];
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'schedules' => $resultSchedules
        ]);
    }


    public function getUserSchedule(Request $request)
    {
        $registered = [];
        try {
            $schedule = Schedule::with('shift')->where('user_id', Auth::id())->whereYear('date', $request->year)->whereMonth('date', $request->month)->whereDay('date', $request->day)->get(
                ['id', 'shift_id', 'date', 'week']
            )->first();

            if ($schedule) {
                $registered = [
                    'id' => $schedule->id,
                    'shift_id' => $schedule->shift_id,
                    'shift_code' => $schedule->shift?->code,
                    'shift_start_time' => ($schedule->shift->start_time == null) ? '--' : date('h:i A', strtotime($schedule->shift?->start_time)),
                    'shift_end_time' => ($schedule->shift->end_time == null) ? '--' : date('h:i A', strtotime($schedule->shift?->end_time)),
                    'date' => $schedule->date,
                    'week' => $schedule->week
                ];
            }

            $success = true;
            $message = 'Fetching Successful';
        } catch (\Throwable $th) {
            $success = false;
            $message = 'Fetching Failed ' . $th;
            $schedule = null;
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'schedule' => $registered
        ]);
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
