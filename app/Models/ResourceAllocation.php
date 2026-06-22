<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceAllocation extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'project_id',
        'allocated_by',
        'resource_type',
        'name',
        'unit',
        'allocated_amount',
        'used_amount',
        'status',
        'allocated_at',
        'notes',
    ];

    protected $casts = [
        'allocated_at'     => 'date',
        'allocated_amount' => 'float',
        'used_amount'      => 'float',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function allocatedBy()
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }

    public function getRemainingAttribute(): float
    {
        return max(0, (float) $this->allocated_amount - (float) $this->used_amount);
    }

    public function getUtilizationPercentAttribute(): float
    {
        if (! $this->allocated_amount || (float) $this->allocated_amount == 0.0) {
            return 0.0;
        }

        return round(((float) $this->used_amount / (float) $this->allocated_amount) * 100, 1);
    }

    public function isOverAllocated(): bool
    {
        return (float) $this->used_amount > (float) $this->allocated_amount;
    }
}
