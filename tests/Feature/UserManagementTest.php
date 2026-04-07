<?php

namespace Tests\Feature;

use App\Models\Street;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a street for testing
        Street::create([
            'name' => 'Test Street',
            'zone' => 'Zone A',
            'description' => 'Test Description',
        ]);
    }

    public function test_admin_can_view_users_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
    }

    public function test_non_admin_cannot_view_users_index(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.users.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $street = Street::first();

        $userData = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
            'street_id' => $street->id,
            'id_number' => 'ID123456',
        ];

        $response = $this->actingAs($admin)->post(route('admin.users.store'), $userData);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'firstname' => 'John',
            'lastname' => 'Doe',
        ]);
    }

    public function test_admin_can_update_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $street = \App\Models\Street::factory()->create();
        $user = User::factory()->create([
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'email' => 'jane@example.com',
            'street_id' => $street->id,
        ]);

        $updateData = [
            'firstname' => 'Janet',
            'lastname' => 'Smith',
            'email' => 'janet@example.com',
            'role' => 'user',
            'street_id' => $street->id,
        ];

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user), $updateData);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'firstname' => 'Janet',
            'lastname' => 'Smith',
            'email' => 'janet@example.com',
        ]);
    }

    public function test_admin_can_delete_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_user_creation_requires_valid_email(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $street = \App\Models\Street::factory()->create();

        $userData = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
            'street_id' => $street->id,
        ];

        $response = $this->actingAs($admin)->post(route('admin.users.store'), $userData);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_creation_requires_password_confirmation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $street = \App\Models\Street::factory()->create();

        $userData = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'DifferentPassword123!',
            'role' => 'user',
            'street_id' => $street->id,
        ];

        $response = $this->actingAs($admin)->post(route('admin.users.store'), $userData);

        $response->assertSessionHasErrors('password');
    }
}
