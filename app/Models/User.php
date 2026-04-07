<?php

namespace App\Models;

use App\Mail\VerificationCodeMail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'phone',
        'street_id',
        'role',
        'photo',
        'id_number',
        'email_verification_code',
        'email_verification_code_expires_at',
    ];

    protected $attributes = [
        'role' => 'user',
    ];

    public function street()
    {
        return $this->belongsTo(Street::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function assignedTasks()
    {
        return $this->belongsToMany(Task::class, 'task_user');
    }

    public function residentExtended()
    {
        return $this->hasOne(ResidentExtended::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'                  => 'datetime',
            'email_verification_code_expires_at' => 'datetime',
            'password'                           => 'hashed',
        ];
    }

    /**
     * Generate a 4-digit verification code, store it, and send it by email.
     */
    public function sendEmailVerificationNotification(): void
    {
        $code = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        $this->email_verification_code            = $code;
        $this->email_verification_code_expires_at = now()->addMinutes(15);
        $this->save();

        Mail::to($this->email)->send(new VerificationCodeMail($code, $this->firstname));

        Log::info('Verification code sent', ['user_id' => $this->id, 'email' => $this->email]);
    }

    /**
     * Check whether the submitted code is valid and not expired.
     */
    public function isValidVerificationCode(string $code): bool
    {
        return $this->email_verification_code === $code
            && $this->email_verification_code_expires_at
            && now()->lessThanOrEqualTo($this->email_verification_code_expires_at);
    }

    /**
     * Mark email as verified and clear the stored code.
     */
    public function markEmailAsVerified(): bool
    {
        $result = $this->forceFill([
            'email_verified_at'                  => now(),
            'email_verification_code'            => null,
            'email_verification_code_expires_at' => null,
        ])->save();

        Log::info('Email verified', ['user_id' => $this->id, 'email' => $this->email]);

        return $result;
    }

    /**
     * Check if user is an admin or superadmin.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    /**
     * Get user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    /**
     * Get user's photo URL.
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('uploads/'.$this->photo);
        }

        return asset('images/default-avatar.png');
    }
}
