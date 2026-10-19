<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\SocialLink;
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
            'phone' => '+62 822-2803-5426',
            'is_open_to_work' => true,
        ];

        if ($profile) {
            $profile->update($data);
        } else {
            $profile = Profile::create(array_merge($data, [
                'user_id' => $userId,
                'email' => 'rofiq@rofiq.dev',
            ]));
        }

        $socialLinks = [
            [
                'platform' => 'GitHub',
                'url' => 'https://github.com/RofiqAulia/',
                'icon' => 'bi-github',
                'sort_order' => 1,
            ],
            [
                'platform' => 'LinkedIn',
                'url' => 'https://www.linkedin.com/in/mrofiqaulia/',
                'icon' => 'bi-linkedin',
                'sort_order' => 2,
            ],
            [
                'platform' => 'Instagram',
                'url' => 'https://www.instagram.com/rofiqsyfhdytllah/',
                'icon' => 'bi-instagram',
                'sort_order' => 3,
            ],
        ];

        foreach ($socialLinks as $link) {
            SocialLink::updateOrCreate(
                [
                    'profile_id' => $profile->id,
                    'platform' => $link['platform'],
                ],
                [
                    'url' => $link['url'],
                    'icon' => $link['icon'],
                    'sort_order' => $link['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
