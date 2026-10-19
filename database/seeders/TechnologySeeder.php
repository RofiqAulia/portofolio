<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Seeder;

class TechnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $techs = [
            'Laravel' => ['slug' => 'laravel', 'icon_path' => 'logo/laravel-logo.jpg'],
            'PHP' => ['slug' => 'php', 'icon_path' => 'logo/php-logo.png'],
            'MySQL' => ['slug' => 'mysql', 'icon_path' => 'logo/mysql-logo.png'],
            'Bootstrap 5' => ['slug' => 'bootstrap-5', 'icon_path' => 'logo/bootstrap-logo.jpg'],
            'Tailwind CSS' => ['slug' => 'tailwind-css', 'icon_path' => 'logo/tailwind-logo.jpg'],
            'Docker' => ['slug' => 'docker', 'icon_path' => 'logo/docker-logo.jpg'],
            'Git' => ['slug' => 'git', 'icon_path' => 'logo/git-logo.png'],
            'Notion' => ['slug' => 'notion', 'icon_path' => 'logo/notion-logo.png'],
            'Canva' => ['slug' => 'canva', 'icon_path' => 'logo/canva-logo.png'],
            'Draw.io' => ['slug' => 'draw-io', 'icon_path' => 'logo/draw-io-logo.png'],
            'Microsoft Excel' => ['slug' => 'excel', 'icon_path' => 'logo/excel-logo.jpg'],
            'Microsoft Word' => ['slug' => 'word', 'icon_path' => 'logo/word-logo.jpg'],
            'Microsoft PowerPoint' => ['slug' => 'powerpoint', 'icon_path' => 'logo/pptx-logo.jpg'],
            'Microsoft SharePoint' => ['slug' => 'sharepoint', 'icon_path' => 'logo/sharepoint.jpg'],
            'Power Automate' => ['slug' => 'power-automate', 'icon_path' => 'logo/automate-logo.jpg'],
            'Redis' => ['slug' => 'redis', 'icon_path' => null],
            'Vue.js' => ['slug' => 'vue-js', 'icon_path' => null],
            'React' => ['slug' => 'react', 'icon_path' => null],
        ];

        foreach ($techs as $name => $data) {
            Technology::updateOrCreate(
                ['slug' => $data['slug']],
                ['name' => $name, 'icon_path' => $data['icon_path']]
            );
        }
    }
}
