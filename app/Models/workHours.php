<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workHours extends Model
{
    protected $table = 'employee_working_hours';
    protected $fillable = ['employee_id', 'start_time', 'end_time'];
}
