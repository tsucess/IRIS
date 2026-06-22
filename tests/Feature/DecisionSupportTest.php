<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\Project;
use App\Models\ResidentExtended;
use App\Models\Street;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class DecisionSupportTest extends TestCase
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

        Cache::flush();
    }

    public function test_admin_can_view_decision_support_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.decision-support'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.decision-support.index');
        $response->assertViewHasAll(['insights', 'recommendations', 'topComplaints', 'riskProjects']);
    }

    public function test_non_admin_cannot_view_decision_support(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.decision-support'));

        $response->assertStatus(403);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.decision-support'));

        $response->assertRedirect(route('login'));
    }

    public function test_urgent_complaint_surfaces_critical_recommendation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $reporter = User::factory()->create(['role' => 'user']);

        Complaint::create([
            'user_id'     => $reporter->id,
            'title'       => 'Urgent issue',
            'description' => 'Broken pipe',
            'category'    => 'water',
            'status'      => 'open',
            'priority'    => 'urgent',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.decision-support'));

        $response->assertStatus(200);
        $insights = $response->viewData('insights');
        $this->assertGreaterThanOrEqual(1, $insights['urgent_complaints']);

        $recs = $response->viewData('recommendations');
        $titles = array_column($recs, 'title');
        $this->assertContains('Urgent Complaints Pending', $titles);
    }

    public function test_overdue_project_appears_in_risk_projects(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $street = Street::first();

        $project = Project::create([
            'title'       => 'Late Project',
            'description' => 'Behind schedule',
            'street_id'   => $street->id,
            'status'      => 'in_progress',
            'start_date'  => now()->subDays(30),
            'end_date'    => now()->subDays(5),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.decision-support'));

        $response->assertStatus(200);
        $risk = $response->viewData('riskProjects');
        $this->assertTrue($risk->contains('id', $project->id));
    }

    public function test_low_water_access_triggers_recommendation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // 4 residents, all without clean water → 100% gap → critical rec
        for ($i = 0; $i < 4; $i++) {
            $u = User::factory()->create(['role' => 'user']);
            ResidentExtended::create([
                'user_id'               => $u->id,
                'access_to_clean_water' => false,
                'access_to_electricity' => true,
                'access_to_sanitation'  => true,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.decision-support'));

        $response->assertStatus(200);
        $titles = array_column($response->viewData('recommendations'), 'title');
        $this->assertContains('Clean Water Access', $titles);
    }
}
