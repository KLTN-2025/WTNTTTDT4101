<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Exam;
use App\Models\User;
use App\Models\Difficulty;
use App\Models\Tag;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;

class ExamWithQuestionsSeeder extends Seeder
{
    public function run()
    {
        $teacher = User::where('role', 'teacher')->first();
        if (!$teacher) {
            $teacher = User::create([
                'name' => 'Giáo viên Demo',
                'email' => 'teacher@example.com',
                'password' => bcrypt('password'),
                'role' => 'teacher',
                'status' => 'active',
            ]);
        }

        $students = User::where('role', 'student')->get();
        if ($students->isEmpty()) {
            $students = collect([
                User::create([
                    'name' => 'Học sinh 1',
                    'email' => 'student1@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'student',
                    'status' => 'active',
                ]),
                User::create([
                    'name' => 'Học sinh 2',
                    'email' => 'student2@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'student',
                    'status' => 'active',
                ]),
            ]);
        }

        $easy = Difficulty::where('slug', 'easy')->first();
        $medium = Difficulty::where('slug', 'medium')->first();
        $hard = Difficulty::where('slug', 'hard')->first();

        if (!$easy) {
            $easy = Difficulty::create(['name' => 'Dễ', 'slug' => 'easy', 'level' => 1]);
        }
        if (!$medium) {
            $medium = Difficulty::create(['name' => 'Trung bình', 'slug' => 'medium', 'level' => 2]);
        }
        if (!$hard) {
            $hard = Difficulty::create(['name' => 'Khó', 'slug' => 'hard', 'level' => 3]);
        }

        $tagToan = Tag::firstOrCreate(['slug' => 'toan'], ['name' => 'Toán']);
        $tagVan = Tag::firstOrCreate(['slug' => 'van'], ['name' => 'Văn']);
        $tagDia = Tag::firstOrCreate(['slug' => 'dia'], ['name' => 'Địa lý']);

        $algebra = Skill::where('slug', 'algebra')->first();
        if (!$algebra) {
            $algebra = Skill::create(['name' => 'Đại số', 'slug' => 'algebra']);
        }

        $questions = [];

        $q1 = Question::create([
            'title' => 'Phép tính cơ bản',
            'content' => 'Tính kết quả của phép tính: \(2 + 2 = ?\)',
            'type' => 'single',
            'difficulty_id' => $easy->id,
            'options' => ['2', '3', '4', '5'],
            'correct_answer' => [2],
            'explanation' => 'Phép cộng đơn giản: 2 + 2 = 4',
            'created_by' => $teacher->id,
            'updated_by' => $teacher->id,
        ]);
        $q1->tags()->attach($tagToan->id);
        $questions[] = $q1;

        $q2 = Question::create([
            'title' => 'Số nguyên tố',
            'content' => 'Chọn tất cả các số nguyên tố nhỏ hơn 10:',
            'type' => 'multi',
            'difficulty_id' => $medium->id,
            'options' => ['2', '3', '4', '5', '6', '7', '8', '9'],
            'correct_answer' => [0, 1, 3, 5],
            'explanation' => 'Số nguyên tố nhỏ hơn 10 là: 2, 3, 5, 7',
            'created_by' => $teacher->id,
            'updated_by' => $teacher->id,
        ]);
        $q2->tags()->attach($tagToan->id);
        $questions[] = $q2;

        $q3 = Question::create([
            'title' => 'Thủ đô Việt Nam',
            'content' => 'Hà Nội là thủ đô của Việt Nam',
            'type' => 'boolean',
            'difficulty_id' => $easy->id,
            'options' => null,
            'correct_answer' => 'true',
            'explanation' => 'Hà Nội là thủ đô của Việt Nam từ năm 1976',
            'created_by' => $teacher->id,
            'updated_by' => $teacher->id,
        ]);
        $q3->tags()->attach($tagDia->id);
        $questions[] = $q3;

        $q4 = Question::create([
            'title' => 'Điền từ - Địa lý',
            'content' => 'Việt Nam nằm ở khu vực Đông Nam ___.',
            'type' => 'text',
            'difficulty_id' => $easy->id,
            'options' => null,
            'correct_answer' => ['Á', 'A'],
            'explanation' => 'Việt Nam nằm ở khu vực Đông Nam Á',
            'created_by' => $teacher->id,
            'updated_by' => $teacher->id,
        ]);
        $q4->tags()->attach($tagDia->id);
        $questions[] = $q4;

        $q5 = Question::create([
            'title' => 'Sắp xếp số',
            'content' => 'Sắp xếp các số sau theo thứ tự tăng dần:',
            'type' => 'order',
            'difficulty_id' => $easy->id,
            'options' => ['8', '3', '5', '1'],
            'correct_answer' => ['1', '3', '5', '8'],
            'explanation' => 'Thứ tự tăng dần: 1, 3, 5, 8',
            'created_by' => $teacher->id,
            'updated_by' => $teacher->id,
        ]);
        $q5->tags()->attach($tagToan->id);
        $questions[] = $q5;

        $q6 = Question::create([
            'title' => 'Ghép cặp thành phố',
            'content' => 'Ghép cặp các thành phố với miền tương ứng:',
            'type' => 'match',
            'difficulty_id' => $medium->id,
            'options' => [
                'left' => [
                    'HN' => 'Hà Nội',
                    'HCM' => 'TP.HCM',
                    'DN' => 'Đà Nẵng'
                ],
                'right' => [
                    'MB' => 'Miền Bắc',
                    'MN' => 'Miền Nam',
                    'MT' => 'Miền Trung'
                ]
            ],
            'correct_answer' => [
                'HN' => 'MB',
                'HCM' => 'MN',
                'DN' => 'MT'
            ],
            'explanation' => 'Hà Nội thuộc Miền Bắc, TP.HCM thuộc Miền Nam, Đà Nẵng thuộc Miền Trung',
            'created_by' => $teacher->id,
            'updated_by' => $teacher->id,
        ]);
        $q6->tags()->attach($tagDia->id);
        $questions[] = $q6;

        $q7 = Question::create([
            'title' => 'Câu hỏi tự luận',
            'content' => 'Trình bày cảm nhận của bạn về một thói quen học tập hiệu quả (8-10 câu).',
            'type' => 'essay',
            'difficulty_id' => $hard->id,
            'options' => null,
            'correct_answer' => null,
            'explanation' => 'Câu tự luận sẽ được giáo viên chấm thủ công',
            'created_by' => $teacher->id,
            'updated_by' => $teacher->id,
        ]);
        $q7->tags()->attach($tagVan->id);
        $questions[] = $q7;

        $q8 = Question::create([
            'title' => 'Công thức toán học',
            'content' => 'Công thức định lý Pythagoras: \(a^2 + b^2 = c^2\). Câu này đúng hay sai?',
            'type' => 'single',
            'difficulty_id' => $medium->id,
            'options' => ['Sai', 'Không đủ dữ kiện', 'Đúng'],
            'correct_answer' => [2],
            'explanation' => 'Công thức định lý Pythagoras: \(a^2 + b^2 = c^2\) là đúng',
            'image_url' => 'https://via.placeholder.com/300x180?text=Hinh+minh+hoa',
            'created_by' => $teacher->id,
            'updated_by' => $teacher->id,
        ]);
        $q8->tags()->attach($tagToan->id);
        $questions[] = $q8;

        $q9 = Question::create([
            'title' => 'Phép nhân',
            'content' => 'Tính: \(3 \times 4 = ?\)',
            'type' => 'single',
            'difficulty_id' => $easy->id,
            'options' => ['10', '11', '12', '13'],
            'correct_answer' => [2],
            'explanation' => 'Phép nhân: 3 × 4 = 12',
            'created_by' => $teacher->id,
            'updated_by' => $teacher->id,
        ]);
        $q9->tags()->attach($tagToan->id);
        $questions[] = $q9;

        $q10 = Question::create([
            'title' => 'Phân số',
            'content' => 'Chọn các phân số bằng \(\frac{1}{2}\):',
            'type' => 'multi',
            'difficulty_id' => $medium->id,
            'options' => ['\(\frac{2}{4}\)', '\(\frac{3}{6}\)', '\(\frac{1}{3}\)', '\(\frac{5}{10}\)'],
            'correct_answer' => [0, 1, 3],
            'explanation' => 'Các phân số bằng \(\frac{1}{2}\) là: \(\frac{2}{4}\), \(\frac{3}{6}\), \(\frac{5}{10}\)',
            'created_by' => $teacher->id,
            'updated_by' => $teacher->id,
        ]);
        $q10->tags()->attach($tagToan->id);
        $questions[] = $q10;

        $exam = Exam::create([
            'title' => 'Bài kiểm tra tổng hợp - Tất cả loại câu hỏi',
            'subject' => 'Tổng hợp',
            'description' => 'Bài kiểm tra bao gồm tất cả các loại câu hỏi: trắc nghiệm, nhiều đáp án, đúng/sai, điền từ, sắp xếp, ghép cặp, tự luận',
            'start_date' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'in_progress',
            'created_by' => $teacher->id,
        ]);

        foreach ($questions as $index => $question) {
            $exam->questions()->attach($question->id, [
                'order' => $index + 1,
                'points' => 1.0,
            ]);
        }

        foreach ($students as $student) {
            DB::table('user_exams')->insert([
                'user_id' => $student->id,
                'exam_id' => $exam->id,
                'status' => 'not_started',
                'duration_minutes' => 60,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info("Đã tạo bài thi '{$exam->title}' với 10 câu hỏi và gán cho " . $students->count() . " học sinh!");
    }
}

