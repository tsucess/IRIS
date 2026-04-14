<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'description', 'category',
        'status', 'priority', 'assigned_to', 'admin_notes', 'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'open'      => 'warning',
            'in_review' => 'info',
            'resolved'  => 'success',
            'rejected'  => 'danger',
            default     => 'secondary',
        };
    }

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
}
