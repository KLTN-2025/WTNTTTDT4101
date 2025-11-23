<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Difficulty;

class DifficultiesSeeder extends Seeder
{
    public function run()
    {
        $difficulties = [
            ['name' => 'Dễ', 'slug' => 'easy', 'level' => 1],
            ['name' => 'Trung bình', 'slug' => 'medium', 'level' => 2],
            ['name' => 'Khó', 'slug' => 'hard', 'level' => 3],
        ];

        foreach ($difficulties as $difficulty) {
            Difficulty::updateOrCreate(
                ['slug' => $difficulty['slug']],
                $difficulty
            );
        }
    }
}

