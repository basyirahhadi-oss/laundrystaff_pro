<?php

namespace Tests\Feature;

use App\Models\Leave;
use App\Models\User;
use App\Services\FileEncryptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SecureFileTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_owner_can_view_encrypted_attachment(): void
    {
        $staffUser = User::factory()->create(['role' => 'staff']);
        $adminUser = User::factory()->create(['role' => 'admin']);
        $otherStaff = User::factory()->create(['role' => 'staff']);

        $service = app(FileEncryptionService::class);
        $file = UploadedFile::fake()->createWithContent('medical_certificate.pdf', '%PDF-1.4 Fake Medical Certificate');
        $enc = $service->encryptAndStore($file);

        $leave = Leave::create([
            'user_id'         => $staffUser->id,
            'leave_type'      => 'mc',
            'start_date'      => now()->toDateString(),
            'end_date'        => now()->addDay()->toDateString(),
            'reason'          => 'Flu symptoms',
            'attachment'      => $enc['path'],
            'attachment_name' => $enc['original_name'],
            'attachment_mime' => $enc['mime_type'],
            'status'          => 'pending',
        ]);

        // 1. Staff owner can view
        $response = $this->actingAs($staffUser)->get(route('secure.leave.attachment', $leave));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');

        // 2. Admin can view
        $responseAdmin = $this->actingAs($adminUser)->get(route('secure.leave.attachment', $leave));
        $responseAdmin->assertStatus(200);

        // 3. Other staff cannot view (403 Forbidden - RBAC Protection)
        $responseOther = $this->actingAs($otherStaff)->get(route('secure.leave.attachment', $leave));
        $responseOther->assertStatus(403);

        // 4. Unauthenticated guest cannot view (Redirect to login)
        auth()->logout();
        $responseGuest = $this->get(route('secure.leave.attachment', $leave));
        $responseGuest->assertRedirect(route('login'));
    }
}
