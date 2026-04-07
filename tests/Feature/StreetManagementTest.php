<?php

namespace Tests\Feature;

use App\Models\Street;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StreetManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_streets_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('streets.index'));

        $response->assertStatus(200);
        $response->assertViewIs('streets.index');
    }

    public function test_non_admin_cannot_view_streets_index(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('streets.index'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_view_streets_index(): void
    {
        $response = $this->get(route('streets.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_create_street(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $streetData = [
            'name' => 'New Street',
            'zone' => 'Zone A',
            'description' => 'A new street in Zone A',
        ];

        $response = $this->actingAs($admin)->post(route('streets.store'), $streetData);

        $response->assertRedirect(route('streets.index'));
        $this->assertDatabaseHas('streets', [
            'name' => 'New Street',
            'zone' => 'Zone A',
        ]);
    }

    public function test_admin_can_update_street(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $street = Street::create([
            'name' => 'Old Street Name',
            'zone' => 'Zone A',
            'description' => 'Old description',
        ]);

        $updateData = [
            'name' => 'Updated Street Name',
            'zone' => 'Zone B',
            'description' => 'Updated description',
        ];

        $response = $this->actingAs($admin)->put(route('streets.update', $street), $updateData);

        $response->assertRedirect(route('streets.index'));
        $this->assertDatabaseHas('streets', [
            'id' => $street->id,
            'name' => 'Updated Street Name',
            'zone' => 'Zone B',
        ]);
    }

    public function test_admin_can_delete_street(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $street = Street::create([
            'name' => 'Street to Delete',
            'zone' => 'Zone A',
            'description' => 'This will be deleted',
        ]);

        $response = $this->actingAs($admin)->delete(route('streets.destroy', $street));

        $response->assertRedirect(route('streets.index'));
        $this->assertSoftDeleted('streets', ['id' => $street->id]);
    }

    public function test_street_creation_requires_name(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $streetData = [
            'zone' => 'Zone A',
            'description' => 'Street without name',
        ];

        $response = $this->actingAs($admin)->post(route('streets.store'), $streetData);

        $response->assertSessionHasErrors('name');
    }

    public function test_street_creation_requires_zone(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $streetData = [
            'name' => 'Street Name',
            'description' => 'Street without zone',
        ];

        $response = $this->actingAs($admin)->post(route('streets.store'), $streetData);

        $response->assertSessionHasErrors('zone');
    }

    public function test_street_name_must_be_unique(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Street::create([
            'name' => 'Duplicate Street',
            'zone' => 'Zone A',
            'description' => 'First street',
        ]);

        $streetData = [
            'name' => 'Duplicate Street',
            'zone' => 'Zone B',
            'description' => 'Duplicate name',
        ];

        $response = $this->actingAs($admin)->post(route('streets.store'), $streetData);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_view_street_details(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $street = Street::create([
            'name' => 'Test Street',
            'zone' => 'Zone A',
            'description' => 'Test description',
        ]);

        $response = $this->actingAs($admin)->get(route('streets.show', $street));

        $response->assertStatus(200);
        $response->assertViewIs('streets.show');
        $response->assertViewHas('street', $street);
    }
}
