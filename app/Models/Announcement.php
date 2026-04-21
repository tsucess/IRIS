<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'body', 'audience', 'pinned', 'expires_at',
    ];

    protected $casts = [
        'pinned'     => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isActive(): bool
    {
        return $this->expires_at === null || $this->expires_at->isFuture();
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeVisibleTo($query, ?User $user = null)
    {
        return $query->where(function ($q) use ($user) {
            // Guests (unauthenticated) only see announcements meant for everyone
            if ($user === null) {
                $q->where('audience', 'all');
                return;
            }

            $q->where('audience', 'all')
              ->orWhere(function ($q2) use ($user) {
                  if ($user->isAdmin()) {
                      $q2->where('audience', 'admins');
                  } else {
                      $q2->where('audience', 'residents');
                  }
              });
        });
    }
}
