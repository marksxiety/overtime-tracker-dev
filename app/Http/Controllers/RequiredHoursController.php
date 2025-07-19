<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RequiredHours;
use Illuminate\Http\Request;

class RequiredHoursController extends Controller
{
    public function registerRequiredHours(Request $request) {
        
        $data = $request->validate([
            'year' => 'required|integer|max_digits:4',
            'week' =>  'required|integer|max_digits:2',
            'required_hours' =>  'required|integer|max_digits:4'
        ]);

        RequiredHours::create($data);
        return redirect()->back()->with(['message' => 'Required Hours for week has been registered']);
    }

    public function registeredRequiredHours() {

        try {
            $requiredhours = RequiredHours::query()->orderBy('year', 'desc')->orderBy('week', 'desc')->get();

            return inertia('Maintenance/RequiredHours', [
                'requiredhours' => $requiredhours
            ]);
        } catch (\Throwable $th) {
            return inertia('Maintenance/RequiredHours', [
                'requiredhours' => [],
                'errors' => 'Failed to load registered Required Hours'
            ]);
        }

    }
}
