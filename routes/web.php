<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\EmployeeWorkingHourController;

use App\Http\Middleware\Role;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(Role::class . ':employee,owner')->group(function () {
        Route::get('/planning', [EmployeeController::class, 'employees'])->name('planning');
        Route::post('/planning', [EmployeeController::class, 'store'])->name('planning.store');
        
        Route::get('/get-employee-working-hours/{id}', [EmployeeController::class, 'getEmployeeOccupation']);
        Route::get('/get-employees-working-at-time', [EmployeeController::class, 'getEmployeesWorkingAtTime']);
        Route::delete('/employee/worktime/{id}', [EmployeeController::class, 'employeeWorktimeDelete'])->name('worktime.delete');

        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    });
    

    Route::middleware(Role::class . ':owner')->group(function () {
        Route::get('/employees', [EmployeeController::class, 'view'])->name('employees.view');
        Route::get('/employee/{id}', [EmployeeController::class, 'show'])->name('employee.show');
        Route::put('/employee/{id}', [EmployeeController::class, 'update'])->name('employee.update');
        Route::delete('/employee/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
        
    });
});


// Navigation Links
Route::redirect('/home', '/');

Route::get('/privacystatement', function () {
    return view('privacystatement');
})->name('privacystatement');
Route::get('/products', [ProductController::class, 'show'])->name('products');


Route::get('/appointment', function () {
    return view('appointment');
})->name('appointment');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');





require __DIR__.'/auth.php';
