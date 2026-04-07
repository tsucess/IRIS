<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Street;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
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

    public function test_authenticated_user_can_view_projects(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('projects.index'));

        $response->assertStatus(200);
        $response->assertViewIs('projects.index');
    }

    public function test_guest_cannot_view_projects(): void
    {
        $response = $this->get(route('projects.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_create_project(): void
    {
        $user = User::factory()->create();
        $street = Street::first();

        $projectData = [
            'title' => 'Road Repair Project',
            'description' => 'Repair main road',
            'street_id' => $street->id,
            'status' => 'pending',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(30)->format('Y-m-d'),
        ];

        $response = $this->actingAs($user)->post(route('projects.store'), $projectData);

        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', [
            'title' => 'Road Repair Project',
            'street_id' => $street->id,
        ]);
    }

    public function test_user_can_update_project(): void
    {
        $user = User::factory()->create();
        $street = Street::first();

        $project = Project::create([
            'title' => 'Old Project Name',
            'description' => 'Old description',
            'street_id' => $street->id,
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);

        $updateData = [
            'title' => 'Updated Project Name',
            'description' => 'Updated description',
            'street_id' => $street->id,
            'status' => 'in_progress',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(30)->format('Y-m-d'),
        ];

        $response = $this->actingAs($user)->put(route('projects.update', $project), $updateData);

        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Updated Project Name',
            'status' => 'in_progress',
        ]);
    }

    public function test_user_can_delete_project(): void
    {
        $user = User::factory()->create();
        $street = Street::first();

        $project = Project::create([
            'title' => 'Project to Delete',
            'description' => 'This will be deleted',
            'street_id' => $street->id,
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);

        $response = $this->actingAs($user)->delete(route('projects.destroy', $project));

        $response->assertRedirect(route('projects.index'));
        $this->assertSoftDeleted('projects', ['id' => $project->id]);
    }

    public function test_project_requires_title(): void
    {
        $user = User::factory()->create();
        $street = Street::first();

        $projectData = [
            'description' => 'Project without title',
            'street_id' => $street->id,
            'status' => 'pending',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(30)->format('Y-m-d'),
        ];

        $response = $this->actingAs($user)->post(route('projects.store'), $projectData);

        $response->assertSessionHasErrors('title');
    }

    public function test_project_end_date_must_be_after_start_date(): void
    {
        $user = User::factory()->create();
        $street = Street::first();

        $projectData = [
            'title' => 'Invalid Date Project',
            'description' => 'End date before start date',
            'street_id' => $street->id,
            'status' => 'pending',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->subDays(10)->format('Y-m-d'),
        ];

        $response = $this->actingAs($user)->post(route('projects.store'), $projectData);

        $response->assertSessionHasErrors('end_date');
    }
}
