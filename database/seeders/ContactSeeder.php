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
                'name' => 'Lorem ipsum dolor sit amet',
                'email' => 'lorem@example.com',
                'phone' => '021-555-0123',
                'message' => 'Lorem ipsum dolor sit amet',
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::create($contact);
        }
    }
} 