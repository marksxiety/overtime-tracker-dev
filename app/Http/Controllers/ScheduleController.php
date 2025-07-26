<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Carbon\Carbon;
use Carbon\CarbonImmutable;


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
                    'date' => $current->toDateString(),
                    'week' => $week,
                    'day' => $current->format('l'),
                    'shift_code' => null
                ];
            }

            $schedules = Schedule::where('user_id', $user_id)->whereYear('date', $year)->where('week', $week)->get();

            // populate the shift_code value if it matches the date
            // this will identify if the user has a current schedule on the specific day
            foreach ($schedules as $schedule) {
                for ($j = 0; $j < count($days); $j++) {
                    if ($schedule['date'] === $days[$j]['date']) {
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
}
