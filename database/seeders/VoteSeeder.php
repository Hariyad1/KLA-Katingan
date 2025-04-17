<?php

namespace Database\Seeders;

use App\Models\Vote;
use Illuminate\Database\Seeder;

class VoteSeeder extends Seeder
{
    public function run(): void
    {
        $votes = [
            [
                'ip_address' => '192.168.1.1',
                'value' => 5,
                'created_at' => now(),
            ],
            [
                'ip_address' => '192.168.1.2',
                'value' => 4,
                'created_at' => now(),
            ],
            [
                'ip_address' => '192.168.1.3',
                'value' => 5,
                'created_at' => now(),
            ],
        ];

        foreach ($votes as $vote) {
            Vote::create($vote);
        }
    }
} 