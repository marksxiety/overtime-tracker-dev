<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift;

class ShiftContoller extends Controller
{
    public function insertShiftCode(Request $request)
    {

        $request->validate([
            'code' => 'unique:shift_codes|required|string|max:10',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Shift::create([
            'code' => $request->code,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->back()->with('message', 'Shift code saved successfully.');
    }

    public function registeredShiftCodes()
    {
        try {
            $shiftcodes = Shift::all();

            return inertia('Maintenance/ShiftCodes', [
                'shiftcodes' => $shiftcodes
            ]);
        } catch (\Throwable $th) {
            return inertia('Maintenance/ShiftCodes', [
                'shiftcodes' => [],
                'error' => 'Failed to fetch shift codes.'
            ]);
        }
    }
}
