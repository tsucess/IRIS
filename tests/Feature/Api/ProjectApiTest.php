<?php

namespace Tests\Feature\Api;

use App\Models\Project;
use App\Models\Street;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticatedUser()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $token = $user->createToken('test-token')->plainTextToken;
        
        return ['user' => $user, 'token' => $token];
    }

    public function test_can_list_projects()
    {
        $auth = $this->authenticatedUser();
        Project::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->getJson('/api/v1/projects');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'description', 'status', 'created_at'],
                ],
            ]);
    }

    public function test_can_create_project()
    {
        $auth = $this->authenticatedUser();
        $street = Street::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->postJson('/api/v1/projects', [
                'title' => 'New Project',
                'description' => 'Project description',
                'status' => 'pending',
                'start_date' => '2026-01-10',
                'end_date' => '2026-02-10',
                'street_id' => $street->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'title', 'status'],
            ]);

        $this->assertDatabaseHas('projects', [
            'title' => 'New Project',
            'status' => 'pending',
        ]);
    }

    public function test_can_show_project()
    {
        $auth = $this->authenticatedUser();
        $project = Project::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->getJson('/api/v1/projects/' . $project->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $project->id,
                    'title' => $project->title,
                ],
            ]);
    }

    public function test_can_update_project()
    {
        $auth = $this->authenticatedUser();
        $project = Project::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->putJson('/api/v1/projects/' . $project->id, [
                'title' => 'Updated Project',
                'description' => 'Updated description',
                'status' => 'in_progress',
                'start_date' => '2026-01-10',
                'end_date' => '2026-02-10',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'Updated Project',
                    'status' => 'in_progress',
                ],
            ]);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Updated Project',
        ]);
    }

    public function test_can_delete_project()
    {
        $auth = $this->authenticatedUser();
        $project = Project::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->deleteJson('/api/v1/projects/' . $project->id);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Project deleted successfully']);

        $this->assertSoftDeleted('projects', [
            'id' => $project->id,
        ]);
    }

    public function test_project_creation_requires_title()
    {
        $auth = $this->authenticatedUser();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->postJson('/api/v1/projects', [
                'status' => 'pending',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_can_filter_projects_by_status()
    {
        $auth = $this->authenticatedUser();
        Project::factory()->create(['status' => 'pending']);
        Project::factory()->create(['status' => 'in_progress']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->getJson('/api/v1/projects?status=pending');

        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertCount(1, $data);
        $this->assertEquals('pending', $data[0]['status']);
    }

    public function test_can_assign_users_to_project()
    {
        $auth = $this->authenticatedUser();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->postJson('/api/v1/projects', [
                'title' => 'Team Project',
                'status' => 'pending',
                'user_ids' => [$user1->id, $user2->id],
            ]);

        $response->assertStatus(201);

        $project = Project::where('title', 'Team Project')->first();
        $this->assertCount(2, $project->users);
    }
}

