<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionManagementController extends Controller
{
    public function index(Request $request)
    {
        $sessions = DB::table('sessions')
            ->where('user_id', auth()->id())
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) use ($request) {
                $agent = $session->user_agent ?? 'Unknown';

                return (object) [
                    'id'            => $session->id,
                    'ip_address'    => $session->ip_address,
                    'user_agent'    => $agent,
                    'is_current'    => $session->id === request()->session()->getId(),
                    'last_activity' => \Carbon\Carbon::createFromTimestamp($session->last_activity),
                ];
            });

        return view('sessions.index', compact('sessions'));
    }

    public function destroy(Request $request, string $sessionId)
    {
        // Prevent deleting own current session through this route
        if ($sessionId === $request->session()->getId()) {
            return back()->with('error', 'You cannot revoke your current session here. Use Logout instead.');
        }

        DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('success', 'Session revoked successfully.');
    }
}
