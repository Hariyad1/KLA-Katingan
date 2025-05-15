<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsSeeder extends Seeder
{
    public function run(): void
    {

        $news = [
            [
                'title' => 'Program Kota Layak Anak Katingan Raih Penghargaan Nasional',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Kota Katingan berhasil meraih penghargaan nasional dalam implementasi program Kota Layak Anak. Proin vehicula, libero vel egestas fringilla, purus tortor faucibus purus, sed consequat ipsum velit sed magna. Vestibulum non ultrices diam. Sed ac lacus sit amet lectus finibus ornare. Duis a ipsum sed enim sollicitudin sagittis et non libero. Vivamus a placerat dolor. Proin varius orci vitae tempor vulputate.',
                'image' => 'news/penghargaan-kla.jpg',
                'kategori_id' => 6,
                'created_by' => 21,
                'status' => 1,
            ],
        ];

        foreach ($news as $item) {
            News::create($item);
        }
    }
} 