<?php

namespace Database\Seeders;

use App\Models\ResidentExtended;
use App\Models\User;
use Illuminate\Database\Seeder;

class ResidentExtendedSeeder extends Seeder
{
    public function run(): void
    {
        // Make sure you already have some users in 'users' table
        $users = User::take(5)->get(); // pick first 5 users for demo

        foreach ($users as $user) {
            ResidentExtended::create([
                'user_id' => $user->id,
                'middle_name' => 'Ade',
                'gender' => 'male',
                'date_of_birth' => '1990-05-12',
                'place_of_birth' => 'Ilisan, Ogun State',
                'marital_status' => 'single',
                'number_of_children' => 0,
                'ethnicity' => 'Yoruba',
                'religion' => 'Christianity',

                // Contact & Address
                'address' => 'No. 12 Market Road',
                'city' => 'Ilisan',
                'state' => 'Ogun',
                'postal_code' => '121103',
                'phone_number' => '08012345678',
                'email' => $user->email,

                // Education & Employment
                'education_level' => 'tertiary',
                'employment_status' => 'employed',
                'occupation' => 'Teacher',
                'income_bracket' => 'middle',

                // Health
                'has_disability' => false,
                'blood_group' => 'O+',

                // Civic
                'is_voter' => true,
                'is_taxpayer' => true,

                // Household
                'household_size' => 4,
                'access_to_electricity' => true,
                'access_to_clean_water' => true,
                'access_to_sanitation' => true,
                'internet_access' => true,

                // Emergency Contact
                'emergency_contact_name' => 'Ogunleye John',
                'emergency_contact_phone' => '08098765432',
                'emergency_contact_relation' => 'Brother',

                // Community
                'civic_participation' => true,
                'volunteer_activities' => false,
            ]);
        }
    }
}
