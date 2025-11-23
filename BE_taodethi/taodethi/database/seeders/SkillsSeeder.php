<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillsSeeder extends Seeder
{
    public function run()
    {
        $skills = [
            ['name' => 'Đọc hiểu', 'slug' => 'reading', 'category' => 'language'],
            ['name' => 'Viết', 'slug' => 'writing', 'category' => 'language'],
            ['name' => 'Nghe', 'slug' => 'listening', 'category' => 'language'],
            ['name' => 'Nói', 'slug' => 'speaking', 'category' => 'language'],
            ['name' => 'Đại số', 'slug' => 'algebra', 'category' => 'math'],
            ['name' => 'Hình học', 'slug' => 'geometry', 'category' => 'math'],
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(
                ['slug' => $skill['slug']],
                $skill
            );
        }
    }
}

