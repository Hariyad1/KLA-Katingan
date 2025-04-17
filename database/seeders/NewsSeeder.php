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
                'kategori_id' => 5,
                'created_by' => 21,
                'status' => 1,
            ],
            [
                'title' => 'Pembangunan Taman Ramah Anak di 5 Kecamatan',
                'content' => 'Suspendisse potenti. Pemerintah Kabupaten Katingan memulai pembangunan taman ramah anak di 5 kecamatan. Nullam auctor gravida diam, in maximus dui iaculis in. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Praesent euismod nisi justo, vel hendrerit enim gravida sed. Sed pretium lorem at ultricies faucibus. Curabitur non est nisi. Morbi tempus mauris vitae nisi condimentum sagittis.',
                'image' => 'news/taman-anak.jpg',
                'kategori_id' => 5,
                'created_by' => 21,
                'status' => 1,
            ],
            [
                'title' => 'Workshop Perlindungan Anak Berbasis Masyarakat',
                'content' => 'Etiam in ex quis enim hendrerit suscipit. Workshop ini bertujuan untuk meningkatkan kesadaran masyarakat tentang perlindungan anak. Mauris vehicula turpis sit amet congue vehicula. Sed sed sem convallis, convallis nunc ut, tempus enim. Nulla facilisi. Integer lacinia sapien non diam convallis tempus. Donec dapibus ipsum sit amet viverra faucibus.',
                'image' => 'news/workshop-anak.jpg',
                'kategori_id' => 2,
                'created_by' => 21,
                'status' => 1,
            ],
            [
                'title' => 'Peluncuran Program Sekolah Ramah Anak',
                'content' => 'Maecenas tincidunt erat id rhoncus facilisis. Program ini merupakan inovasi dalam menciptakan lingkungan pendidikan yang ramah anak. Cras sagittis mi sit amet malesuada mollis. Mauris purus erat, varius vel mauris ac, placerat vestibulum sem. Maecenas eu sodales augue, vitae pretium mi. Aliquam erat volutpat. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae.',
                'image' => 'news/sekolah-ramah-anak.jpg',
                'kategori_id' => 1,
                'created_by' => 21,
                'status' => 1,
            ],
            [
                'title' => 'Forum Anak Katingan Gelar Aksi Peduli Lingkungan',
                'content' => 'Phasellus dignissim feugiat tellus, non fermentum enim tincidunt non. Forum Anak Katingan menginisiasi gerakan peduli lingkungan dengan melibatkan ratusan anak. Vestibulum consequat hendrerit lacus ut tincidunt. Cras non erat in purus placerat mollis. Morbi non diam augue. Nulla at tempus sem, et fringilla dolor. Maecenas mollis quis neque ut commodo.',
                'image' => 'news/forum-anak.jpg',
                'kategori_id' => 1,
                'created_by' => 21,
                'status' => 1,
            ],
            [
                'title' => 'Pengembangan Infrastruktur Digital di Desa',
                'content' => '<p>Program pengembangan infrastruktur digital di desa telah mencapai kemajuan signifikan. Beberapa desa telah memiliki akses internet berkecepatan tinggi dan fasilitas digital yang memadai.</p>',
                'image' => 'images/news/digital-infrastructure.jpg',
                'kategori_id' => 1,
                'created_by' => 21,
                'status' => 1,
            ],
            [
                'title' => 'Workshop Pelatihan Digital Marketing',
                'content' => '<p>Workshop pelatihan digital marketing untuk UMKM desa telah dilaksanakan dengan sukses. Para peserta belajar tentang strategi pemasaran online dan penggunaan media sosial.</p>',
                'image' => 'images/news/digital-marketing.jpg',
                'kategori_id' => 2,
                'created_by' => 21,
                'status' => 1,
            ],
            [
                'title' => 'Inovasi Teknologi Pertanian',
                'content' => '<p>Penerapan teknologi modern dalam pertanian telah meningkatkan hasil panen. Petani local kini menggunakan sistem irigasi pintar dan monitoring tanaman berbasis IoT.</p>',
                'image' => 'images/news/smart-farming.jpg',
                'kategori_id' => 1,
                'created_by' => 21,
                'status' => 1,
            ],
            [
                'title' => 'Program Literasi Digital',
                'content' => '<p>Program literasi digital untuk masyarakat desa telah dimulai. Fokus program adalah pada penggunaan teknologi secara aman dan produktif.</p>',
                'image' => 'images/news/digital-literacy.jpg',
                'kategori_id' => 2,
                'created_by' => 21,
                'status' => 1,
            ],
            [
                'title' => 'Pembangunan Pusat Digital',
                'content' => '<p>Pusat digital desa telah diresmikan. Fasilitas ini akan menjadi hub untuk berbagai kegiatan digital dan pelatihan teknologi.</p>',
                'image' => 'images/news/digital-center.jpg',
                'kategori_id' => 1,
                'created_by' => 21,
                'status' => 1,
            ],
            [
                'title' => 'Pelatihan E-Commerce untuk UMKM',
                'content' => '<p>Sesi pelatihan e-commerce telah diadakan untuk pelaku UMKM. Peserta belajar cara memasarkan produk mereka di platform online.</p>',
                'image' => 'images/news/ecommerce-training.jpg',
                'kategori_id' => 2,
                'created_by' => 21,
                'status' => 1,
            ],
            [
                'title' => 'Implementasi Smart Village',
                'content' => '<p>Konsep smart village mulai diterapkan di beberapa desa percontohan. Sistem monitoring berbasis IoT telah dipasang untuk berbagai keperluan.</p>',
                'image' => 'images/news/smart-village.jpg',
                'kategori_id' => 1,
                'created_by' => 21,
                'status' => 1,
            ],
            [
                'title' => 'Pengembangan Aplikasi Desa',
                'content' => '<p>Aplikasi khusus untuk manajemen desa telah dikembangkan. Aplikasi ini akan memudahkan administrasi dan pelayanan masyarakat.</p>',
                'image' => 'images/news/village-app.jpg',
                'kategori_id' => 2,
                'created_by' => 21,
                'status' => 1,
            ],
            [
                'title' => 'Digitalisasi Arsip Desa',
                'content' => '<p>Proses digitalisasi arsip desa telah dimulai. Sistem manajemen dokumen digital akan memudahkan pencarian dan pengelolaan arsip.</p>',
                'image' => 'images/news/digital-archive.jpg',
                'kategori_id' => 1,
                'created_by' => 21,
                'status' => 1,
            ],
            [
                'title' => 'Pelatihan Sistem Informasi Desa',
                'content' => '<p>Pelatihan penggunaan sistem informasi desa telah dilaksanakan. Perangkat desa kini dapat mengelola data dengan lebih efisien.</p>',
                'image' => 'images/news/information-system.jpg',
                'kategori_id' => 2,
                'created_by' => 21,
                'status' => 1,
            ],
        ];

        foreach ($news as $item) {
            News::create($item);
        }
    }
} 