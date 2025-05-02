<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KlasterSeeder::class,
            IndikatorSeeder::class,
            OpdSeeder::class,
            DataDukungSeeder::class,
            AgendaSeeder::class,
            ContactSeeder::class,
            KategoriSeeder::class,
            NewsSeeder::class,
            SettingSeeder::class,
            VoteSeeder::class,
            MediaSeeder::class,
            DocumentMediaSeeder::class,
            ProgramKerjaSeeder::class,
        ]);
    }
}
