<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AttachmentType;
use App\Enums\ExperienceType;
use App\Enums\MessageStatus;
use App\Enums\ProjectStatus;
use App\Enums\SkillType;
use App\Enums\UserRole;
use App\Models\Experience;
use App\Models\Message;
use App\Models\PageView;
use App\Models\Profile;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\SkillCategory;
use App\Models\SocialLink;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Awal & Editor
        $admin = User::updateOrCreate(
            ['email' => 'admin@rofiq.dev'],
            [
                'name' => 'M. Rofiq Aulia',
                'password' => Hash::make('password123'),
                'role' => UserRole::Admin,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'editor@rofiq.dev'],
            [
                'name' => 'Editor Portfolio',
                'password' => Hash::make('password123'),
                'role' => UserRole::Editor,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Profile Pemilik Tunggal
        $profile = Profile::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'full_name' => 'M. Rofiq Aulia',
                'headline' => 'System Analyst & Software Engineer',
                'tagline' => 'Fresh graduate Sarjana Terapan Teknik Informatika dengan spesialisasi : analisis sistem dan rekayasa perangkat lunak. Berfokus menerjemahkan alur bisnis manual menjadi spesifikasi sistem yang terstruktur berbasis digital, serta mengotomatisasi proses kerja demi efisiensi dan peningkatan berkelanjutan (continuous improvement). Terbuka di posisi IT Business Analyst, System Analyst, IT Staff Programming dan Analis Data berbasis AI.',
                'bio' => 'Memadukan kemampuan analisis kebutuhan bisnis (requirements analysis, perancangan UML & ERD) dengan kecakapan teknis berbasis PHP/Laravel dan MySQL. Berorientasi pada struktur data ternormalisasi, prinsip kode bersih (OOP & SOLID), serta komitmen tinggi terhadap continuous improvement dan efisiensi operasional sistem.',
                'email' => 'rofiq@rofiq.dev',
                'phone' => '+62 822-2803-5426',
                'location' => 'Malang, Indonesia',
                'quote_text' => 'Bermula Dengan Bismillah Berkahir Dengan Alhamdulillah.',
                'quote_author' => 'M. Rofiq Aulia',
                'is_open_to_work' => true,
            ]
        );

        // Social Links
        SocialLink::updateOrCreate(
            ['profile_id' => $profile->id, 'platform' => 'GitHub'],
            ['url' => 'https://github.com/RofiqAulia/', 'icon' => 'bi-github', 'sort_order' => 1, 'is_active' => true]
        );
        SocialLink::updateOrCreate(
            ['profile_id' => $profile->id, 'platform' => 'LinkedIn'],
            ['url' => 'https://www.linkedin.com/in/mrofiqaulia/', 'icon' => 'bi-linkedin', 'sort_order' => 2, 'is_active' => true]
        );
        SocialLink::updateOrCreate(
            ['profile_id' => $profile->id, 'platform' => 'Instagram'],
            ['url' => 'https://www.instagram.com/rofiqsyfhdytllah/', 'icon' => 'bi-instagram', 'sort_order' => 3, 'is_active' => true]
        );

        // 3. Technologies
        $this->call(TechnologySeeder::class);
        $techModels = Technology::all()->keyBy('slug');

        // 4. Skill Categories & Skills
        $hardCat = SkillCategory::firstOrCreate(
            ['type' => SkillType::Hard],
            ['name' => 'Hard Skills', 'sort_order' => 1]
        );
        $hardSkills = [
            ['name' => 'Laravel & PHP 8.3', 'level' => 95, 'level_label' => 'Expert', 'sort_order' => 1],
            ['name' => 'MySQL Database & Query Optimization', 'level' => 90, 'level_label' => 'Advanced', 'sort_order' => 2],
            ['name' => 'RESTful API & Clean Architecture', 'level' => 92, 'level_label' => 'Expert', 'sort_order' => 3],
            ['name' => 'Bootstrap 5 & Responsive UI', 'level' => 88, 'level_label' => 'Advanced', 'sort_order' => 4],
        ];
        foreach ($hardSkills as $skill) {
            $hardCat->skills()->firstOrCreate(['name' => $skill['name']], $skill);
        }

        $softCat = SkillCategory::firstOrCreate(
            ['type' => SkillType::Soft],
            ['name' => 'Soft Skills', 'sort_order' => 2]
        );
        $softSkills = [
            ['name' => 'Kepemimpinan Tim', 'level' => null, 'level_label' => 'Experienced', 'sort_order' => 1],
            ['name' => 'Komunikasi & Kolaborasi', 'level' => null, 'level_label' => 'Proaktif', 'sort_order' => 2],
            ['name' => 'Penyelesaian Masalah Kritis', 'level' => null, 'level_label' => 'Solutif', 'sort_order' => 3],
        ];
        foreach ($softSkills as $skill) {
            $softCat->skills()->firstOrCreate(['name' => $skill['name']], $skill);
        }

        $analysisCat = SkillCategory::firstOrCreate(
            ['type' => SkillType::Analysis],
            ['name' => 'Analisis & Desain Sistem', 'sort_order' => 3]
        );
        $analysisSkills = [
            ['name' => 'Desain ERD & Normalisasi Database', 'level' => null, 'level_label' => 'Mahir', 'sort_order' => 1],
            ['name' => 'UML, Sequence & Architecture Flowchart', 'level' => null, 'level_label' => 'Terstruktur', 'sort_order' => 2],
            ['name' => 'Software Design Patterns (OOP / SOLID)', 'level' => null, 'level_label' => 'Best Practice', 'sort_order' => 3],
        ];
        foreach ($analysisSkills as $skill) {
            $analysisCat->skills()->firstOrCreate(['name' => $skill['name']], $skill);
        }

        // 5. Projects
        $proj1 = Project::withTrashed()->firstOrNew(['slug' => 'sistem-informasi-manajemen-logistik-enterprise']);
        if ($proj1->trashed()) {
            $proj1->restore();
        }
        $proj1->fill([
            'title' => 'Sistem Informasi Manajemen Logistik Enterprise',
            'summary' => 'Platform terintegrasi pelacakan armada armada pengiriman dan inventaris gudang berbasis IoT dan background jobs.',
            'description' => 'Aplikasi web enterprise yang menangani lebih dari 10.000 transaksi harian dengan arsitektur queue Redis, pemantauan status real-time, laporan PDF otomatis, dan otorisasi multi-level yang ketat.',
            'role' => 'Lead Software Engineer',
            'demo_url' => 'https://demo.rofiq.dev/logistik',
            'repo_url' => 'https://github.com/rofiq/logistics-erp',
            'started_at' => now()->subMonths(8),
            'finished_at' => now()->subMonths(2),
            'status' => ProjectStatus::Published,
            'is_featured' => true,
            'sort_order' => 1,
            'published_at' => now()->subMonths(2),
        ]);
        $proj1->save();

        if (isset($techModels['laravel'], $techModels['mysql'], $techModels['redis'], $techModels['bootstrap-5'])) {
            $proj1->technologies()->sync([
                $techModels['laravel']->id,
                $techModels['mysql']->id,
                $techModels['redis']->id,
                $techModels['bootstrap-5']->id,
            ]);
        }

        $proj2 = Project::withTrashed()->firstOrNew(['slug' => 'platform-e-commerce-b2b-marketplace']);
        if ($proj2->trashed()) {
            $proj2->restore();
        }
        $proj2->fill([
            'title' => 'Platform E-Commerce B2B Marketplace',
            'summary' => 'Portal transaksi grosir multitenant dilengkapi verifikasi legalitas faktur dan integrasi payment gateway perbankan.',
            'description' => 'Solusi e-commerce B2B lengkap dengan sistem negosiasi harga dinamis, approval purchase order, dan dashboard analitik keuangan berkala.',
            'role' => 'Full-Stack Developer',
            'demo_url' => 'https://b2b.rofiq.dev',
            'repo_url' => 'https://github.com/rofiq/b2b-platform',
            'started_at' => now()->subMonths(4),
            'finished_at' => now()->subMonth(),
            'status' => ProjectStatus::Published,
            'is_featured' => true,
            'sort_order' => 2,
            'published_at' => now()->subMonth(),
        ]);
        $proj2->save();

        if (isset($techModels['laravel'], $techModels['mysql'], $techModels['bootstrap-5'])) {
            $proj2->technologies()->sync([
                $techModels['laravel']->id,
                $techModels['mysql']->id,
                $techModels['bootstrap-5']->id,
            ]);
        }

        // 6. Experiences (Internship & Organization)
        $expIntern = Experience::firstOrCreate(
            ['position' => 'Backend Engineer Intern', 'organization_name' => 'PT Teknologi Inovasi Nusantara'],
            [
                'type' => ExperienceType::Internship,
                'location' => 'Jakarta Selatan',
                'start_date' => now()->subMonths(14)->toDateString(),
                'end_date' => now()->subMonths(8)->toDateString(),
                'is_current' => false,
                'summary' => 'Mengembangkan REST API mikroservis dan optimasi performa query database MySQL hingga 40%.',
                'story' => 'Selama magang 6 bulan, saya bertanggung jawab merancang endpoint API untuk modul autentikasi dan integrasi pembayaran. Mengurangi response time rata-rata dari 250ms menjadi 85ms melalui indexing terarah.',
                'status' => ProjectStatus::Published,
                'sort_order' => 1,
            ]
        );
        $expIntern->highlights()->firstOrCreate(['content' => 'Meningkatkan kecepatan query laporan database sebesar 40%']);
        $expIntern->highlights()->firstOrCreate(['content' => 'Membangun 15+ endpoint REST API terdokumentasi OpenAPI']);
        $expIntern->technologies()->sync([$techModels['laravel']->id, $techModels['mysql']->id]);
        $expIntern->attachments()->firstOrCreate(
            ['title' => 'Sertifikat Magang Terbaik'],
            [
                'type' => AttachmentType::Link,
                'url' => 'https://drive.google.com/sample-cert',
                'is_visible' => true,
                'sort_order' => 1,
            ]
        );

        $expOrg = Experience::firstOrCreate(
            ['position' => 'Kepala Divisi Riset & Teknologi', 'organization_name' => 'Himpunan Mahasiswa Informatika'],
            [
                'type' => ExperienceType::Organization,
                'location' => 'Kampus Utama',
                'start_date' => now()->subYears(2)->toDateString(),
                'end_date' => now()->subYear()->toDateString(),
                'is_current' => false,
                'summary' => 'Memimpin 12 anggota pengembang dalam merilis portal akademik kampus dan bootcamp coding mahasiswa.',
                'story' => 'Menginisiasi pelatihan web development tahunan yang diikuti 200+ peserta serta memimpin migrasi infrastruktur server aplikasi himpunan ke environment cloud.',
                'status' => ProjectStatus::Published,
                'sort_order' => 2,
            ]
        );
        $expOrg->highlights()->firstOrCreate(['content' => 'Melatih 200+ mahasiswa dalam kurikulum Laravel & Clean Code']);
        $expOrg->highlights()->firstOrCreate(['content' => 'Merilis website resmi himpunan dengan 10.000+ kunjungan tahunan']);
        $expOrg->technologies()->sync([$techModels['laravel']->id, $techModels['bootstrap-5']->id]);

        // 7. Site Settings
        $settings = [
            'site_title' => 'Portofolio Profesional — M. Rofiq Aulia',
            'site_description' => 'Website portofolio individu senior Laravel developer yang menyajikan proyek unggulan, keahlian, dan riwayat pengalaman.',
            'meta_keywords' => 'laravel developer, software engineer, web developer indonesia, backend engineer',
            'author_name' => 'M. Rofiq Aulia',
            'contact_email' => 'rofiq@rofiq.dev',
            'contact_phone' => '+62 812-3456-7890',
            'footer_copyright' => '© 2026 M. Rofiq Aulia. All rights reserved.',
        ];
        foreach ($settings as $key => $val) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $val, 'group' => 'general']);
        }

        // 8. Messages & PageViews Sample
        Message::firstOrCreate(
            ['email' => 'klien@startup.co.id'],
            [
                'name' => 'Budi Santoso',
                'subject' => 'Tawaran Proyek Pengembangan Web Enterprise',
                'body' => 'Halo Mas Rofiq, kami sangat tertarik dengan portofolio Anda dan ingin mendiskusikan kerja sama proyek pembuatan sistem internal perusahaan.',
                'status' => MessageStatus::New,
                'ip_address' => '127.0.0.1',
            ]
        );

        PageView::create([
            'page' => '/',
            'visitor_hash' => hash('sha256', '127.0.0.1|agent|sample'),
            'referrer' => 'https://google.com',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'created_at' => now(),
        ]);

        // 9. Documentations & Blueprints
        $this->call(DocumentationSeeder::class);
    }
}
