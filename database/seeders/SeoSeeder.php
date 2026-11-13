<?php

namespace Database\Seeders;

use App\Models\Seo;
use Illuminate\Database\Seeder;

class SeoSeeder extends Seeder
{
    public function run(): void
    {
        Seo::firstOrCreate(
            ['slug' => 'default-seo'],
            [
                'page' => 'Default SEO',
                'meta_title' => '{site_name} · Trust, Test, and Evaluate your AI Systems',
                'meta_description' => config('ethaika.hero.text'),
            ]
        );
    }
}
