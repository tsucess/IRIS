<?php

namespace App\Models;

use App\Models\Attachment;
use App\Models\Comment;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = ['project_id', 'title', 'description', 'status', 'priority', 'due_date', 'assigned_to'];

    protected $attributes = [
        'priority' => 'medium',
    ];

    public function getPriorityBadgeColorAttribute(): string
    {
        return match ($this->priority) {
            'low'    => 'secondary',
            'medium' => 'info',
            'high'   => 'warning',
            'urgent' => 'danger',
            default  => 'secondary',
        };
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
