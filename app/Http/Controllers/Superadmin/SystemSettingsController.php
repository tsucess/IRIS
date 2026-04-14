<?php

namespace App\Http\Controllers\Superadmin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SystemSettingsController extends Controller
{
    /**
     * Show system settings & overview dashboard for superadmins.
     */
    public function index()
    {
        $adminUsers = User::whereIn('role', [UserRole::ADMIN->value, UserRole::SUPERADMIN->value])
            ->with('street')
            ->latest()
            ->paginate(20);

        $stats = Cache::remember('superadmin_stats', 60, function () {
            return [
                'total_users'      => User::count(),
                'total_admins'     => User::where('role', UserRole::ADMIN->value)->count(),
                'total_superadmins'=> User::where('role', UserRole::SUPERADMIN->value)->count(),
                'verified_users'   => User::whereNotNull('email_verified_at')->count(),
                'unverified_users' => User::whereNull('email_verified_at')->count(),
                'soft_deleted'     => User::onlyTrashed()->count(),
            ];
        });

        return view('superadmin.settings', compact('adminUsers', 'stats'));
    }

    /**
     * Promote a user to admin role (superadmin only).
     */
    public function promoteToAdmin(User $user)
    {
        abort_if($user->role === UserRole::SUPERADMIN->value, 403, 'Cannot change superadmin role.');

        $user->update(['role' => UserRole::ADMIN->value]);

        return back()->with('success', "{$user->full_name} has been promoted to Admin.");
    }

    /**
     * Demote an admin to regular user (superadmin only).
     */
    public function demoteToUser(User $user)
    {
        abort_if($user->role === UserRole::SUPERADMIN->value, 403, 'Cannot demote a superadmin.');

        $user->update(['role' => UserRole::USER->value]);

        return back()->with('success', "{$user->full_name} has been demoted to User.");
    }

    /**
     * Restore a soft-deleted user.
     */
    public function restore(int $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return back()->with('success', 'User restored successfully.');
    }

    /**
     * Clear all application caches.
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        return back()->with('success', 'Application cache cleared successfully.');
    }
}
