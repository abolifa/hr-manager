<?php

namespace App\Models;

use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'target_mode',
        'target_role',
        'target_company_ids',
        'for_all',
        'attachments',
        'company_id',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'target_company_ids' => 'array',
        'attachments' => 'array',
    ];


    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_task')
            ->withTimestamps()
            ->withPivot('status');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    public function assignedEmployees()
    {
        return $this->belongsToMany(Employee::class, 'employee_task')
            ->wherePivot('status', 'assigned');
    }

    public function inProgressEmployees()
    {
        return $this->belongsToMany(Employee::class, 'employee_task')
            ->wherePivot('status', 'in_progress');
    }

    public function doneEmployees()
    {
        return $this->belongsToMany(Employee::class, 'employee_task')
            ->wherePivot('status', 'done');
    }
}
