<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            [
                'name' => 'Pusat Bantuan',
                'email' => 'help@example.com',
                'phone' => '021-555-0123',
                'message' => 'Silakan hubungi kami jika ada pertanyaan',
            ],
            [
                'name' => 'Admin Sistem',
                'email' => 'admin@example.com',
                'phone' => '021-555-0124',
                'message' => 'Bantuan teknis sistem',
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::create($contact);
        }
    }
} 