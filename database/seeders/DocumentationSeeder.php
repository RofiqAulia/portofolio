<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\DocumentationType;
use App\Models\Documentation;
use App\Models\Project;
use Illuminate\Database\Seeder;

class DocumentationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proj1 = Project::where('slug', 'sistem-informasi-manajemen-logistik-enterprise')->first();
        $proj2 = Project::where('slug', 'platform-e-commerce-b2b-marketplace')->first();

        // 1. ERD & Database Blueprint (Terkait Proyek 1)
        $doc1 = Documentation::withTrashed()->firstOrNew(['slug' => 'erd-database-sistem-informasi-manajemen-logistik-enterprise']);
        if ($doc1->trashed()) {
            $doc1->restore();
        }
        $proj1Title = $proj1?->title ?? 'Sistem Informasi Manajemen Logistik Enterprise';
        $doc1->fill([
            'title' => "Desain ERD & Skema Database — {$proj1Title}",
            'category' => 'ERD & Database',
            'summary' => "Skema arsitektur entitas relasi (ERD) 3NF lengkap dengan indeks teroptimasi, foreign keys cascade, dan struktur audit log transaksi gudang untuk proyek {$proj1Title}.",
            'description' => 'Dokumentasi arsitektur database relasional MySQL yang dirancang memenuhi kaidah Third Normal Form (3NF). Meliputi tabel master barang, pergerakan stok real-time (inbound/outbound), relasi multi-gudang, dan integrasi penomoran QR code unik.',
            'type' => DocumentationType::Image,
            'file_path' => 'documentations/files/erd-smart-warehouse.png',
            'preview_image_path' => null,
            'external_url' => 'https://whimsical.com',
            'project_id' => $proj1?->id,
            'is_featured' => true,
            'is_published' => true,
            'sort_order' => 1,
            'published_at' => now()->subMonths(2),
        ]);
        $doc1->save();

        // Hapus record lama jika menggunakan slug lama
        Documentation::where('slug', 'erd-database-smart-warehouse-system')->where('id', '!=', $doc1->id)->delete();

        // 2. UML & Flowchart Blueprint (Terkait Proyek 1)
        $doc2 = Documentation::withTrashed()->firstOrNew(['slug' => 'uml-flowchart-arsitektur-autentikasi-live-camera-qr']);
        if ($doc2->trashed()) {
            $doc2->restore();
        }
        $doc2->fill([
            'title' => "Diagram UML Sequence & Autentikasi QR — {$proj1Title}",
            'category' => 'UML & Flowchart',
            'summary' => "Diagram urutan (Sequence Diagram) dan Flowchart alur pemindaian QR code via kamera browser, verifikasi token JWT, hingga pembaruan stok otomatis untuk proyek {$proj1Title}.",
            'description' => 'Diagram detail yang memetakan siklus interaksi antara frontend kamera pengguna, enkripsi token QR dinamis, middleware validasi backend Laravel, dan atomic transaction lock di database.',
            'type' => DocumentationType::Image,
            'file_path' => 'documentations/files/uml-flowchart-qr.png',
            'preview_image_path' => null,
            'external_url' => 'https://miro.com',
            'project_id' => $proj1?->id,
            'is_featured' => true,
            'is_published' => true,
            'sort_order' => 2,
            'published_at' => now()->subMonth(),
        ]);
        $doc2->save();

        // 3. Arsitektur Sistem Clean Code & SOLID (Terkait Proyek 2)
        $doc3 = Documentation::withTrashed()->firstOrNew(['slug' => 'arsitektur-sistem-clean-architecture-solid-laravel']);
        if ($doc3->trashed()) {
            $doc3->restore();
        }
        $proj2Title = $proj2?->title ?? 'Platform E-Commerce B2B Marketplace';
        $doc3->fill([
            'title' => "Blueprint Arsitektur Clean Code & SOLID — {$proj2Title}",
            'category' => 'Arsitektur Sistem',
            'summary' => "Dokumen blueprint arsitektur modular Service-Repository pattern, DTO (Data Transfer Objects), Action classes, dan decoupled domain logic untuk proyek {$proj2Title}.",
            'description' => 'Blueprint panduan arsitektur rekayasa perangkat lunak untuk menjaga kode tetap modular, mudah diuji (testable), dan bebas dari dependensi yang berbelit-belit.',
            'type' => DocumentationType::Document,
            'file_path' => 'documentations/files/clean-architecture-guide.pdf',
            'preview_image_path' => null,
            'external_url' => 'https://github.com',
            'project_id' => $proj2?->id,
            'is_featured' => true,
            'is_published' => true,
            'sort_order' => 3,
            'published_at' => now()->subWeeks(3),
        ]);
        $doc3->save();
    }
}
