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
            'Laravel' => ['slug' => 'laravel', 'icon_path' => 'logo/laravel-logo.svg'],
            'PHP' => ['slug' => 'php', 'icon_path' => 'logo/php-logo.svg'],
            'MySQL' => ['slug' => 'mysql', 'icon_path' => 'logo/mysql-logo.svg'],
            'Bootstrap 5' => ['slug' => 'bootstrap-5', 'icon_path' => 'logo/bootstrap-5-logo.svg'],
            'Tailwind CSS' => ['slug' => 'tailwind-css', 'icon_path' => 'logo/tailwind-css-logo.svg'],
            'Docker' => ['slug' => 'docker', 'icon_path' => 'logo/docker-logo.svg'],
            'Git' => ['slug' => 'git', 'icon_path' => 'logo/git-logo.svg'],
            'Notion' => ['slug' => 'notion', 'icon_path' => 'logo/notion-logo.svg'],
            'Canva' => ['slug' => 'canva', 'icon_path' => 'logo/canva-logo.svg'],
            'Draw.io' => ['slug' => 'draw-io', 'icon_path' => 'logo/draw-io-logo.svg'],
            'Microsoft Excel' => ['slug' => 'excel', 'icon_path' => 'logo/excel-logo.svg'],
            'Microsoft Word' => ['slug' => 'word', 'icon_path' => 'logo/word-logo.svg'],
            'Microsoft PowerPoint' => ['slug' => 'powerpoint', 'icon_path' => 'logo/powerpoint-logo.svg'],
            'Microsoft SharePoint' => ['slug' => 'sharepoint', 'icon_path' => 'logo/sharepoint.svg'],
            'Power Automate' => ['slug' => 'power-automate', 'icon_path' => 'logo/power-automate-logo.svg'],
            'Redis' => ['slug' => 'redis', 'icon_path' => 'logo/redis-logo.svg'],
            'Vue.js' => ['slug' => 'vue-js', 'icon_path' => 'logo/vue-logo.svg'],
            'React' => ['slug' => 'react', 'icon_path' => 'logo/react-logo.svg'],
        ];

        foreach ($techs as $name => $data) {
            Technology::updateOrCreate(
                ['slug' => $data['slug']],
                ['name' => $name, 'icon_path' => $data['icon_path']]
            );
        }
    }
}
