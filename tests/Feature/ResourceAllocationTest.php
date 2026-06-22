<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ResourceAllocation;
use App\Models\Street;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceAllocationTest extends TestCase
{
    use RefreshDatabase;

    private Street $street;

    protected function setUp(): void
    {
        parent::setUp();

        $this->street = Street::create([
            'name' => 'Test Street',
            'zone' => 'Zone A',
            'description' => 'Test Description',
        ]);
    }

    private function makeProject(): Project
    {
        return Project::create([
            'title' => 'Demo Project',
            'description' => 'desc',
            'street_id' => $this->street->id,
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => now()->addDays(10),
        ]);
    }

    public function test_admin_can_view_project_allocations(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = $this->makeProject();

        $response = $this->actingAs($admin)->get(route('projects.allocations.index', $project));

        $response->assertStatus(200);
        $response->assertViewIs('allocations.index');
    }

    public function test_admin_can_view_allocations_overview(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('allocations.overview'));

        $response->assertStatus(200);
        $response->assertViewIs('allocations.overview');
    }

    public function test_admin_can_create_allocation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = $this->makeProject();

        $response = $this->actingAs($admin)->post(route('projects.allocations.store', $project), [
            'resource_type'    => 'funds',
            'name'             => 'Cement Purchase',
            'unit'             => 'NGN',
            'allocated_amount' => 500000,
            'used_amount'      => 0,
            'status'           => 'planned',
        ]);

        $response->assertRedirect(route('projects.allocations.index', $project));
        $this->assertDatabaseHas('resource_allocations', [
            'project_id' => $project->id,
            'name'       => 'Cement Purchase',
        ]);
    }

    public function test_non_admin_non_member_cannot_view_project_allocations(): void
    {
        $stranger = User::factory()->create(['role' => 'user']);
        $project = $this->makeProject();

        $response = $this->actingAs($stranger)->get(route('projects.allocations.index', $project));

        $response->assertStatus(403);
    }

    public function test_regular_user_cannot_create_allocation(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $project = $this->makeProject();
        $project->users()->attach($user->id);

        $response = $this->actingAs($user)->post(route('projects.allocations.store', $project), [
            'resource_type'    => 'funds',
            'name'             => 'Whatever',
            'allocated_amount' => 1000,
            'status'           => 'planned',
        ]);

        $response->assertStatus(403);
    }

    public function test_allocation_requires_name_and_amount(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = $this->makeProject();

        $response = $this->actingAs($admin)->post(route('projects.allocations.store', $project), [
            'resource_type' => 'funds',
            'status'        => 'planned',
        ]);

        $response->assertSessionHasErrors(['name', 'allocated_amount']);
    }

    public function test_admin_can_update_allocation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = $this->makeProject();
        $allocation = ResourceAllocation::create([
            'project_id' => $project->id,
            'resource_type' => 'funds',
            'name' => 'Initial',
            'allocated_amount' => 1000,
            'status' => 'planned',
        ]);

        $response = $this->actingAs($admin)->put(
            route('projects.allocations.update', [$project, $allocation]),
            [
                'resource_type'    => 'funds',
                'name'             => 'Updated',
                'allocated_amount' => 2000,
                'used_amount'      => 500,
                'status'           => 'in_use',
            ]
        );

        $response->assertRedirect(route('projects.allocations.index', $project));
        $this->assertDatabaseHas('resource_allocations', [
            'id' => $allocation->id, 'name' => 'Updated', 'allocated_amount' => 2000,
        ]);
    }

    public function test_admin_can_delete_allocation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = $this->makeProject();
        $allocation = ResourceAllocation::create([
            'project_id' => $project->id,
            'resource_type' => 'materials',
            'name' => 'Bricks',
            'allocated_amount' => 100,
            'status' => 'planned',
        ]);

        $response = $this->actingAs($admin)->delete(
            route('projects.allocations.destroy', [$project, $allocation])
        );

        $response->assertRedirect(route('projects.allocations.index', $project));
        $this->assertSoftDeleted('resource_allocations', ['id' => $allocation->id]);
    }
}
