<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Street;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StreetModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_street_has_fillable_attributes(): void
    {
        $street = new Street;

        $fillable = [
            'name',
            'zone',
            'description',
        ];

        $this->assertEquals($fillable, $street->getFillable());
    }

    public function test_street_has_many_users(): void
    {
        $street = Street::create([
            'name' => 'Test Street',
            'zone' => 'Zone A',
            'description' => 'Test Description',
        ]);

        User::factory()->count(3)->create(['street_id' => $street->id]);

        $this->assertCount(3, $street->users);
    }

    public function test_street_has_many_projects(): void
    {
        $street = Street::create([
            'name' => 'Test Street',
            'zone' => 'Zone A',
            'description' => 'Test Description',
        ]);

        Project::create([
            'title' => 'Project 1',
            'description' => 'Description 1',
            'street_id' => $street->id,
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);

        Project::create([
            'title' => 'Project 2',
            'description' => 'Description 2',
            'street_id' => $street->id,
            'status' => 'in_progress',
            'start_date' => now(),
            'end_date' => now()->addDays(60),
        ]);

        $this->assertCount(2, $street->projects);
    }

    public function test_street_name_is_required(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Street::create([
            'zone' => 'Zone A',
            'description' => 'Test Description',
        ]);
    }

    public function test_street_zone_is_optional(): void
    {
        $street = Street::create([
            'name' => 'Test Street',
            'description' => 'Test Description',
        ]);

        $this->assertNull($street->zone);
    }

    public function test_street_name_must_be_unique(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Street::create([
            'name' => 'Duplicate Street',
            'zone' => 'Zone A',
            'description' => 'First street',
        ]);

        Street::create([
            'name' => 'Duplicate Street',
            'zone' => 'Zone B',
            'description' => 'Second street',
        ]);
    }

    public function test_street_can_be_soft_deleted(): void
    {
        $street = Street::create([
            'name' => 'Test Street',
            'zone' => 'Zone A',
            'description' => 'Test Description',
        ]);

        $streetId = $street->id;
        $street->delete();

        $this->assertSoftDeleted('streets', ['id' => $streetId]);
    }

    public function test_street_residents_count_accessor(): void
    {
        $street = Street::create([
            'name' => 'Test Street',
            'zone' => 'Zone A',
            'description' => 'Test Description',
        ]);

        User::factory()->count(5)->create(['street_id' => $street->id]);

        $street->loadCount('users');
        $this->assertEquals(5, $street->users_count);
    }

    public function test_street_projects_count_accessor(): void
    {
        $street = Street::create([
            'name' => 'Test Street',
            'zone' => 'Zone A',
            'description' => 'Test Description',
        ]);

        Project::create([
            'title' => 'Project 1',
            'description' => 'Description 1',
            'street_id' => $street->id,
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);

        $street->loadCount('projects');
        $this->assertEquals(1, $street->projects_count);
    }

    public function test_street_full_name_accessor(): void
    {
        $street = Street::create([
            'name' => 'Main Street',
            'zone' => 'Zone A',
            'description' => 'Test Description',
        ]);

        $this->assertEquals('Main Street (Zone A)', $street->full_name);
    }
}
