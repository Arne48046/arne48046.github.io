<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\User;
use App\Models\workHours;
use App\Models\Employee; // Import the Employee model
use Illuminate\Http\Request;
use Carbon\Carbon;
class EmployeeController extends Controller
{
    /**
     * Show a list of all of the application's users.
     */

    public function view(): View
    {
        // Get users with the role 'employee' from the 'users' table
        $users = DB::table('users')
           ->where('role', 'employee')
           ->get();
    
        // If there are new users to add as employees, insert them into the 'employees' table
        foreach ($users as $user) {
           $existingEmployee = DB::table('employees')->where('user_id', $user->id)->first();
           if (!$existingEmployee) {
               DB::table('employees')->insert([
                  'user_id' => $user->id,
                  'name' => $user->name,
                  'email' => $user->email,
                  'worked_hours' => 0,
                  'active_worked_hours' => 0,
                  'inactive_worked_hours' => 0,
               ]);
           }
        }
    
        // Fetch all employees including newly added ones
        $allEmployees = DB::table('employees')->get();
        // Execute the SQL statement to update worked hours
DB::statement("UPDATE employees AS e
SET e.worked_hours = COALESCE((
    SELECT SUM(TIMESTAMPDIFF(HOUR, ewh.start_time, ewh.end_time))
    FROM employee_working_hours AS ewh
    WHERE ewh.employee_id = e.user_id
), 0);");

        return view('employees', ['employees' => $allEmployees]);
    }
     
    public function show($id)
    {
        $employee = DB::table('employees')
                 ->where('user_id', $id)
                 ->first();
        $month = request()->input('month', Carbon::now()->month); // Get selected month or current month if not provided
    
        $startOfMonth = Carbon::create(null, $month, 1, 0, 0, 0)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
    
        $workedHours = DB::table('employee_working_hours')
                        ->where('employee_id', $id)
                        ->whereBetween('end_time', [$startOfMonth, $endOfMonth])
                        ->get();
    
        return view('employee.show', ['employee' => $employee, 'workedHours' => $workedHours, 'selectedMonth' => $month]);
    }
    public function update(Request $request, $id)
        {
            $employee = DB::table('employees')->where('user_id', $id)->first();
            if (!$employee) {
                return redirect()->back()->with('error', 'Employee not found.');
            }

            DB::table('employees')
                ->where('user_id', $id)
                ->update([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'), // Update email field
                    'updated_at' => now()
                ]);
            
            return redirect()->back()->with('success', 'Employee information updated successfully.');
        }
    public function destroy($id)
    {
        $employee = DB::table('users')->where('id', $id)->first();
        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found.');
        }

        DB::table('users')
            ->where('id', $id)
            ->delete();
        
        return redirect()->route('employees.view')->with('success', 'Employee deleted successfully.');
    }
    public function employees()
    {
        $allEmployees = DB::table('employees')->get();
        $employeeOccupation  = DB::table('employee_working_hours')->get();

        return view('planning', ['employees' => $allEmployees], ['employeeOccupation' => $employeeOccupation]);
    }

    public function store(Request $request)
    {
        // Get the start and end time values from the form
        $date = $request->input('date');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
    
        // Combine the current date with the time values and format them
        $startDateTime = date('Y-m-d H:i:s', strtotime($date . ' ' . $startTime));
        $endDateTime = date('Y-m-d H:i:s', strtotime($date . ' ' . $endTime));
    
        // Fetch the selected employee's name from the employees table
        $employeeId = $request->input('employeeSelect');
        $employee = Employee::where('user_id', $employeeId)->firstOrFail();
        $employeeName = $employee->name;
    
        // Create a new entry in the 'employee_working_hours' table
        $workingHours = new WorkHours();
        $workingHours->employee_id = $employeeId;
        $workingHours->name = $employeeName;
        $workingHours->start_time = $startDateTime;
        $workingHours->end_time = date('Y-m-d H:i:s', strtotime($endDateTime . ' -1 minute'));
        $workingHours->save();
        // Update employees with null worked_hours to 0
     // Execute the SQL statement to update worked hours
DB::statement("UPDATE employees AS e
SET e.worked_hours = COALESCE((
    SELECT SUM(TIMESTAMPDIFF(HOUR, ewh.start_time, ewh.end_time))
    FROM employee_working_hours AS ewh
    WHERE ewh.employee_id = e.user_id
), 0);");


        return redirect()->route('planning')->with('success', 'Hours added successfully.');
    }
    
    public function getEmployeesWorkingAtTime(Request $request)
    {

        $time = $request->input('time');
        $employees = WorkHours::where('start_time', '<=', $time)
            ->where('end_time', '>=', $time)
            ->get();

        // Return the list of employees as JSON response
        return response()->json($employees);
    }
    public function getEmployeeOccupation($id)
    {
        $employeeOccupation = WorkHours::where('employee_id', $id)->get();
        return response()->json($employeeOccupation);
    }

    public function employeeWorktimeDelete($id)
    {
        $worktime = WorkHours::find($id);
        $worktime->delete();
        return redirect()->route('planning')->with('success', 'Worktime deleted successfully.');
    }
    public function showWorkedHours($id)
    {
        $employee = Employee::where('user_id', $id)->firstOrFail();
        
    }
}