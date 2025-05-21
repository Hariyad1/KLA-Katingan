# Website Kota Layak Anak

Sistem informasi berbasis web untuk Kota Layak Anak yang menyediakan informasi, berita, dan layanan terkait program Kota Layak Anak.

## Tentang Aplikasi

Website ini dikembangkan untuk mendukung program Kota Layak Anak dengan menyediakan:
- Informasi program/kegiatan Kota Layak Anak
- Berita terkait anak
- Layanan pengaduan dan aspirasi

## Persyaratan Sistem

- PHP >= 8.2
- Composer
- Node.js
- PostgreSQL

## Instalasi

1. Clone repositori ini:
```bash
git clone [url-repositori]
cd [nama-folder-proyek]
```

2. Install dependensi PHP:
```bash
composer install
```

3. Install dependensi Node.js:
```bash
npm install
```

4. Salin file .env.example menjadi .env:
```bash
cp .env.example .env
```

5. Generate key aplikasi:
```bash
php artisan key:generate
```

6. Konfigurasi database di file .env

7. Jalankan migrasi database:
```bash
php artisan migrate
```

8. Buat symbolic link untuk storage:
```bash
php artisan storage:link
```

9. Compile asset:
```bash
npm run dev
```

10. Jalankan server:
```bash
php artisan serve
```

## Deployment

1. Build asset untuk production:
```bash
npm run build
```

2. Pastikan symbolic link storage sudah dibuat:
```bash
php artisan storage:link
```

3. Optimasi Laravel:
```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Struktur Proyek

- `app/` - Berisi model, controller, dan logika aplikasi
- `database/` - Migrasi dan seeder database
- `resources/` - Views, assets, dan file frontend
- `routes/` - Definisi route aplikasi
- `public/` - File-file publik yang dapat diakses langsung

## Pengembangan

## Kontribusi

## Lisensi