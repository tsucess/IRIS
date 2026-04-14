<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Street extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['name', 'zone', 'description'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Add this:
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get street's full name with zone.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name} ({$this->zone})";
    }
}
