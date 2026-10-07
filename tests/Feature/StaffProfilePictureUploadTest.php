<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StaffProfilePictureUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_profile_picture_when_creating_staff(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // 1x1 transparent PNG
        $fakeImage = UploadedFile::fake()->create('avatar.png', 10, 'image/png');

        $response = $this->actingAs($admin)->post(route('staff.store'), [
            'staff_id' => 'STF999',
            'full_name' => 'Ahmad Test',
            'position' => 'Cashier',
            'phone_number' => '0123456789',
            'salary_rate' => 1500.00,
            'profile_picture' => $fakeImage,
        ]);

        $response->assertRedirect(route('staff.index'));

        $staff = DB::table('staff')->where('staff_id', 'STF999')->first();
        $this->assertNotNull($staff);
        $this->assertNotNull($staff->profile_picture);
        $this->assertStringStartsWith('data:image/png;base64,', $staff->profile_picture);
    }

    public function test_admin_can_upload_profile_picture_when_updating_staff(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        DB::table('staff')->insert([
            'staff_id' => 'STF888',
            'full_name' => 'Siti Test',
            'position' => 'Supervisor',
            'phone_number' => '0198765432',
            'salary_rate' => 2000.00,
            'profile_picture' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $newImage = UploadedFile::fake()->create('new_avatar.jpg', 15, 'image/jpeg');

        $response = $this->actingAs($admin)->put(route('staff.update', 'STF888'), [
            'full_name' => 'Siti Test Updated',
            'position' => 'Senior Supervisor',
            'phone_number' => '0198765432',
            'salary_rate' => 2200.00,
            'profile_picture' => $newImage,
        ]);

        $response->assertRedirect(route('staff.index'));

        $staff = DB::table('staff')->where('staff_id', 'STF888')->first();
        $this->assertEquals('Siti Test Updated', $staff->full_name);
        $this->assertNotNull($staff->profile_picture);
        $this->assertStringStartsWith('data:image/jpeg;base64,', $staff->profile_picture);
    }

    public function test_edit_page_renders_with_streamed_avatar_url(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

        DB::table('staff')->insert([
            'staff_id' => 'STF777',
            'full_name' => 'Abu Test',
            'position' => 'Operator',
            'phone_number' => '0112233445',
            'salary_rate' => 1600.00,
            'profile_picture' => $base64,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('staff.edit', 'STF777'));

        $response->assertStatus(200);
        $response->assertSee(route('staff.avatar', 'STF777'), false);
    }

    public function test_avatar_endpoint_streams_profile_picture(): void
    {
        $base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

        DB::table('staff')->insert([
            'staff_id' => 'STF777',
            'full_name' => 'Abu Test',
            'position' => 'Operator',
            'phone_number' => '0112233445',
            'salary_rate' => 1600.00,
            'profile_picture' => $base64,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(route('staff.avatar', 'STF777'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }
}
