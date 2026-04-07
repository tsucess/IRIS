<?php

namespace Tests\Feature;

use App\Models\Street;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidentExtendedTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Street::create([
            'name' => 'Test Street',
            'zone' => 'Zone A',
            'description' => 'Test Description',
        ]);
    }

    public function test_user_can_view_extended_profile_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.extended.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('profile.extended');
    }

    public function test_user_can_update_extended_profile(): void
    {
        $user = User::factory()->create();

        $extendedData = [
            'middle_name' => 'Ade',
            'gender' => 'male',
            'date_of_birth' => '1990-05-12',
            'place_of_birth' => 'Lagos',
            'marital_status' => 'single',
            'number_of_children' => 0,
            'ethnicity' => 'Yoruba',
            'religion' => 'Christianity',
            'address' => '123 Test Street',
            'city' => 'Ilisan',
            'state' => 'Ogun',
            'postal_code' => '121103',
            'phone_number' => '08012345678',
            'email' => $user->email,
            'education_level' => 'tertiary',
            'employment_status' => 'employed',
            'occupation' => 'Engineer',
            'income_bracket' => 'middle',
            'has_disability' => false,
            'blood_group' => 'O+',
            'is_voter' => true,
            'is_taxpayer' => true,
            'household_size' => 4,
            'access_to_electricity' => true,
            'access_to_clean_water' => true,
            'access_to_sanitation' => true,
            'internet_access' => true,
            'emergency_contact_name' => 'Jane Doe',
            'emergency_contact_phone' => '08098765432',
            'emergency_contact_relation' => 'Sister',
            'civic_participation' => 'true',
            'volunteer_activities' => 'false',
            'indigene' => true,
            'country' => 'Nigeria',
        ];

        $response = $this->actingAs($user)->post(route('profile.extended.update'), $extendedData);

        $response->assertRedirect(route('profile.extended.edit'));
        $this->assertDatabaseHas('resident_extended', [
            'user_id' => $user->id,
            'middle_name' => 'Ade',
            'gender' => 'male',
            'ethnicity' => 'Yoruba',
        ]);
    }

    public function test_admin_can_view_user_extended_profile(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.users.extended.edit', $user));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.extended');
    }

    public function test_non_admin_cannot_view_other_user_extended_profile(): void
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user1)->get(route('admin.users.extended.edit', $user2));

        $response->assertStatus(403);
    }

    public function test_admin_can_update_user_extended_profile(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $extendedData = [
            'middle_name' => 'Updated',
            'gender' => 'female',
            'ethnicity' => 'Igbo',
            'religion' => 'Islam',
        ];

        $response = $this->actingAs($admin)->post(route('admin.users.extended.update', $user), $extendedData);

        $response->assertRedirect();
        $this->assertDatabaseHas('resident_extended', [
            'user_id' => $user->id,
            'middle_name' => 'Updated',
            'gender' => 'female',
        ]);
    }

    public function test_extended_profile_validates_email_format(): void
    {
        $user = User::factory()->create();

        $extendedData = [
            'email' => 'invalid-email-format',
        ];

        $response = $this->actingAs($user)->post(route('profile.extended.update'), $extendedData);

        $response->assertSessionHasErrors('email');
    }

    public function test_extended_profile_validates_date_of_birth(): void
    {
        $user = User::factory()->create();

        $extendedData = [
            'date_of_birth' => now()->addDays(1)->format('Y-m-d'), // Future date
        ];

        $response = $this->actingAs($user)->post(route('profile.extended.update'), $extendedData);

        $response->assertSessionHasErrors('date_of_birth');
    }

    public function test_extended_profile_validates_gender_enum(): void
    {
        $user = User::factory()->create();

        $extendedData = [
            'gender' => 'InvalidGender',
        ];

        $response = $this->actingAs($user)->post(route('profile.extended.update'), $extendedData);

        $response->assertSessionHasErrors('gender');
    }
}
