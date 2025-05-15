<?php

namespace Database\Seeders;

use App\Models\Indikator;
use App\Models\Klaster;
use Illuminate\Database\Seeder;

class IndikatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Klaster 1 : Hak Sipil dan Kebebasan
        $klaster1 = Klaster::where('name', 'Klaster 1 - Hak Sipil dan Kebebasan')->first();
        
        $indikators1 = [
            [
                'klaster_id' => $klaster1->id,
                'name' => 'Persentase anak yang teregistrasi dan mendapatkan Kutipan Akta Kelahiran'
            ],
            [
                'klaster_id' => $klaster1->id,
                'name' => 'Tersedia fasilitas informasi layak anak'
            ],
            [
                'klaster_id' => $klaster1->id,
                'name' => 'Jumlah kelompok anak, termasuk Forum Anak, yang ada di kabupaten/kota, kecamatan dan desa/kelurahan'
            ]
        ];

        // Klaster 2 : Lingkungan Keluarga dan Pengasuhan Alternatif
        $klaster2 = Klaster::where('name', 'Klaster 2 - Lingkungan Keluarga dan Pengasuhan Alternatif')->first();
        
        $indikators2 = [
            [
                'klaster_id' => $klaster2->id,
                'name' => 'Persentase usia perkawinan pertama di bawah 18 (delapan belas) tahun'
            ],
            [
                'klaster_id' => $klaster2->id,
                'name' => 'Tersedia lembaga konsultasi bagi orang tua/keluarga tentang pengasuhan dan perawatan anak'
            ],
            [
                'klaster_id' => $klaster2->id,
                'name' => 'Tersedia lembaga kesejahteraan sosial anak'
            ]
        ];

        // Klaster 3 : Kesehatan Dasar dan Kesejahteraan
        $klaster3 = Klaster::where('name', 'Klaster 3 - Kesehatan Dasar dan Kesejahteraan')->first();
        
        $indikators3 = [
            [
                'klaster_id' => $klaster3->id,
                'name' => 'Angka kematian bayi'
            ],
            [
                'klaster_id' => $klaster3->id,
                'name' => 'Prevalensi kekurangan gizi pada balita'
            ],
            [
                'klaster_id' => $klaster3->id,
                'name' => 'Persentase air susu ibu (ASI) eksklusif'
            ],
            [
                'klaster_id' => $klaster3->id,
                'name' => 'Jumlah pojok ASI'
            ],
            [
                'klaster_id' => $klaster3->id,
                'name' => 'Persentase imunisasi dasar lengkap'
            ],
            [
                'klaster_id' => $klaster3->id,
                'name' => 'Jumlah lembaga yang memberikan pelayanan kesehatan reproduksi dan mental'
            ],
            [
                'klaster_id' => $klaster3->id,
                'name' => 'Jumlah anak dari keluarga miskin yang memperoleh akses peningkatan kesejahteraan'
            ],
            [
                'klaster_id' => $klaster3->id,
                'name' => 'Persentase rumah tangga dengan akses air bersih'
            ],
            [
                'klaster_id' => $klaster3->id,
                'name' => 'Tersedia kawasan tanpa rokok'
            ]         
        ];

        // Klaster 4 : Pendidikan, Pemanfaatan Waktu Luang, dan Kegiatan Budaya
        $klaster4 = Klaster::where('name', 'Klaster 4 - Pendidikan, Pemanfaatan Waktu Luang, dan Kegiatan Budaya')->first();
        
        $indikators4 = [
            [
                'klaster_id' => $klaster4->id,
                'name' => 'Angka partisipasi pendidikan anak usia dini'
            ],
            [
                'klaster_id' => $klaster4->id,
                'name' => 'Persentase wajib belajar pendidikan 12 (dua belas) tahun'
            ],
            [
                'klaster_id' => $klaster4->id,
                'name' => 'Persentase sekolah ramah anak'
            ],
            [
                'klaster_id' => $klaster4->id,
                'name' => 'Jumlah sekolah yang memiliki program, sarana dan prasarana perjalanan anak ke dan dari sekolah'
            ],
            [
                'klaster_id' => $klaster4->id,
                'name' => 'Tersedia fasilitas untuk kegiatan kreatif dan rekreatif yang ramah anak, di luar sekolah, yang dapat diakses semua anak'
            ]
        ];

        // Klaster 5 : Perlindungan Khusus
        $klaster5 = Klaster::where('name', 'Klaster 5 - Perlindungan Khusus')->first();
        
        $indikators5 = [
            [
                'klaster_id' => $klaster5->id,
                'name' => 'Persentase anak yang memerlukan perlindungan khusus dan memperoleh pelayanan'
            ],
            [
                'klaster_id' => $klaster5->id,
                'name' => 'Persentase kasus anak berhadapan dengan hukum (ABH) yang diselesaikan dengan pendekatan keadilan restoratif (restorative justice)'
            ],
            [
                'klaster_id' => $klaster5->id,
                'name' => 'Adanya mekanisme penanggulangan bencana yang memperhatikan kepentingan anak'
            ],
            [
                'klaster_id' => $klaster5->id,
                'name' => 'Persentase anak yang dibebaskan dari bentuk-bentuk pekerjaan terburuk anak'
            ]
        ];

        $allIndikators = array_merge($indikators1, $indikators2, $indikators3, $indikators4, $indikators5);

        foreach ($allIndikators as $indikator) {
            Indikator::create($indikator);
        }
    }
} 