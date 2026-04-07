<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserSearchController extends Controller
{
    /**
     * Show search page.
     */
    public function index()
    {
        return view('admin.users.search');
    }

    /**
     * Handle AJAX search request.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        $users = User::query()
            ->leftJoin('resident_extended', 'users.id', '=', 'resident_extended.user_id')
            ->where(function ($q) use ($query) {
                $q->where('users.firstname', 'like', "%{$query}%")
                    ->orWhere('users.lastname', 'like', "%{$query}%")
                    ->orWhere('users.email', 'like', "%{$query}%")
                    ->orWhere('resident_extended.middle_name', 'like', "%{$query}%")
                    ->orWhere('users.id_number', 'like', "%{$query}%");
            })
            ->select('users.*', 'resident_extended.middle_name')
            ->get();

        return response()->json($users);
    }

    public function view(User $user)
    {
        $user->load('residentExtended');

        return view('admin.users.view', compact('user'));
    }
}
