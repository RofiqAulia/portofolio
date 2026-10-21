<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Certification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'title' => 'Sistem Manajemen Beasiswa SOVIA',
                'category' => 'Hak Cipta (HKI)',
                'issuer' => 'DJKI Kemenkumham RI',
                'issued_year' => '2025',
                'status_label' => 'Terdaftar Resmi',
                'logo_path' => null,
                'description' => 'Surat Pencatatan Hak Cipta resmi atas perancangan arsitektur dan sistem manajemen beasiswa enterprise terintegrasi untuk otomatisasi pelaporan korporat.',
                'action_url' => '#proyek',
                'action_label' => 'Lihat Sistem',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Web Developer - Program BUMN Magenta',
                'category' => 'Sertifikasi Magang',
                'issuer' => 'Forum Human Capital Indonesia & Holding Manufaktur',
                'issued_year' => '2025 - 2026',
                'status_label' => 'Lulus Terverifikasi',
                'logo_path' => 'experiences/covers/6NglBWRy61Oo7R0yOEkPcnoPvvRckphUopQA87wP.png',
                'description' => 'Sertifikasi kelulusan magang industri berskala enterprise atas keterlibatan aktif pengembangan 4 platform web dinamis dan digitalisasi data operasional.',
                'action_url' => '#magang',
                'action_label' => 'Detail Magang',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Best Performance Backend Engineer',
                'category' => 'Penghargaan Kinerja',
                'issuer' => 'PT Teknologi Inovasi Nusantara',
                'issued_year' => '2026',
                'status_label' => 'Best Intern',
                'logo_path' => null,
                'description' => 'Penghargaan kinerja terbaik atas rekayasa REST API mikroservis dan optimasi query database MySQL yang sukses memangkas response time hingga 40%.',
                'action_url' => '#magang',
                'action_label' => 'Lihat Jejak',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Sekretaris Umum & Kadiv Riset Teknologi',
                'category' => 'Kepemimpinan',
                'issuer' => 'UKM Olahraga & Himpunan Mahasiswa Informatika',
                'issued_year' => '2022 - 2024',
                'status_label' => '2 Periode',
                'logo_path' => 'experiences/covers/QuTbrO4b43e4b5hJrL7fSfQeDVSzpn6au56nOK43.png',
                'description' => 'Sertifikasi kepengurusan resmi dalam tata kelola administrasi organisasi, kepemimpinan divisi riset teknologi, serta manajemen inisiatif digital mahasiswa.',
                'action_url' => '#organisasi',
                'action_label' => 'Kepengurusan',
                'is_featured' => false,
                'sort_order' => 4,
            ],
            [
                'title' => 'Sarjana Terapan Teknik Informatika',
                'category' => 'Predikat Akademik',
                'issuer' => 'Politeknik Negeri Malang',
                'issued_year' => 'IPK 3.68/4.00',
                'status_label' => 'Cumlaude',
                'logo_path' => 'experiences/covers/QuTbrO4b43e4b5hJrL7fSfQeDVSzpn6au56nOK43.png',
                'description' => 'Kelulusan tepat waktu dengan predikat Sangat Memuaskan/Cumlaude, spesialisasi Rekayasa Perangkat Lunak, Analisis Sistem, dan Continuous Improvement.',
                'action_url' => '#tentang',
                'action_label' => 'Profil Lulusan',
                'is_featured' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'Arsip Dokumentasi & Diagram Arsitektur',
                'category' => 'Blueprint Kredensial',
                'issuer' => 'System Architecture Lab',
                'issued_year' => 'Live Lab',
                'status_label' => 'Blueprint Teruji',
                'logo_path' => null,
                'description' => 'Dokumentasi visual nyata mencakup desain database ERD normalisasi 3NF, flowchart proses bisnis korporat, dan sequence diagram autentikasi sistem.',
                'action_url' => '/dokumentasi',
                'action_label' => 'Buka Arsip',
                'is_featured' => false,
                'sort_order' => 6,
            ],
        ];

        foreach ($items as $data) {
            $slug = Str::slug($data['title']);
            Certification::updateOrCreate(
                ['slug' => $slug],
                array_merge($data, [
                    'slug' => $slug,
                    'images' => [],
                    'files' => [],
                    'is_published' => true,
                    'published_at' => now(),
                ])
            );
        }
    }
}
