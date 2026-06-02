<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use App\Models\VisitRequest;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        $userIds = $this->seedUsers($faker, $monthStart, $monthEnd);
        $campaignIds = $this->seedCampaigns($faker, $monthStart, $monthEnd);
        $this->seedDonations($faker, $userIds, $campaignIds, $monthStart, $monthEnd);
        $this->seedVisitRequests($faker, $userIds, $monthStart, $monthEnd);
    }

    protected function seedUsers($faker, Carbon $monthStart, Carbon $monthEnd): array
    {
        $userIds = [];

        for ($i = 0; $i < 100; $i++) {
            $user = User::create([
                'name' => $faker->name(),
                'login' => '08' . $faker->numerify('##########'),
                'email' => $faker->optional(0.85)->safeEmail(),
                'password' => 'password123',
                'pin' => $faker->numerify('#####'),
                'role' => 'user',
                'position' => $faker->optional(0.25)->jobTitle(),
                'organization' => $faker->company(),
                'address' => $faker->streetAddress() . ', ' . $faker->city(),
                'created_at' => $faker->dateTimeBetween($monthStart, $monthEnd),
                'updated_at' => $faker->dateTimeBetween($monthStart, $monthEnd),
            ]);

            $userIds[] = $user->id;
        }

        return $userIds;
    }

    protected function seedCampaigns($faker, Carbon $monthStart, Carbon $monthEnd): array
    {
        $campaignIds = [];

        for ($i = 0; $i < 100; $i++) {
            $target = $faker->numberBetween(500000, 5000000);
            $collected = $faker->numberBetween(0, $target);
            $status = $collected >= $target ? 'completed' : 'active';

            $campaign = Campaign::create([
                'title' => ucfirst($faker->words(4, true)),
                'description' => $faker->paragraphs(2, true),
                'target_amount' => $target,
                'collected_amount' => $collected,
                'deadline' => $faker->dateTimeBetween($monthEnd, $monthEnd->copy()->addMonths(2))->format('Y-m-d'),
                'status' => $status,
                'image' => $faker->optional(0.6)->imageUrl(640, 360, 'charity', true, 'Camp'),
                'created_at' => $faker->dateTimeBetween($monthStart, $monthEnd),
                'updated_at' => $faker->dateTimeBetween($monthStart, $monthEnd),
            ]);

            $campaignIds[] = $campaign->id;
        }

        return $campaignIds;
    }

    protected function seedDonations($faker, array $userIds, array $campaignIds, Carbon $monthStart, Carbon $monthEnd): void
    {
        $uniqueCodes = [];

        for ($i = 0; $i < 100; $i++) {
            $createdAt = $faker->dateTimeBetween($monthStart, $monthEnd);
            Donation::create([
                'user_id' => $faker->randomElement($userIds),
                'campaign_id' => $faker->optional(0.75)->randomElement($campaignIds),
                'nama_lengkap' => $faker->name(),
                'nomor_telepon' => '08' . $faker->numerify('##########'),
                'kode_donasi' => $this->uniqueCode($uniqueCodes),
                'jumlah_donasi' => $faker->numberBetween(50000, 2000000),
                'pesan' => $faker->optional(0.5)->sentence(6),
                'bukti_transfer' => 'bukti_transfer/sample.jpg',
                'status' => $faker->randomElement(['approved', 'pending', 'rejected']),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }

    protected function seedVisitRequests($faker, array $userIds, Carbon $monthStart, Carbon $monthEnd): void
    {
        $uniqueCodes = [];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        for ($i = 0; $i < 100; $i++) {
            $visitDate = $faker->dateTimeBetween($monthStart, $monthEnd);
            $startHour = $faker->numberBetween(8, 15);
            $duration = $faker->numberBetween(1, 3);
            $startTime = Carbon::createFromTime($startHour, 0, 0)->format('H:i:s');
            $endTime = Carbon::createFromTime($startHour + $duration, 0, 0)->format('H:i:s');
            $isRoutine = $faker->boolean(20);
            $routineDays = null;
            $routineEndDate = null;

            if ($isRoutine) {
                $picked = $faker->randomElements($days, $faker->numberBetween(2, 4));
                $routineDays = implode(', ', $picked);
                $routineEndDate = Carbon::instance($visitDate)->copy()->addDays($faker->numberBetween(7, 90))->format('Y-m-d');
            }

            VisitRequest::create([
                'user_id' => $faker->randomElement($userIds),
                'kode_kunjungan' => $this->uniqueCode($uniqueCodes),
                'nama_pengunjung' => $faker->name(),
                'jumlah_pengunjung' => $faker->numberBetween(1, 6),
                'tanggal_kunjungan' => $visitDate->format('Y-m-d'),
                'jam_mulai' => $startTime,
                'jam_selesai' => $endTime,
                'tujuan_kunjungan' => $faker->sentence(8),
                'status' => $faker->randomElement(['pending', 'approved', 'rejected']),
                'catatan_admin' => $faker->optional(0.4)->sentence(8),
                'is_routine' => $isRoutine,
                'routine_days' => $routineDays,
                'routine_end_date' => $routineEndDate,
                'created_at' => $visitDate,
                'updated_at' => $visitDate,
            ]);
        }
    }

    protected function uniqueCode(array &$used): string
    {
        do {
            $letters = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3));
            $digits = '';
            for ($j = 0; $j < 10; $j++) {
                $digits .= random_int(0, 9);
            }
            $code = $letters . $digits;
        } while (in_array($code, $used, true));

        $used[] = $code;

        return $code;
    }
}
