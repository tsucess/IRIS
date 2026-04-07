<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Street;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectModelTest extends TestCase
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

    public function test_project_has_fillable_attributes(): void
    {
        $project = new Project;

        $fillable = [
            'title',
            'description',
            'start_date',
            'end_date',
            'status',
            'street_id',
            'budget',
            'actual_cost',
        ];

        $this->assertEquals($fillable, $project->getFillable());
    }

    public function test_project_belongs_to_street(): void
    {
        $street = Street::first();

        $project = Project::create([
            'title' => 'Test Project',
            'description' => 'Test Description',
            'street_id' => $street->id,
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);

        $this->assertInstanceOf(Street::class, $project->street);
        $this->assertEquals($street->id, $project->street->id);
    }

    public function test_project_belongs_to_many_users(): void
    {
        $street = Street::first();

        $project = Project::create([
            'title' => 'Test Project',
            'description' => 'Test Description',
            'street_id' => $street->id,
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $project->users()->attach([$user1->id, $user2->id]);

        $this->assertCount(2, $project->users);
    }

    public function test_project_status_defaults_to_pending(): void
    {
        $street = Street::first();

        $project = Project::create([
            'title' => 'Test Project',
            'description' => 'Test Description',
            'street_id' => $street->id,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);

        $this->assertEquals('pending', $project->status);
    }

    public function test_project_can_be_soft_deleted(): void
    {
        $street = Street::first();

        $project = Project::create([
            'title' => 'Test Project',
            'description' => 'Test Description',
            'street_id' => $street->id,
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);

        $projectId = $project->id;
        $project->delete();

        $this->assertSoftDeleted('projects', ['id' => $projectId]);
    }

    public function test_project_is_pending_method(): void
    {
        $street = Street::first();

        $project = Project::create([
            'title' => 'Test Project',
            'description' => 'Test Description',
            'street_id' => $street->id,
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);

        $this->assertTrue($project->isPending());
    }

    public function test_project_is_in_progress_method(): void
    {
        $street = Street::first();

        $project = Project::create([
            'title' => 'Test Project',
            'description' => 'Test Description',
            'street_id' => $street->id,
            'status' => 'in_progress',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);

        $this->assertTrue($project->isInProgress());
    }

    public function test_project_is_completed_method(): void
    {
        $street = Street::first();

        $project = Project::create([
            'title' => 'Test Project',
            'description' => 'Test Description',
            'street_id' => $street->id,
            'status' => 'completed',
            'start_date' => now()->subDays(60),
            'end_date' => now()->subDays(30),
        ]);

        $this->assertTrue($project->isCompleted());
    }

    public function test_project_duration_in_days_accessor(): void
    {
        $street = Street::first();

        $startDate = now();
        $endDate = now()->addDays(30);

        $project = Project::create([
            'title' => 'Test Project',
            'description' => 'Test Description',
            'street_id' => $street->id,
            'status' => 'pending',
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        $this->assertEquals(30, $project->duration_in_days);
    }

    public function test_project_budget_variance_accessor(): void
    {
        $street = Street::first();

        // Under budget: actual_cost < budget → variance is negative
        $underBudget = Project::create([
            'title'       => 'Under Budget Project',
            'street_id'   => $street->id,
            'status'      => 'pending',
            'start_date'  => now(),
            'end_date'    => now()->addDays(30),
            'budget'      => 5000.00,
            'actual_cost' => 3000.00,
        ]);

        $this->assertEquals(-2000.0, $underBudget->budget_variance);

        // Over budget: actual_cost > budget → variance is positive
        $overBudget = Project::create([
            'title'       => 'Over Budget Project',
            'street_id'   => $street->id,
            'status'      => 'pending',
            'start_date'  => now(),
            'end_date'    => now()->addDays(30),
            'budget'      => 5000.00,
            'actual_cost' => 7000.00,
        ]);

        $this->assertEquals(2000.0, $overBudget->budget_variance);

        // No budget set → returns 0
        $noBudget = Project::create([
            'title'      => 'No Budget Project',
            'street_id'  => $street->id,
            'status'     => 'pending',
            'start_date' => now(),
            'end_date'   => now()->addDays(30),
        ]);

        $this->assertEquals(0, $noBudget->budget_variance);
    }

    public function test_project_is_over_budget_method(): void
    {
        $street = Street::first();

        // Over budget
        $overBudget = Project::create([
            'title'       => 'Over Budget Project',
            'street_id'   => $street->id,
            'status'      => 'pending',
            'start_date'  => now(),
            'end_date'    => now()->addDays(30),
            'budget'      => 5000.00,
            'actual_cost' => 7000.00,
        ]);

        $this->assertTrue($overBudget->isOverBudget());

        // Under budget
        $underBudget = Project::create([
            'title'       => 'Under Budget Project',
            'street_id'   => $street->id,
            'status'      => 'pending',
            'start_date'  => now(),
            'end_date'    => now()->addDays(30),
            'budget'      => 5000.00,
            'actual_cost' => 3000.00,
        ]);

        $this->assertFalse($underBudget->isOverBudget());

        // No budget set → not over budget
        $noBudget = Project::create([
            'title'      => 'No Budget Project',
            'street_id'  => $street->id,
            'status'     => 'pending',
            'start_date' => now(),
            'end_date'   => now()->addDays(30),
        ]);

        $this->assertFalse($noBudget->isOverBudget());
    }
}
