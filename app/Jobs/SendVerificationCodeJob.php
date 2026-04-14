<?php

namespace App\Jobs;

use App\Mail\VerificationCodeMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendVerificationCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Delay before retrying after failure (seconds).
     */
    public int $backoff = 30;

    public function __construct(
        public readonly User $user,
        public readonly string $code
    ) {}

    public function handle(): void
    {
        Mail::to($this->user->email)
            ->send(new VerificationCodeMail($this->code, $this->user->firstname));

        Log::info('Verification code email sent via queue', [
            'user_id' => $this->user->id,
            'email'   => $this->user->email,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Verification code email job failed', [
            'user_id' => $this->user->id,
            'email'   => $this->user->email,
            'error'   => $exception->getMessage(),
        ]);
    }
}
