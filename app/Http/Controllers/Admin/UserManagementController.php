<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\ResidentExtended;
use App\Models\Street;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('street')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $streets = Street::all();

        // Auto-generate password
        $autoPassword = Str::random(10); // You can increase length

        return view('admin.users.create', compact('streets', 'autoPassword'));
    }

    public function idCard(User $user): View
    {
        $this->authorize('view', $user);

        $user->load('residentExtended');

        $qrCode = QrCode::size(100)
            ->backgroundColor(255, 255, 255)
            ->generate(url("admin/users/{$user->id}/view"));

        return view('admin.users.idcard', [
            'user'     => $user,
            'qrCode'   => $qrCode,
            'resident' => $user->residentExtended,
        ]);
    }

    public function downloadIdCard(User $user)
    {
        $this->authorize('view', $user);

        $user->load('residentExtended');

        $qrCode = QrCode::size(100)
            ->backgroundColor(255, 255, 255)
            ->generate(url("admin/users/{$user->id}/view"));

        $resident = $user->residentExtended;

        $pdf = Pdf::loadView('admin.users.idcard-pdf', compact('user', 'qrCode', 'resident'));

        return $pdf->download("ID-{$user->id_number}.pdf");
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);

        // Generate ID number if not provided
        if (empty($validated['id_number'])) {
            $validated['id_number'] = strtoupper('COMM-'.uniqid());
        }

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo'] = $path;
        }

        // Create the user
        $user = User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User created successfully. ID: {$user->id_number}");
    }

    public function edit(User $user)
    {
        $streets = Street::all();

        return view('admin.users.edit', compact('user', 'streets'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        // Hash password if provided
        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo'] = $path;
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    public function exportView(Request $request)
    {
        $filters = $request->only([
            'gender', 'marital_status', 'indigene', 'has_disability',
            'income_bracket', 'education_level', 'religion', 'ethnicity',
            'employment_status', 'age_range',
        ]);

        $isSqlite = \DB::connection()->getDriverName() === 'sqlite';

        $query = User::with('residentExtended')
            ->join('resident_extended', 'users.id', '=', 'resident_extended.user_id')
            ->whereHas('residentExtended', function ($q) use ($filters, $isSqlite) {
                if (! empty($filters['gender'])) {
                    $q->where('gender', $filters['gender']);
                }
                if (! empty($filters['marital_status'])) {
                    $q->where('marital_status', $filters['marital_status']);
                }
                if (! empty($filters['indigene'])) {
                    $q->where('indigene', $filters['indigene']);
                }
                if (! empty($filters['has_disability'])) {
                    $q->where('has_disability', $filters['has_disability']);
                }
                if (! empty($filters['income_bracket'])) {
                    $q->where('income_bracket', $filters['income_bracket']);
                }
                if (! empty($filters['education_level'])) {
                    $q->where('education_level', $filters['education_level']);
                }
                if (! empty($filters['religion'])) {
                    $q->where('religion', $filters['religion']);
                }
                if (! empty($filters['ethnicity'])) {
                    $q->where('ethnicity', $filters['ethnicity']);
                }
                if (! empty($filters['employment_status'])) {
                    $q->where('employment_status', $filters['employment_status']);
                }
                if (! empty($filters['age_range'])) {
                    $ranges = [
                        '18-25' => [18, 25],
                        '26-35' => [26, 35],
                        '36-50' => [36, 50],
                        '51+'   => [51, 200],
                    ];
                    if (isset($ranges[$filters['age_range']])) {
                        [$min, $max] = $ranges[$filters['age_range']];
                        if ($isSqlite) {
                            // SQLite: use strftime for age calculation
                            $q->whereRaw(
                                "(strftime('%Y', 'now') - strftime('%Y', date_of_birth)) BETWEEN ? AND ?",
                                [$min, $max]
                            );
                        } else {
                            $q->whereRaw(
                                'TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN ? AND ?',
                                [$min, $max]
                            );
                        }
                    }
                }
            });

        $ethnicities = ResidentExtended::whereNotNull('ethnicity')
            ->distinct()
            ->orderBy('ethnicity')
            ->pluck('ethnicity');

        $users = $query->get();

        return view('exports.users', compact('users', 'ethnicities'));
    }

    public function export(Request $request)
    {
        $filters = $request->all();

        return Excel::download(new UsersExport($filters), 'users.xlsx');
    }
}
