<?php

namespace App\Models;

use Database\Factories\TaskUpdateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskUpdate extends Model
{
    /** @use HasFactory<TaskUpdateFactory> */
    use HasFactory;

    protected $fillable = [
        'task_id',
        'employee_id',
    ];
}
