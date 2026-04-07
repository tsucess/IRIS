<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\ResidentExtended;
use App\Models\Street;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
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

    public function test_user_has_fillable_attributes(): void
    {
        $user = new User;

        $fillable = [
            'firstname',
            'lastname',
            'email',
            'password',
            'phone',
            'street_id',
            'role',
            'photo',
            'id_number',
        ];

        $this->assertEquals($fillable, $user->getFillable());
    }

    public function test_user_has_hidden_attributes(): void
    {
        $user = new User;

        $hidden = [
            'password',
            'remember_token',
        ];

        $this->assertEquals($hidden, $user->getHidden());
    }

    public function test_user_belongs_to_street(): void
    {
        $street = Street::first();
        $user = User::factory()->create(['street_id' => $street->id]);

        $this->assertInstanceOf(Street::class, $user->street);
        $this->assertEquals($street->id, $user->street->id);
    }

    public function test_user_has_one_resident_extended(): void
    {
        $user = User::factory()->create();

        ResidentExtended::create([
            'user_id' => $user->id,
            'middle_name' => 'Test',
            'gender' => 'male',
        ]);

        $this->assertInstanceOf(ResidentExtended::class, $user->residentExtended);
        $this->assertEquals('Test', $user->residentExtended->middle_name);
    }

    public function test_user_belongs_to_many_projects(): void
    {
        $user = User::factory()->create();
        $street = Street::first();

        $project = Project::create([
            'title' => 'Test Project',
            'description' => 'Test Description',
            'street_id' => $street->id,
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);

        $user->projects()->attach($project->id);

        $this->assertCount(1, $user->projects);
        $this->assertEquals('Test Project', $user->projects->first()->title);
    }

    public function test_user_is_admin_method(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($superadmin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    public function test_user_full_name_accessor(): void
    {
        $user = User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
        ]);

        $this->assertEquals('John Doe', $user->full_name);
    }

    public function test_password_is_hashed_on_creation(): void
    {
        $user = User::factory()->create([
            'password' => 'plain-text-password',
        ]);

        $this->assertNotEquals('plain-text-password', $user->password);
        $this->assertTrue(\Hash::check('plain-text-password', $user->password));
    }

    public function test_user_can_be_soft_deleted(): void
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        $this->assertSoftDeleted('users', ['id' => $userId]);
    }

    public function test_user_email_must_be_unique(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        User::factory()->create(['email' => 'duplicate@example.com']);
        User::factory()->create(['email' => 'duplicate@example.com']);
    }

    public function test_user_role_defaults_to_user(): void
    {
        $street = Street::first();

        $user = User::create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'street_id' => $street->id,
        ]);

        $this->assertEquals('user', $user->role);
    }

    public function test_user_photo_url_accessor(): void
    {
        $user = User::factory()->create(['photo' => 'test-photo.jpg']);

        $expectedUrl = asset('uploads/test-photo.jpg');
        $this->assertEquals($expectedUrl, $user->photo_url);
    }

    public function test_user_photo_url_returns_default_when_no_photo(): void
    {
        $user = User::factory()->create(['photo' => null]);

        $expectedUrl = asset('images/default-avatar.png');
        $this->assertEquals($expectedUrl, $user->photo_url);
    }
}
