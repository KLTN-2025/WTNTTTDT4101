<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Tag;
use App\Models\Difficulty;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Support\Str;

class QuestionsSeeder extends Seeder
{
    public function run()
    {
        // Lấy user đầu tiên làm creator (hoặc tạo nếu chưa có)
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Teacher Demo',
                'email' => 'teacher@example.com',
                'password' => bcrypt('password'),
                'role' => 'teacher',
            ]);
        }

        // Lấy difficulties
        $easy = Difficulty::where('slug', 'easy')->first();
        $medium = Difficulty::where('slug', 'medium')->first();
        $hard = Difficulty::where('slug', 'hard')->first();

        // Lấy skills
        $algebra = Skill::where('slug', 'algebra')->first();
        $geometry = Skill::where('slug', 'geometry')->first();
        $reading = Skill::where('slug', 'reading')->first();

        // Tạo tags
        $tagToan = Tag::firstOrCreate(
            ['slug' => 'toan'],
            ['name' => 'Toán']
        );
        $tagCoBan = Tag::firstOrCreate(
            ['slug' => 'co-ban'],
            ['name' => 'Cơ bản']
        );
        $tagVan = Tag::firstOrCreate(
            ['slug' => 'van'],
            ['name' => 'Văn']
        );

        // Câu hỏi 1: Trắc nghiệm đơn giản - Toán
        $q1 = Question::create([
            'title' => '2 + 2 = ?',
            'content' => 'Tính kết quả của phép tính: \(2 + 2\)',
            'type' => 'single',
            'difficulty_id' => $easy->id,
            'options' => ['2', '3', '4', '5'],
            'correct_answer' => [2], // Index của đáp án đúng (4)
            'explanation' => 'Phép cộng đơn giản: 2 + 2 = 4',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
        $q1->tags()->attach([$tagToan->id, $tagCoBan->id]);
        $q1->skills()->attach($algebra->id);

        // Câu hỏi 2: Nhiều đáp án - Toán
        $q2 = Question::create([
            'title' => 'Số nguyên tố nhỏ hơn 10',
            'content' => 'Chọn tất cả các số nguyên tố nhỏ hơn 10:',
            'type' => 'multi',
            'difficulty_id' => $medium->id,
            'options' => ['2', '3', '4', '5', '6', '7', '8', '9'],
            'correct_answer' => [0, 1, 3, 5], // 2, 3, 5, 7
            'explanation' => 'Số nguyên tố nhỏ hơn 10 là: 2, 3, 5, 7',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
        $q2->tags()->attach($tagToan->id);
        $q2->skills()->attach($algebra->id);

        // Câu hỏi 3: Đúng/Sai
        $q3 = Question::create([
            'title' => 'Hà Nội là thủ đô của Việt Nam',
            'content' => 'Câu hỏi đúng/sai: Hà Nội là thủ đô của Việt Nam',
            'type' => 'boolean',
            'difficulty_id' => $easy->id,
            'options' => null,
            'correct_answer' => 'true',
            'explanation' => 'Hà Nội là thủ đô của Việt Nam từ năm 1976',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
        $q3->tags()->attach($tagVan->id);
        $q3->skills()->attach($reading->id);

        // Câu hỏi 4: Điền từ
        $q4 = Question::create([
            'title' => 'Điền từ vào chỗ trống',
            'content' => 'Việt Nam nằm ở khu vực Đông Nam ___.',
            'type' => 'text',
            'difficulty_id' => $easy->id,
            'options' => null,
            'correct_answer' => ['Á', 'A'], // Chấp nhận cả "Á" và "A"
            'explanation' => 'Việt Nam nằm ở khu vực Đông Nam Á',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
        $q4->tags()->attach($tagVan->id);
        $q4->skills()->attach($reading->id);

        // Tạo version cho mỗi câu hỏi
        foreach ([$q1, $q2, $q3, $q4] as $question) {
            $question->versions()->create([
                'version_number' => 1,
                'data' => $question->toArray(),
                'change_note' => 'Tạo mới câu hỏi',
                'created_by' => $user->id,
            ]);
        }

        $this->command->info('Đã tạo 4 câu hỏi mẫu thành công!');
    }
}

