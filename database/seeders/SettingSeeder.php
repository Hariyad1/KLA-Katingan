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
                'key' => 'app_name',
                'value' => 'Sistem Data Dukung OPD',
            ],
            [
                'key' => 'app_description',
                'value' => 'Sistem Informasi Data Dukung Organisasi Perangkat Daerah',
            ],
            [
                'key' => 'contact_email',
                'value' => 'contact@example.com',
            ],
            [
                'key' => 'contact_phone',
                'value' => '021-555-0123',
            ],
            [
                'key' => 'address',
                'value' => 'Jl. Contoh No. 123, Kota Example',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
} 