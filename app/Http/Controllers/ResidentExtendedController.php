<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\ResidentExtended;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResidentExtendedController extends Controller
{
    /**
     * Show edit form for logged-in user.
     */
    public function edit()
    {
        $resident = ResidentExtended::where('user_id', Auth::id())->first();
        $user = Auth::user();

        return view('profile.extended', compact('resident', 'user'));
    }

    /**
     * Handle update for logged-in user.
     */
    public function update(Request $request)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'middle_name' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
            'place_of_birth' => 'nullable|string|max:255',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'number_of_children' => 'nullable|integer|min:0',
            'ethnicity' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'education_level' => 'nullable|in:none,primary,secondary,tertiary,vocational',
            'employment_status' => 'nullable|in:employed,unemployed,self-employed,retired',
            'occupation' => 'nullable|string|max:255',
            'income_bracket' => 'nullable|in:low,middle,high',
            'has_disability' => 'nullable|boolean',
            'blood_group' => 'nullable|string|max:10',
            'is_voter' => 'nullable|boolean',
            'is_taxpayer' => 'nullable|boolean',
            'household_size' => 'nullable|integer|min:1',
            'access_to_electricity' => 'nullable|boolean',
            'access_to_clean_water' => 'nullable|boolean',
            'access_to_sanitation' => 'nullable|boolean',
            'internet_access' => 'nullable|boolean',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:255',
            'civic_participation' => 'nullable|string|max:500',
            'volunteer_activities' => 'nullable|string|max:500',
            'indigene' => 'nullable|boolean',
            'country' => 'nullable|string|max:255',
        ]);

        ResidentExtended::updateOrCreate(
            ['user_id' => $userId],
            $validated
        );

        return redirect()->route('profile.extended.edit')->with('success', 'Profile updated successfully.');
    }

    /**
     * Show edit form for admin (editing a user’s extended profile).
     */
    public function adminEdit($userId)
    {
        // Authorization: Only admin or superadmin can edit other users
        if (! UserRole::isAdmin(Auth::user()->role)) {
            abort(403, 'Unauthorized action.');
        }

        $resident = ResidentExtended::where('user_id', $userId)->first();
        $user = User::findOrFail($userId);

        return view('admin.users.extended', compact('resident', 'user'));
    }

    /**
     * Handle update for admin (editing a user’s extended profile).
     */
    public function adminUpdate(Request $request, $userId)
    {
        // Authorization: Only admin or superadmin can update other users
        if (! UserRole::isAdmin(Auth::user()->role)) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'middle_name' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
            'place_of_birth' => 'nullable|string|max:255',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'number_of_children' => 'nullable|integer|min:0',
            'ethnicity' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'education_level' => 'nullable|in:none,primary,secondary,tertiary,vocational',
            'employment_status' => 'nullable|in:employed,unemployed,self-employed,retired',
            'occupation' => 'nullable|string|max:255',
            'income_bracket' => 'nullable|in:low,middle,high',
            'has_disability' => 'nullable|boolean',
            'blood_group' => 'nullable|string|max:10',
            'is_voter' => 'nullable|boolean',
            'is_taxpayer' => 'nullable|boolean',
            'household_size' => 'nullable|integer|min:1',
            'access_to_electricity' => 'nullable|boolean',
            'access_to_clean_water' => 'nullable|boolean',
            'access_to_sanitation' => 'nullable|boolean',
            'internet_access' => 'nullable|boolean',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:255',
            'civic_participation' => 'nullable|string|max:500',
            'volunteer_activities' => 'nullable|string|max:500',
            'indigene' => 'nullable|boolean',
            'country' => 'nullable|string|max:255',
        ]);

        ResidentExtended::updateOrCreate(
            ['user_id' => $userId],
            $validated
        );

        return redirect()->route('admin.users.extended.edit', $userId)->with('success', 'Resident details updated successfully.');
    }
}
