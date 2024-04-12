<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeWorkingHour;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\User;
use App\Models\workHours;
use App\Models\Employee; // Import the Employee model


class EmployeeWorkingHourController extends Controller
{
    public function store(Request $request)
    {
        $employee = Employee::find($id); // Fetch the employee data

        // Assuming $request->selectedDates is an array of selected date/time strings
        foreach ($request->selectedDates as $selectedDateTime) {
            $dateTimeParts = explode(', ', $selectedDateTime);
            $startTime = $dateTimeParts[1];
            $endTime = $dateTimeParts[2];
    
            // Save to database
            EmployeeWorkingHour::create([
                'employee_id' => $request->employee_id,
                'start_time' => $startTime,
                'end_time' => $endTime
            ]);
        }
    
        return view('employee.show', compact('employee'));
    }}
