<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;


class ScheduleController extends Controller
{
    public function fetchSchedule(Request $request)
    {
        try {
            $days = [];
            $user_id = $request->input('user_id');
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

            $schedules = Schedule::where('user_id', $user_id)->whereYear('date', $year)->where('week', $week)->get();

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
                'schedules' => $days
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

        // loop the info (update only the data that the id's not null)
        // having a null id it means that the certain row is not yet registered
        try {
            foreach ($info as $item) {
                if ($item['id']) {
                    Schedule::where('id', $item['id'])->update([
                        'user_id' => Auth::id(),
                        'shift_id' => $item['shift_code'],
                        'date' => $item['date'],
                        'week' => $item['week'],
                    ]);
                } else {

                    // if the shift code is not provided, avoid that index to be created.
                    if (!$item['shift_code']) {
                        continue;
                    }

                    Schedule::create([
                        'user_id' => Auth::id(),
                        'shift_id' => $item['shift_code'],
                        'date' => $item['date'],
                        'week' => $item['week'],
                    ]);
                }
            }

            $message = 'Submission Successful';
            $success = true;
        } catch (\Throwable $th) {
            $success = false;
            $message = "Submission Failed";
        }

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }
}
