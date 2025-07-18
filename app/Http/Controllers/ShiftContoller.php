<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift;
use Illuminate\Validation\Rule;

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
    public function updateShiftCode(Request $request, Shift $shift)
    {
        $request->validate([
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('shift_codes')->ignore($shift->id),
            ],
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
        ]);

        $shift->update([
            'code' => $request->code,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->back()->with('message', 'Shift code updated successfully.');
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

    public function deleteShiftCode(Shift $shift) 
    {
        try {
            $shift->delete();
            return redirect()->back()->with('message', 'Shift code deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errors', 'Shift code deleted unsuccessful.');
        }
    }
}
