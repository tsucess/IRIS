<?php

namespace App\Http\Controllers;

use App\Jobs\SendVerificationCodeJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TwoFactorController extends Controller
{
    /** Show 2FA setup page */
    public function setup()
    {
        return view('auth.two-factor-setup', ['user' => auth()->user()]);
    }

    /** Enable 2FA — generate & store secret, send test code */
    public function enable(Request $request)
    {
        $user = auth()->user();
        $secret = bin2hex(random_bytes(16)); // 32-char hex secret
        $user->forceFill([
            'two_factor_enabled' => true,
            'two_factor_secret'  => $secret,
        ])->save();

        // Send a confirmation email
        SendVerificationCodeJob::dispatch($user, substr(strtoupper($secret), 0, 6));

        Log::info('2FA enabled', ['user_id' => $user->id]);

        return back()->with('success', '2FA has been enabled for your account.');
    }

    /** Disable 2FA */
    public function disable(Request $request)
    {
        $request->validate(['password' => 'required|current_password']);

        auth()->user()->forceFill([
            'two_factor_enabled'    => false,
            'two_factor_secret'     => null,
            'two_factor_verified_at'=> null,
        ])->save();

        return back()->with('success', '2FA has been disabled.');
    }

    /** Show the 2FA verification form (after login) */
    public function showVerify()
    {
        if (session('two_factor_user_id') === null) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-verify');
    }

    /** Verify the 2FA code submitted after login */
    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $userId = session('two_factor_user_id');
        if (! $userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::findOrFail($userId);

        // Compare code against the first 6 chars of hex secret (demo mode)
        $expected = strtoupper(substr($user->two_factor_secret, 0, 6));
        if (strtoupper($request->code) !== $expected) {
            return back()->withErrors(['code' => 'Invalid 2FA code.']);
        }

        // Mark verified & complete login
        $user->forceFill(['two_factor_verified_at' => now()])->save();
        auth()->login($user);
        session()->forget('two_factor_user_id');
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
