<?php

namespace Tests\Feature;

use App\Models\ResidentExtended;
use App\Models\Street;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create streets for testing
        Street::create([
            'name' => 'Test Street 1',
            'zone' => 'Zone A',
            'description' => 'Test Description',
        ]);

        Street::create([
            'name' => 'Test Street 2',
            'zone' => 'Zone B',
            'description' => 'Test Description',
        ]);
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    public function test_guest_cannot_view_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_displays_total_residents_count(): void
    {
        $user = User::factory()->create();
        User::factory()->count(10)->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('totalUsers', 11); // 10 + 1 authenticated user
    }

    public function test_dashboard_displays_gender_distribution(): void
    {
        $user = User::factory()->create();

        // Create users with extended profiles
        $maleUser = User::factory()->create();
        ResidentExtended::create([
            'user_id' => $maleUser->id,
            'gender' => 'male',
        ]);

        $femaleUser = User::factory()->create();
        ResidentExtended::create([
            'user_id' => $femaleUser->id,
            'gender' => 'female',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('genderRatio');
    }

    public function test_dashboard_displays_marital_status_distribution(): void
    {
        $user = User::factory()->create();

        $marriedUser = User::factory()->create();
        ResidentExtended::create([
            'user_id' => $marriedUser->id,
            'marital_status' => 'married',
        ]);

        $singleUser = User::factory()->create();
        ResidentExtended::create([
            'user_id' => $singleUser->id,
            'marital_status' => 'single',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('maritalStatus');
    }

    public function test_dashboard_displays_education_level_distribution(): void
    {
        $user = User::factory()->create();

        $tertiaryUser = User::factory()->create();
        ResidentExtended::create([
            'user_id' => $tertiaryUser->id,
            'education_level' => 'tertiary',
        ]);

        $secondaryUser = User::factory()->create();
        ResidentExtended::create([
            'user_id' => $secondaryUser->id,
            'education_level' => 'secondary',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('educationLevels');
    }

    public function test_dashboard_displays_employment_status_distribution(): void
    {
        $user = User::factory()->create();

        $employedUser = User::factory()->create();
        ResidentExtended::create([
            'user_id' => $employedUser->id,
            'employment_status' => 'employed',
        ]);

        $unemployedUser = User::factory()->create();
        ResidentExtended::create([
            'user_id' => $unemployedUser->id,
            'employment_status' => 'unemployed',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('employmentStatus');
    }

    public function test_dashboard_can_filter_by_zone(): void
    {
        $user = User::factory()->create();

        $street = Street::where('zone', 'Zone A')->first();
        User::factory()->count(5)->create(['street_id' => $street->id]);

        $response = $this->actingAs($user)->get(route('dashboard', ['zone' => 'Zone A']));

        $response->assertStatus(200);
    }

    public function test_dashboard_displays_infrastructure_access_metrics(): void
    {
        $user = User::factory()->create();

        $userWithAccess = User::factory()->create();
        ResidentExtended::create([
            'user_id' => $userWithAccess->id,
            'access_to_electricity' => true,
            'access_to_clean_water' => true,
            'access_to_sanitation' => true,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('infrastructure');
    }
}
