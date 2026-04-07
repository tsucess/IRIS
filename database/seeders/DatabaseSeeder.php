<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Project;
use App\Models\Street;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding database...');

        // Create Streets
        $this->command->info('Creating streets...');
        $streets = [
            ['name' => 'Main Street', 'zone' => 'Zone A', 'description' => 'Primary residential area'],
            ['name' => 'Oak Avenue', 'zone' => 'Zone A', 'description' => 'Quiet neighborhood'],
            ['name' => 'Maple Drive', 'zone' => 'Zone B', 'description' => 'Commercial district'],
            ['name' => 'Pine Road', 'zone' => 'Zone B', 'description' => 'Mixed residential'],
            ['name' => 'Cedar Lane', 'zone' => 'Zone C', 'description' => 'New development area'],
        ];

        foreach ($streets as $streetData) {
            Street::create($streetData);
        }

        // Create Admin User
        $this->command->info('Creating admin user...');
        $admin = User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@commdevsys.com',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN->value,
            'street_id' => 1,
            'phone' => '1234567890',
            'id_number' => 'ADMIN001',
        ]);

        // Create Superadmin User
        $this->command->info('Creating superadmin user...');
        User::create([
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'email' => 'superadmin@commdevsys.com',
            'password' => Hash::make('password'),
            'role' => UserRole::SUPERADMIN->value,
            'street_id' => 1,
            'phone' => '0987654321',
            'id_number' => 'SUPER001',
        ]);

        // Create Regular Users
        $this->command->info('Creating regular users...');
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'firstname' => fake()->firstName(),
                'lastname' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'role' => UserRole::USER->value,
                'street_id' => fake()->numberBetween(1, 5),
                'phone' => fake()->phoneNumber(),
                'id_number' => 'RES'.str_pad($i, 4, '0', STR_PAD_LEFT),
            ]);
        }

        // Create Projects
        $this->command->info('Creating projects...');
        $projects = [
            [
                'title' => 'Community Center Renovation',
                'description' => 'Renovate the main community center building',
                'street_id' => 1,
                'status' => 'in_progress',
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(60),
            ],
            [
                'title' => 'Street Lighting Installation',
                'description' => 'Install LED street lights on Main Street',
                'street_id' => 1,
                'status' => 'pending',
                'start_date' => now()->addDays(10),
                'end_date' => now()->addDays(40),
            ],
            [
                'title' => 'Park Development',
                'description' => 'Create a new community park in Zone B',
                'street_id' => 3,
                'status' => 'pending',
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(90),
            ],
            [
                'title' => 'Road Repair Project',
                'description' => 'Repair damaged roads in Zone C',
                'street_id' => 5,
                'status' => 'completed',
                'start_date' => now()->subDays(60),
                'end_date' => now()->subDays(10),
            ],
        ];

        foreach ($projects as $projectData) {
            $project = Project::create($projectData);
            // Assign some users to projects
            $project->users()->attach([1, 2, fake()->numberBetween(3, 10)]);
        }

        // Call extended profile seeder
        $this->command->info('Creating extended profiles...');
        $this->call(ResidentExtendedSeeder::class);

        $this->command->newLine();
        $this->command->info('✅ Database seeded successfully!');
        $this->command->newLine();
        $this->command->table(
            ['Credential', 'Value'],
            [
                ['Admin Email', 'admin@commdevsys.com'],
                ['Admin Password', 'password'],
                ['Superadmin Email', 'superadmin@commdevsys.com'],
                ['Superadmin Password', 'password'],
            ]
        );
    }
}
