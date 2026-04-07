<?php

namespace Tests\Feature\Api;

use App\Models\Street;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StreetApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticatedUser()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $token = $user->createToken('test-token')->plainTextToken;
        
        return ['user' => $user, 'token' => $token];
    }

    public function test_can_list_streets()
    {
        $auth = $this->authenticatedUser();
        Street::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->getJson('/api/v1/streets');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'zone', 'description', 'created_at', 'updated_at'],
                ],
            ]);
    }

    public function test_can_create_street()
    {
        $auth = $this->authenticatedUser();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->postJson('/api/v1/streets', [
                'name' => 'Main Street',
                'zone' => 'Zone A',
                'description' => 'Main street description',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'zone', 'description'],
            ]);

        $this->assertDatabaseHas('streets', [
            'name' => 'Main Street',
            'zone' => 'Zone A',
        ]);
    }

    public function test_can_show_street()
    {
        $auth = $this->authenticatedUser();
        $street = Street::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->getJson('/api/v1/streets/' . $street->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $street->id,
                    'name' => $street->name,
                ],
            ]);
    }

    public function test_can_update_street()
    {
        $auth = $this->authenticatedUser();
        $street = Street::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->putJson('/api/v1/streets/' . $street->id, [
                'name' => 'Updated Street',
                'zone' => 'Zone B',
                'description' => 'Updated description',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'Updated Street',
                    'zone' => 'Zone B',
                ],
            ]);

        $this->assertDatabaseHas('streets', [
            'id' => $street->id,
            'name' => 'Updated Street',
        ]);
    }

    public function test_can_delete_street()
    {
        $auth = $this->authenticatedUser();
        $street = Street::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->deleteJson('/api/v1/streets/' . $street->id);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Street deleted successfully']);

        $this->assertSoftDeleted('streets', [
            'id' => $street->id,
        ]);
    }

    public function test_street_creation_requires_name()
    {
        $auth = $this->authenticatedUser();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->postJson('/api/v1/streets', [
                'zone' => 'Zone A',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_street_name_must_be_unique()
    {
        $auth = $this->authenticatedUser();
        Street::factory()->create(['name' => 'Main Street']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->postJson('/api/v1/streets', [
                'name' => 'Main Street',
                'zone' => 'Zone A',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_can_filter_streets_by_zone()
    {
        $auth = $this->authenticatedUser();
        Street::factory()->create(['zone' => 'Zone A']);
        Street::factory()->create(['zone' => 'Zone B']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->getJson('/api/v1/streets?zone=Zone A');

        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertCount(1, $data);
        $this->assertEquals('Zone A', $data[0]['zone']);
    }
}

