<?php

namespace Database\Seeders;

use App\Models\Opd;
use Illuminate\Database\Seeder;

class OpdSeeder extends Seeder
{
    public function run(): void
    {
        $opds = [
            ['name' => 'Dinas Pendidikan'],
            ['name' => 'Dinas Kesehatan'],
            ['name' => 'Dinas Pekerjaan Umum'],
            ['name' => 'Dinas Perhubungan'],
            ['name' => 'Dinas Lingkungan Hidup'],
        ];

        foreach ($opds as $opd) {
            Opd::create($opd);
        }
    }
} 