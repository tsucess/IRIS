<?php

namespace App\Models;

use App\Models\Attachment;
use App\Models\Comment;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'street_id',
        'budget',
        'actual_cost',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'budget'      => 'float',
        'actual_cost' => 'float',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function street()
    {
        return $this->belongsTo(Street::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function allocations()
    {
        return $this->hasMany(ResourceAllocation::class);
    }

    // Quick helper for counting users
    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }

    /**
     * Check if project is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if project is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if project is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Get project duration in days.
     */
    public function getDurationInDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Get budget variance (actual cost - budget).
     */
    public function getBudgetVarianceAttribute(): float
    {
        if (! $this->actual_cost || ! $this->budget) {
            return 0;
        }

        return $this->actual_cost - $this->budget;
    }

    /**
     * Check if project is over budget.
     */
    public function isOverBudget(): bool
    {
        if (! $this->actual_cost || ! $this->budget) {
            return false;
        }

        return $this->actual_cost > $this->budget;
    }
}
