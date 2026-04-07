<?php

namespace App\Exports;

use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = array_filter($filters, fn ($v) => $v !== null && $v !== '');
    }

    public function query()
    {
        $q = User::query()->with(['residentExtended', 'extended']);

        // Helper to apply a whereHas on either relation name that exists
        $whereOnExt = function (callable $cb) use ($q) {
            $q->where(function ($sub) use ($cb) {
                $sub->whereHas('residentExtended', $cb)
                    ->orWhereHas('extended', $cb);
            });
        };

        // Gender
        if (! empty($this->filters['gender'])) {
            $whereOnExt(fn ($e) => $e->where('gender', $this->filters['gender']));
        }

        // Marital status
        if (! empty($this->filters['marital_status'])) {
            $whereOnExt(fn ($e) => $e->where('marital_status', $this->filters['marital_status']));
        }

        // Indigene (0/1)
        if (isset($this->filters['indigene']) && $this->filters['indigene'] !== '') {
            $whereOnExt(fn ($e) => $e->where('indigene', (int) $this->filters['indigene']));
        }

        // Employment
        if (! empty($this->filters['employment_status'])) {
            $whereOnExt(fn ($e) => $e->where('employment_status', $this->filters['employment_status']));
        }

        // Education level
        if (! empty($this->filters['education_level'])) {
            $whereOnExt(fn ($e) => $e->where('education_level', $this->filters['education_level']));
        }

        // Income bracket
        if (! empty($this->filters['income_bracket'])) {
            $whereOnExt(fn ($e) => $e->where('income_bracket', $this->filters['income_bracket']));
        }

        // Disability (0/1)
        if (isset($this->filters['has_disability']) && $this->filters['has_disability'] !== '') {
            $whereOnExt(fn ($e) => $e->where('has_disability', (int) $this->filters['has_disability']));
        }

        // Religion
        if (! empty($this->filters['religion'])) {
            $whereOnExt(fn ($e) => $e->where('religion', $this->filters['religion']));
        }

        // Ethnicity
        if (! empty($this->filters['ethnicity'])) {
            $whereOnExt(fn ($e) => $e->where('ethnicity', 'like', '%'.$this->filters['ethnicity'].'%'));
        }

        // Age filter (support either "age_range" like "26-35" or "age_from/age_to")
        $ageFrom = $this->filters['age_from'] ?? null;
        $ageTo = $this->filters['age_to'] ?? null;

        if (! empty($this->filters['age_range'])) {
            // e.g. "26-35" or "51+"
            if (preg_match('/^(\d+)\-(\d+)$/', $this->filters['age_range'], $m)) {
                [$all, $ageFrom, $ageTo] = $m;
            } elseif (preg_match('/^(\d+)\+$/', $this->filters['age_range'], $m)) {
                $ageFrom = (int) $m[1];
                $ageTo = null;
            }
        }

        if ($ageFrom !== null || $ageTo !== null) {
            // Convert age range to date_of_birth range
            $now = Carbon::now();
            // DOB is between [now - ageTo, now - ageFrom]
            $minDob = $ageTo !== null ? $now->copy()->subYears((int) $ageTo)->startOfDay() : null;   // oldest
            $maxDob = $ageFrom !== null ? $now->copy()->subYears((int) $ageFrom)->endOfDay() : null; // youngest

            $whereOnExt(function ($e) use ($minDob, $maxDob) {
                if ($minDob && $maxDob) {
                    $e->whereBetween('date_of_birth', [$minDob, $maxDob]);
                } elseif ($minDob) {
                    $e->where('date_of_birth', '<=', $minDob);
                } elseif ($maxDob) {
                    $e->where('date_of_birth', '>=', $maxDob);
                }
            });
        }

        return $q->orderBy('id');
    }

    public function map($user): array
    {
        $ext = $user->residentExtended ?? $user->extended;

        return [
            $user->id,                                                     // ID
            trim(($user->firstname ?? '').' '.($user->lastname ?? '')),    // Name
            $user->email,                                                  // Email
            optional($ext)->gender,                                        // Gender
            optional($ext)->marital_status,                                // Marital Status
            optional($ext)->employment_status,                             // Employment Status
            optional($ext)->education_level,                               // Education Level
            optional($ext)->income_bracket,                                // Income Bracket
            $ext ? ($ext->has_disability ? 'Yes' : 'No') : null,           // Disability
            optional($ext)->religion,                                      // Religion
            optional($ext)->ethnicity,                                     // Ethnicity
            optional($user->created_at)?->toDateTimeString(),              // Created At
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Gender',
            'Marital Status',
            'Employment Status',
            'Education Level',
            'Income Bracket',
            'Disability',
            'Religion',
            'Ethnicity',
            'Created At',
        ];
    }
}

// namespace App\Exports;

// use App\Models\User;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithHeadings;

// class UsersExport implements FromCollection, WithHeadings
// {
//     protected $filters;

//     public function __construct(array $filters = [])
//     {
//         $this->filters = $filters;
//     }

//     public function collection()
//     {
//         $query = User::with('residentExtended');

//         // Apply filters
//         if (!empty($this->filters['gender'])) {
//             $query->whereHas('residentExtended', fn($q) => $q->where('gender', $this->filters['gender']));
//         }

//         if (!empty($this->filters['marital_status'])) {
//             $query->whereHas('residentExtended', fn($q) => $q->where('marital_status', $this->filters['marital_status']));
//         }

//         if (!empty($this->filters['indigene'])) {
//             $query->whereHas('residentExtended', fn($q) => $q->where('indigene', $this->filters['indigene']));
//         }

//         if (!empty($this->filters['employment_status'])) {
//             $query->whereHas('residentExtended', fn($q) => $q->where('employment_status', $this->filters['employment_status']));
//         }

//         if (!empty($this->filters['education_level'])) {
//             $query->whereHas('residentExtended', fn($q) => $q->where('education_level', $this->filters['education_level']));
//         }

//         if (!empty($this->filters['income_bracket'])) {
//             $query->whereHas('residentExtended', fn($q) => $q->where('income_bracket', $this->filters['income_bracket']));
//         }

//         if (!empty($this->filters['has_disability'])) {
//             $query->whereHas('residentExtended', fn($q) => $q->where('has_disability', $this->filters['has_disability']));
//         }

//         if (!empty($this->filters['religion'])) {
//             $query->whereHas('residentExtended', fn($q) => $q->where('religion', $this->filters['religion']));
//         }

//         if (!empty($this->filters['ethnicity'])) {
//             $query->whereHas('residentExtended', fn($q) => $q->where('ethnicity', $this->filters['ethnicity']));
//         }

//         // Example: Handle age range filter
//         if (!empty($this->filters['age_range'])) {
//             [$min, $max] = explode('-', $this->filters['age_range']);
//             $query->whereHas('residentExtended', fn($q) =>
//                 $q->whereBetween('age', [(int) $min, (int) $max])
//             );
//         }

//         return $query->get();
//     }

//     public function headings(): array
//     {
//         return [
//             'ID',
//             'Name',
//             'Email',
//             'Gender',
//             'Marital Status',
//             'Employment Status',
//             'Education Level',
//             'Income Bracket',
//             'Disability',
//             'Religion',
//             'Ethnicity',
//             'Created At',
//         ];
//     }
// }

// class UsersExport implements FromCollection, WithHeadings
// {
//     public function collection()
//     {
//         return User::with('residentExtended')->get()->map(function ($user) {
//             return [
//                 $user->firstname,
//                 $user->lastname,
//                 $user->email,
//                 $user->phone,
//                 optional($user->residentExtended)->gender,
//                 optional($user->residentExtended)->marital_status,
//                 optional($user->residentExtended)->indigene ? 'Yes' : 'No',
//                 optional($user->residentExtended)->employment_status,
//                 optional($user->residentExtended)->education_level,
//                 optional($user->residentExtended)->income_bracket,
//                 optional($user->residentExtended)->has_disability ? 'Yes' : 'No',
//                 optional($user->residentExtended)->religion,
//                 optional($user->residentExtended)->ethnicity,
//             ];
//         });
//     }

//     public function headings(): array
//     {
//         return [
//             'Firstname',
//             'Lastname',
//             'Email',
//             'Phone',
//             'Gender',
//             'Marital Status',
//             'Indigene',
//             'Employment',
//             'Education',
//             'Income',
//             'Disability',
//             'Religion',
//             'Ethnicity',
//         ];
//     }
// }

// use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Concerns\FromView;

// class UsersExport implements FromView
// {
//     public function __construct(
//         private array $filters = []
//     ) {}

//     public function view(): View
//     {
//         $query = User::with('residentExtended');

//         // ✅ Gender
//         if (!empty($this->filters['gender'])) {
//             $query->where('gender', $this->filters['gender']);
//         }

//         // ✅ Age Range (assuming date_of_birth column exists)
//         if (!empty($this->filters['age_from']) && !empty($this->filters['age_to'])) {
//             $from = now()->subYears($this->filters['age_to'])->startOfDay();
//             $to   = now()->subYears($this->filters['age_from'])->endOfDay();
//             $query->whereBetween('date_of_birth', [$from, $to]);
//         }

//         // ✅ Marital Status
//         if (!empty($this->filters['marital_status'])) {
//             $query->where('marital_status', $this->filters['marital_status']);
//         }

//         // ✅ Indigene
//         if (isset($this->filters['indigene'])) {
//             $query->where('indigene', $this->filters['indigene']);
//         }

//         // ✅ Employment Status
//         if (!empty($this->filters['employment_status'])) {
//             $query->whereHas('residentExtended', function ($q) {
//                 $q->where('employment_status', $this->filters['employment_status']);
//             });
//         }

//         // ✅ Education Status
//         if (!empty($this->filters['education_status'])) {
//             $query->whereHas('residentExtended', function ($q) {
//                 $q->where('education_status', $this->filters['education_status']);
//             });
//         }

//         // ✅ Income
//         if (!empty($this->filters['income'])) {
//             $query->whereHas('residentExtended', function ($q) {
//                 $q->where('income', $this->filters['income']);
//             });
//         }

//         // ✅ Disability
//         if (!empty($this->filters['disability'])) {
//             $query->whereHas('residentExtended', function ($q) {
//                 $q->where('disability', $this->filters['disability']);
//             });
//         }

//         // ✅ Religion
//         if (!empty($this->filters['religion'])) {
//             $query->whereHas('residentExtended', function ($q) {
//                 $q->where('religion', $this->filters['religion']);
//             });
//         }

//         // ✅ Ethnicity
//         if (!empty($this->filters['ethnicity'])) {
//             $query->whereHas('residentExtended', function ($q) {
//                 $q->where('ethnicity', $this->filters['ethnicity']);
//             });
//         }

//         return view('exports.users', [
//             'users' => $query->get()
//         ]);
//     }

// }
