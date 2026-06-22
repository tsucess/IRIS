<?php

namespace Tests\Feature;

use App\Models\Street;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipationTrendTest extends TestCase
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

    public function test_admin_can_view_participation_trends(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.analytics.participation'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.analytics.participation');
        $response->assertViewHasAll([
            'months', 'labels', 'civicSeries', 'volunteerSeries',
            'complaintSeries', 'projectMembers', 'byCategory', 'totals',
        ]);
    }

    public function test_non_admin_cannot_view_participation_trends(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.analytics.participation'));

        $response->assertStatus(403);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.analytics.participation'));

        $response->assertRedirect(route('login'));
    }

    public function test_participation_data_endpoint_returns_json(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->getJson(route('admin.analytics.participation.data'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'labels', 'civicSeries', 'volunteerSeries',
            'complaintSeries', 'projectMembers', 'totals',
        ]);
    }

    public function test_months_window_is_clamped(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Should accept and clamp to 36 max
        $response = $this->actingAs($admin)->get(route('admin.analytics.participation', ['months' => 999]));

        $response->assertStatus(200);
        $months = $response->viewData('months');
        $this->assertLessThanOrEqual(36, $months);
        $this->assertGreaterThanOrEqual(3, $months);
    }
}
