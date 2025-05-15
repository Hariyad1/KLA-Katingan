<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'name' => 'Video Intro',
                'page' => 'home',
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'image' => null,
                'content' => 'Video perkenalan aplikasi KLA',
                'type' => 'video'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
} 