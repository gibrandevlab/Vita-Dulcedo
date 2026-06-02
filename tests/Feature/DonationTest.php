<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DonationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the donation page loads successfully.
     */
    public function test_donation_page_renders(): void
    {
        $response = $this->get('/donasi');

        $response->assertStatus(200);
        $response->assertSee('Donasi Transparan');
    }

    /**
     * Test a user or guest can submit a donation confirmation.
     */
    public function test_can_submit_donation_confirmation(): void
    {
        Storage::fake('public');

        $payload = [
            'jumlah_donasi' => 50000,
            'nama_lengkap' => 'Donatur Test',
            'nomor_telepon' => '08123456789',
            'pesan' => 'Semoga berkah',
            'bukti_transfer' => UploadedFile::fake()->image('bukti.jpg'),
        ];

        $response = $this->post('/donasi', $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $response->assertSessionHas('kode_donasi');
        $response->assertSessionHas('wa_url');

        // Check database
        $this->assertDatabaseHas('donations', [
            'nama_lengkap' => 'Donatur Test',
            'jumlah_donasi' => 50000,
            'status' => 'pending',
        ]);

        $donation = Donation::first();
        $this->assertNotNull($donation->kode_donasi);
        // Verify format: 3 letters + 10 digits
        $this->assertMatchesRegularExpression('/^[A-Z]{3}\d{10}$/', $donation->kode_donasi);

        // Check if proof was stored
        Storage::disk('public')->assertExists($donation->bukti_transfer);
    }

    /**
     * Test that donation totals only calculate approved donations.
     */
    public function test_totals_only_count_approved_donations(): void
    {
        Donation::create([
            'nama_lengkap' => 'Approved Donor',
            'nomor_telepon' => '08111111111',
            'jumlah_donasi' => 150000,
            'kode_donasi' => 'ABC1234567890',
            'bukti_transfer' => 'bukti_transfer/test.jpg',
            'status' => 'approved',
        ]);

        Donation::create([
            'nama_lengkap' => 'Pending Donor',
            'nomor_telepon' => '08222222222',
            'jumlah_donasi' => 200000,
            'kode_donasi' => 'XYZ0987654321',
            'bukti_transfer' => 'bukti_transfer/test2.jpg',
            'status' => 'pending',
        ]);

        $response = $this->get('/donasi');

        $response->assertStatus(200);
        // Only 150,000 should be summed, not 350,000
        $response->assertSee('Rp 150.000');
        $response->assertDontSee('Rp 350.000');
    }

    /**
     * Test admin routes authorization.
     */
    public function test_non_admin_cannot_access_admin_donation_panel(): void
    {
        $user = User::factory()->create([
            'login' => '08123456789',
            'role' => 'user'
        ]);

        $response = $this->actingAs($user)->get('/admin/donasi');
        $response->assertStatus(403);
    }

    /**
     * Test admin can view and approve donations.
     */
    public function test_admin_can_approve_donation(): void
    {
        $admin = User::factory()->create([
            'login' => '081315672350',
            'role' => 'admin'
        ]);
        
        $donation = Donation::create([
            'nama_lengkap' => 'Test Donor',
            'nomor_telepon' => '08123456789',
            'jumlah_donasi' => 100000,
            'kode_donasi' => 'ABC1234567890',
            'bukti_transfer' => 'bukti_transfer/test.png',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post("/admin/donasi/{$donation->id}/approve");

        $response->assertRedirect();
        $this->assertEquals('approved', $donation->fresh()->status);
    }
}
