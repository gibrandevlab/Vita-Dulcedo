<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;
use Carbon\Carbon;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Data approved untuk simulasi transparansi
        $approvedDonations = [
            // Hari ini
            ['nama_lengkap' => 'Budi Santoso', 'jumlah_donasi' => 500000, 'pesan' => 'Semoga bermanfaat untuk adik-adik.', 'bukti_transfer' => 'bukti_transfer/sample.jpg', 'status' => 'approved', 'created_at' => $now->copy()->subHours(2)],
            ['nama_lengkap' => 'Siti Aminah', 'jumlah_donasi' => 250000, 'pesan' => null, 'bukti_transfer' => 'bukti_transfer/sample.jpg', 'status' => 'approved', 'created_at' => $now->copy()->subHours(5)],

            // Kemarin
            ['nama_lengkap' => 'Ahmad Fadhil', 'jumlah_donasi' => 1000000, 'pesan' => 'Untuk biaya sekolah anak-anak.', 'bukti_transfer' => 'bukti_transfer/sample.jpg', 'status' => 'approved', 'created_at' => $now->copy()->subDays(1)->setHour(10)],
            ['nama_lengkap' => 'Maria Cecilia', 'jumlah_donasi' => 750000, 'pesan' => 'Tuhan memberkati.', 'bukti_transfer' => 'bukti_transfer/sample.jpg', 'status' => 'approved', 'created_at' => $now->copy()->subDays(1)->setHour(14)],
            ['nama_lengkap' => 'Hendra Wijaya', 'jumlah_donasi' => 200000, 'pesan' => null, 'bukti_transfer' => 'bukti_transfer/sample.jpg', 'status' => 'approved', 'created_at' => $now->copy()->subDays(1)->setHour(18)],

            // 2 hari lalu
            ['nama_lengkap' => 'PT Teknologi Harapan', 'jumlah_donasi' => 5000000, 'pesan' => 'Donasi bulanan perusahaan kami.', 'bukti_transfer' => 'bukti_transfer/sample.jpg', 'status' => 'approved', 'created_at' => $now->copy()->subDays(2)->setHour(9)],

            // 3 hari lalu
            ['nama_lengkap' => 'Komunitas Berbagi Kasih', 'jumlah_donasi' => 2000000, 'pesan' => 'Hasil penggalangan dana komunitas.', 'bukti_transfer' => 'bukti_transfer/sample.jpg', 'status' => 'approved', 'created_at' => $now->copy()->subDays(3)->setHour(11)],
            ['nama_lengkap' => 'Dewi Lestari', 'jumlah_donasi' => 150000, 'pesan' => null, 'bukti_transfer' => 'bukti_transfer/sample.jpg', 'status' => 'approved', 'created_at' => $now->copy()->subDays(3)->setHour(16)],

            // 5 hari lalu
            ['nama_lengkap' => 'Agus Prasetyo', 'jumlah_donasi' => 300000, 'pesan' => 'Sedikit berbagi untuk senyum mereka.', 'bukti_transfer' => 'bukti_transfer/sample.jpg', 'status' => 'approved', 'created_at' => $now->copy()->subDays(5)->setHour(13)],
            ['nama_lengkap' => 'Yayasan Kasih Bangsa', 'jumlah_donasi' => 3000000, 'pesan' => 'Bantuan operasional.', 'bukti_transfer' => 'bukti_transfer/sample.jpg', 'status' => 'approved', 'created_at' => $now->copy()->subDays(5)->setHour(10)],

            // 7 hari lalu
            ['nama_lengkap' => 'Rina Susanti', 'jumlah_donasi' => 100000, 'pesan' => null, 'bukti_transfer' => 'bukti_transfer/sample.jpg', 'status' => 'approved', 'created_at' => $now->copy()->subDays(7)->setHour(8)],
            ['nama_lengkap' => 'Bambang Suryono', 'jumlah_donasi' => 2000000, 'pesan' => 'Untuk nutrisi anak-anak.', 'bukti_transfer' => 'bukti_transfer/sample.jpg', 'status' => 'approved', 'created_at' => $now->copy()->subDays(7)->setHour(15)],
        ];

        foreach ($approvedDonations as $donation) {
            // Generate kode donasi unik: 3 huruf besar + 10 angka
            $letters = '';
            for ($i = 0; $i < 3; $i++) {
                $letters .= chr(random_int(65, 90)); // A-Z
            }
            $digits = '';
            for ($i = 0; $i < 10; $i++) {
                $digits .= random_int(0, 9);
            }
            $donation['kode_donasi'] = $letters . $digits;
            $donation['nomor_telepon'] = $donation['nomor_telepon'] ?? '0812' . random_int(10000000, 99999999);

            Donation::create($donation);
        }

        // Data pending (belum diverifikasi — TIDAK masuk ke total)
        $pendingLetters = '';
        for ($i = 0; $i < 3; $i++) {
            $pendingLetters .= chr(random_int(65, 90));
        }
        $pendingDigits = '';
        for ($i = 0; $i < 10; $i++) {
            $pendingDigits .= random_int(0, 9);
        }

        Donation::create([
            'nama_lengkap' => 'Donatur Baru',
            'nomor_telepon' => '087812345678',
            'kode_donasi' => $pendingLetters . $pendingDigits,
            'jumlah_donasi' => 500000,
            'pesan' => 'Semoga diterima.',
            'bukti_transfer' => 'bukti_transfer/sample.jpg',
            'status' => 'pending',
            'created_at' => $now->copy()->subHours(1),
        ]);
    }
}
