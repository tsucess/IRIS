<?php

namespace Tests\Feature;

use App\Models\Occupation;
use App\Models\Street;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OccupationManagementTest extends TestCase
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

    public function test_admin_can_view_occupations_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.occupations.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.occupations.index');
    }

    public function test_non_admin_cannot_view_occupations_index(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.occupations.index'));

        $response->assertStatus(403);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.occupations.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_create_occupation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.occupations.store'), [
            'name'      => 'Software Engineer',
            'category'  => 'Technology',
            'sector'    => 'Private',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.occupations.index'));
        $this->assertDatabaseHas('occupations', [
            'name'     => 'Software Engineer',
            'category' => 'Technology',
        ]);
    }

    public function test_occupation_name_is_required(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.occupations.store'), [
            'category' => 'Technology',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_occupation_name_must_be_unique(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Occupation::create(['name' => 'Farmer', 'is_active' => true]);

        $response = $this->actingAs($admin)->post(route('admin.occupations.store'), [
            'name' => 'Farmer',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_update_occupation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $occupation = Occupation::create(['name' => 'Old Name', 'is_active' => true]);

        $response = $this->actingAs($admin)->put(route('admin.occupations.update', $occupation), [
            'name'      => 'New Name',
            'category'  => 'Agriculture',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.occupations.index'));
        $this->assertDatabaseHas('occupations', [
            'id'   => $occupation->id,
            'name' => 'New Name',
        ]);
    }

    public function test_admin_can_delete_occupation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $occupation = Occupation::create(['name' => 'Disposable', 'is_active' => true]);

        $response = $this->actingAs($admin)->delete(route('admin.occupations.destroy', $occupation));

        $response->assertRedirect(route('admin.occupations.index'));
        $this->assertSoftDeleted('occupations', ['id' => $occupation->id]);
    }
}
