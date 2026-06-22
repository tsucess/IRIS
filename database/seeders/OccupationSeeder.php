<?php

namespace Database\Seeders;

use App\Models\Occupation;
use Illuminate\Database\Seeder;

class OccupationSeeder extends Seeder
{
    public function run(): void
    {
        $occupations = [
            ['name' => 'Teacher',           'category' => 'Professional', 'sector' => 'Education'],
            ['name' => 'Doctor',            'category' => 'Professional', 'sector' => 'Health'],
            ['name' => 'Nurse',             'category' => 'Professional', 'sector' => 'Health'],
            ['name' => 'Engineer',          'category' => 'Professional', 'sector' => 'Engineering'],
            ['name' => 'Software Developer','category' => 'Professional', 'sector' => 'Technology'],
            ['name' => 'Accountant',        'category' => 'Professional', 'sector' => 'Finance'],
            ['name' => 'Lawyer',            'category' => 'Professional', 'sector' => 'Legal'],
            ['name' => 'Farmer',            'category' => 'Skilled',      'sector' => 'Agriculture'],
            ['name' => 'Trader',            'category' => 'Self-Employed','sector' => 'Commerce'],
            ['name' => 'Civil Servant',     'category' => 'Professional', 'sector' => 'Government'],
            ['name' => 'Mechanic',          'category' => 'Skilled',      'sector' => 'Automotive'],
            ['name' => 'Carpenter',         'category' => 'Skilled',      'sector' => 'Construction'],
            ['name' => 'Mason',             'category' => 'Skilled',      'sector' => 'Construction'],
            ['name' => 'Electrician',       'category' => 'Skilled',      'sector' => 'Construction'],
            ['name' => 'Tailor',            'category' => 'Skilled',      'sector' => 'Fashion'],
            ['name' => 'Driver',            'category' => 'Skilled',      'sector' => 'Transport'],
            ['name' => 'Student',           'category' => 'Other',        'sector' => 'Education'],
            ['name' => 'Retired',           'category' => 'Other',        'sector' => 'N/A'],
            ['name' => 'Unemployed',        'category' => 'Other',        'sector' => 'N/A'],
            ['name' => 'Other',             'category' => 'Other',        'sector' => 'N/A'],
        ];

        foreach ($occupations as $row) {
            Occupation::firstOrCreate(
                ['name' => $row['name']],
                array_merge($row, ['is_active' => true])
            );
        }
    }
}
