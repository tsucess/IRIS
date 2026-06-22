<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Occupation extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'category',
        'sector',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function residents()
    {
        return $this->hasMany(ResidentExtended::class, 'occupation_id');
    }

    public function getResidentsCountAttribute(): int
    {
        return $this->residents()->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
