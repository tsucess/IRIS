<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Street;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProfileController extends Controller
{
    /**
     * Show the user profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        // Only load id and name for street dropdown
        $streets = Street::select('id', 'name', 'zone')->orderBy('name')->get();

        return view('profile.edit', compact('user', 'streets'));
    }

    /**
     * Update the user's profile.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $user = $request->user();
            $data = $request->validated();

            // Handle photo upload with security
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');

                // Validate file type and size
                $request->validate([
                    'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
                ]);

                // Generate secure random filename
                $extension = $photo->getClientOriginalExtension();
                $filename = uniqid('profile_', true).'_'.time().'.'.$extension;

                // Delete old photo if exists
                if ($user->photo && file_exists(public_path('uploads/'.$user->photo))) {
                    unlink(public_path('uploads/'.$user->photo));
                }

                // Move file to uploads directory
                $photo->move(public_path('uploads'), $filename);
                $data['photo'] = $filename;

                Log::info('Profile photo updated', [
                    'user_id'  => $user->id,
                    'filename' => $filename,
                ]);
            }

            // Assign ID number if not yet assigned
            if (! $user->id_number) {
                $data['id_number'] = strtoupper('COMM-'.uniqid());
            }

            // Check if email was changed before filling
            $emailChanged = $user->email !== $data['email'];

            $user->fill($data);

            // Reset email verification if email was changed
            if ($emailChanged) {
                $user->email_verified_at = null;
            }

            $user->save();

            Log::info('Profile updated successfully', [
                'user_id' => $user->id,
                'email'   => $user->email,
            ]);

            return Redirect::route('profile.edit')->with('status', 'profile-updated');

        } catch (\Exception $e) {
            Log::error('Profile update failed', [
                'user_id' => $request->user()->id,
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return Redirect::route('profile.edit')
                ->with('error', 'Failed to update profile. Please try again.')
                ->withInput();
        }
    }

    /**
     * View the user's ID card.
     */
    public function idCard(Request $request): View
    {
        $user = $request->user();
        $user->load('residentExtended');

        $qrCode = QrCode::size(100)
            ->backgroundColor(255, 255, 255)
            ->generate(url("admin/users/{$user->id}/view"));

        return view('profile.idcard', [
            'user'     => $user,
            'qrCode'   => $qrCode,
            'resident' => $user->residentExtended,
        ]);
    }

    /**
     * Download the user's ID card as a PDF.
     */
    public function downloadIdCard(Request $request)
    {
        try {
            $user = $request->user();
            $user->load('residentExtended');

            $qrCode = QrCode::size(100)
                ->backgroundColor(255, 255, 255)
                ->generate(url("admin/users/{$user->id}/view"));

            $resident = $user->residentExtended;

            $pdf = Pdf::loadView('profile.idcard-pdf', compact('user', 'qrCode', 'resident'));

            Log::info('ID card downloaded', [
                'user_id' => $user->id,
                'email'   => $user->email,
            ]);

            return $pdf->download("idcard_{$user->id_number}.pdf");

        } catch (\Exception $e) {
            Log::error('ID card download failed', [
                'user_id' => $request->user()->id,
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return Redirect::route('profile.idcard')
                ->with('error', 'Failed to generate ID card. Please try again.');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
