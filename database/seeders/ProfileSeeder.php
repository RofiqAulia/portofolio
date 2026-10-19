<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $userId = $user?->id ?? 1;

        $profile = Profile::first();

        $data = [
            'full_name' => 'M. Rofiq Aulia',
            'headline' => 'System Analyst & Software Engineer',
            'tagline' => 'Fresh graduate Sarjana Terapan Teknik Informatika dengan spesialisasi : analisis sistem dan rekayasa perangkat lunak. Berfokus menerjemahkan alur bisnis manual menjadi spesifikasi sistem yang terstruktur berbasis digital, serta mengotomatisasi proses kerja demi efisiensi dan peningkatan berkelanjutan (continuous improvement). Terbuka di posisi IT Business Analyst, System Analyst, IT Staff Programming dan Analis Data berbasis AI.',
            'bio' => 'Memadukan kemampuan analisis kebutuhan bisnis (requirements analysis, perancangan UML & ERD) dengan kecakapan teknis berbasis PHP/Laravel dan MySQL. Berorientasi pada struktur data ternormalisasi, prinsip kode bersih (OOP & SOLID), serta komitmen tinggi terhadap continuous improvement dan efisiensi operasional sistem.',
            'location' => 'Malang, Indonesia',
            'is_open_to_work' => true,
        ];

        if ($profile) {
            $profile->update($data);
        } else {
            Profile::create(array_merge($data, [
                'user_id' => $userId,
                'email' => 'rofiq@rofiq.dev',
            ]));
        }
    }
}
