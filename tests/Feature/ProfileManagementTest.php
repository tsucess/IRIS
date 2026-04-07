<?php

namespace Tests\Feature;

use App\Models\Street;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileManagementTest extends TestCase
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

    public function test_user_can_view_profile_edit_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('profile.edit');
        $response->assertViewHas('user', $user);
    }

    public function test_user_can_update_profile_information(): void
    {
        $user = User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@example.com',
        ]);

        $updateData = [
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'email' => 'jane@example.com',
        ];

        $response = $this->actingAs($user)->patch(route('profile.update'), $updateData);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('status', 'profile-updated');

        $user->refresh();
        $this->assertEquals('Jane', $user->firstname);
        $this->assertEquals('Smith', $user->lastname);
        $this->assertEquals('jane@example.com', $user->email);
    }

    public function test_user_can_upload_profile_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $photo = UploadedFile::fake()->image('profile.jpg', 500, 500);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'photo' => $photo,
        ]);

        $response->assertRedirect(route('profile.edit'));

        $user->refresh();
        $this->assertNotNull($user->photo);
    }

    public function test_profile_photo_must_be_valid_image(): void
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 1000);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'photo' => $file,
        ]);

        $response->assertSessionHasErrors('photo');
    }

    public function test_profile_photo_size_must_not_exceed_2mb(): void
    {
        $user = User::factory()->create();
        $photo = UploadedFile::fake()->image('large.jpg')->size(3000); // 3MB

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'photo' => $photo,
        ]);

        $response->assertSessionHasErrors('photo');
    }

    public function test_email_verification_status_is_reset_when_email_changes(): void
    {
        $user = User::factory()->create([
            'email' => 'old@example.com',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => 'new@example.com',
        ]);

        $user->refresh();
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_unchanged_when_email_unchanged(): void
    {
        $verifiedAt = now();
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'email_verified_at' => $verifiedAt,
        ]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'firstname' => 'Updated Name',
            'lastname' => $user->lastname,
            'email' => 'test@example.com',
        ]);

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_user_can_view_id_card(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.idcard'));

        $response->assertStatus(200);
        $response->assertViewIs('profile.idcard');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('qrCode');
    }

    public function test_user_can_download_id_card_pdf(): void
    {
        $user = User::factory()->create(['id_number' => 'COMM-12345']);

        $response = $this->actingAs($user)->get(route('profile.idcard.download'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_id_number_is_auto_generated_if_not_exists(): void
    {
        $user = User::factory()->create(['id_number' => null]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
        ]);

        $user->refresh();
        $this->assertNotNull($user->id_number);
        $this->assertStringStartsWith('COMM-', $user->id_number);
    }
}
