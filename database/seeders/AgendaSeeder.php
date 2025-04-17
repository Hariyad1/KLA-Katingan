<?php

namespace Database\Seeders;

use App\Models\Agenda;
use Illuminate\Database\Seeder;

class AgendaSeeder extends Seeder
{
    public function run(): void
    {
        $agendas = [
            [
                'title' => 'Rapat Koordinasi OPD',
                'description' => 'Rapat koordinasi seluruh OPD terkait program kerja 2024',
                'date' => '2024-03-25',
                'time' => '09:00:00',
                'location' => 'Ruang Rapat Utama',
            ],
            [
                'title' => 'Workshop Data Dukung',
                'description' => 'Pelatihan penggunaan sistem data dukung untuk admin OPD',
                'date' => '2024-03-27',
                'time' => '13:00:00',
                'location' => 'Aula Diklat',
            ],
        ];

        foreach ($agendas as $agenda) {
            Agenda::create($agenda);
        }
    }
} 