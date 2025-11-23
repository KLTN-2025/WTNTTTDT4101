<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ExamTemplate;
use App\Models\ExamSchedule;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Tag;
use App\Models\Difficulty;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamTemplateController extends Controller
{
    /**
     * Display a listing of exam templates.
     */
    public function index()
    {
        $templates = ExamTemplate::with(['creator', 'schedules'])
            ->where('created_by', Auth::id())
            ->latest()
            ->paginate(20);

        $tags = Tag::all();
        $difficulties = Difficulty::all();
        $classes = Classroom::where('teacher_id', Auth::id())->get();
        $students = User::where('role', 'student')->get();

        return view('teacher.exams.index', compact('templates', 'tags', 'difficulties', 'classes', 'students'));
    }

    /**
     * Store a newly created exam template.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        
        if (is_string($data['structure'] ?? null)) {
            $data['structure'] = json_decode($data['structure'], true);
        }
        
        if (is_string($data['difficulty_distribution'] ?? null)) {
            $data['difficulty_distribution'] = json_decode($data['difficulty_distribution'], true);
        }
        
        if (isset($data['randomize_questions'])) {
            $data['randomize_questions'] = filter_var($data['randomize_questions'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
        }
        
        if (isset($data['randomize_options'])) {
            $data['randomize_options'] = filter_var($data['randomize_options'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
        }
        
        if (isset($data['unique_per_student'])) {
            $data['unique_per_student'] = filter_var($data['unique_per_student'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
        }
        
        $request->merge($data);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'structure' => 'required|array',
            'structure.*.tag' => 'required|string',
            'structure.*.count' => 'required|integer|min:1',
            'structure.*.points' => 'required|numeric|min:0',
            'difficulty_distribution' => 'required|array',
            'difficulty_distribution.easy' => 'required|numeric|min:0|max:100',
            'difficulty_distribution.medium' => 'required|numeric|min:0|max:100',
            'difficulty_distribution.hard' => 'required|numeric|min:0|max:100',
            'randomize_questions' => 'boolean',
            'randomize_options' => 'boolean',
            'unique_per_student' => 'boolean',
        ]);

        // Validate difficulty percentages sum to 100
        $totalPct = $validated['difficulty_distribution']['easy'] 
            + $validated['difficulty_distribution']['medium'] 
            + $validated['difficulty_distribution']['hard'];
        
        if (abs($totalPct - 100) > 0.01) {
            return back()->withErrors(['difficulty_distribution' => 'Tổng tỉ lệ độ khó phải bằng 100%']);
        }

        // Calculate totals
        $totalQuestions = array_sum(array_column($validated['structure'], 'count'));
        $totalPoints = 0;
        foreach ($validated['structure'] as $section) {
            $totalPoints += $section['count'] * $section['points'];
        }

        $template = ExamTemplate::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'structure' => $validated['structure'],
            'difficulty_distribution' => $validated['difficulty_distribution'],
            'randomize_questions' => $validated['randomize_questions'] ?? false,
            'randomize_options' => $validated['randomize_options'] ?? false,
            'unique_per_student' => $validated['unique_per_student'] ?? false,
            'total_questions' => $totalQuestions,
            'total_points' => $totalPoints,
            'created_by' => Auth::id(),
        ]);

        // Generate questions for preview
        $questions = $this->generateQuestions($template);

        if ($request->header('HX-Request')) {
            return response()->json([
                'success' => true,
                'template' => $template,
                'preview' => $questions->map(function($q) {
                    return [
                        'id' => $q->id,
                        'title' => $q->title,
                        'difficulty' => $q->difficulty->name ?? '-',
                        'points' => $q->pivot->points ?? 1,
                    ];
                })
            ]);
        }

        return redirect()->route('teacher.exams.index')->with('success', 'Mẫu đề đã được tạo thành công.');
    }

    /**
     * Generate exam from template.
     */
    public function generateExam(Request $request, ExamTemplate $template)
    {
        $isAjax = $request->header('HX-Request') || $request->header('X-Requested-With') === 'XMLHttpRequest';

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'description' => 'nullable|string',
                'class_id' => 'nullable|exists:classes,id',
                'user_ids' => 'nullable|array',
                'user_ids.*' => 'exists:users,id',
                'start_date' => 'nullable|date',
                'due_date' => 'nullable|date|after:start_date',
                'duration_minutes' => 'nullable|integer|min:1',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $e->errors(),
                ], 422);
            }
            return back()->withErrors($e->errors());
        }

        try {
            $questions = $this->generateQuestions($template);

            if ($questions->isEmpty()) {
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không thể tạo đề thi. Không tìm thấy câu hỏi phù hợp với mẫu đề.',
                    ], 400);
                }
                return back()->withErrors(['error' => 'Không thể tạo đề thi. Không tìm thấy câu hỏi phù hợp với mẫu đề.']);
            }

            // Create exam
            $exam = Exam::create([
                'title' => $validated['name'],
                'subject' => $validated['subject'],
                'description' => $validated['description'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'due_date' => $validated['due_date'] ?? null,
                'status' => 'upcoming',
                'created_by' => Auth::id(),
            ]);

            // Attach questions with order and points
            $order = 1;
            foreach ($questions as $question) {
                $points = $this->getPointsForQuestion($question, $template);
                $exam->questions()->attach($question->id, [
                    'order' => $order++,
                    'points' => $points,
                ]);
            }

            // Assign exam to students
            $userIds = [];
            
            if (isset($validated['class_id'])) {
                $class = Classroom::findOrFail($validated['class_id']);
                $userIds = $class->students()->get()->pluck('id')->toArray();
            }
            
            if (isset($validated['user_ids']) && is_array($validated['user_ids'])) {
                $userIds = array_merge($userIds, $validated['user_ids']);
            }
            
            $userIds = array_unique($userIds);
            
            if (!empty($userIds)) {
                $durationMinutes = $validated['duration_minutes'] ?? 60;
                $attachData = [];
                foreach ($userIds as $userId) {
                    $attachData[$userId] = [
                        'status' => 'not_started',
                        'duration_minutes' => $durationMinutes,
                    ];
                }
                $exam->users()->sync($attachData);
            }

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đề thi đã được tạo và gán cho ' . count($userIds) . ' học sinh.',
                    'exam_id' => $exam->id,
                ]);
            }

            return redirect()->route('teacher.exams.index')->with('success', 'Đề thi đã được tạo từ mẫu và gán cho ' . count($userIds) . ' học sinh.');
        } catch (\Exception $e) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                ], 500);
            }
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    /**
     * Store exam schedule.
     */
    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'exam_template_id' => 'nullable|exists:exam_templates,id',
            'exam_id' => 'nullable|exists:exams,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration_minutes' => 'required|integer|min:1',
            'max_attempts' => 'required|integer|min:1',
        ]);

        $schedule = ExamSchedule::create([
            'exam_template_id' => $validated['exam_template_id'] ?? null,
            'exam_id' => $validated['exam_id'] ?? null,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'duration_minutes' => $validated['duration_minutes'],
            'max_attempts' => $validated['max_attempts'],
            'created_by' => Auth::id(),
        ]);

        if ($request->header('HX-Request')) {
            return response()->json(['success' => true, 'schedule' => $schedule]);
        }

        return redirect()->route('teacher.exams.index')->with('success', 'Lịch thi đã được tạo thành công.');
    }

    /**
     * Generate questions based on template criteria.
     */
    private function generateQuestions(ExamTemplate $template): \Illuminate\Support\Collection
    {
        $allQuestions = collect();
        $structure = $template->structure ?? [];
        $difficultyDist = $template->difficulty_distribution ?? ['easy' => 40, 'medium' => 40, 'hard' => 20];

        foreach ($structure as $section) {
            $tag = $section['tag'] ?? '';
            $count = $section['count'] ?? 0;
            
            if (empty($tag) || $count <= 0) {
                continue;
            }

            // Find tag
            $tagModel = Tag::where('slug', \Illuminate\Support\Str::slug($tag))
                ->orWhere('name', 'like', "%{$tag}%")
                ->first();

            if (!$tagModel) {
                continue;
            }

            // Get questions with this tag
            $query = Question::whereHas('tags', function($q) use ($tagModel) {
                $q->where('tags.id', $tagModel->id);
            });

            // Calculate difficulty distribution for this section
            $easyCount = (int) round($count * $difficultyDist['easy'] / 100);
            $mediumCount = (int) round($count * $difficultyDist['medium'] / 100);
            $hardCount = $count - $easyCount - $mediumCount;

            // Get questions by difficulty
            $easyQuestions = (clone $query)->whereHas('difficulty', function($q) {
                $q->where('slug', 'easy');
            })->inRandomOrder()->limit($easyCount)->get();

            $mediumQuestions = (clone $query)->whereHas('difficulty', function($q) {
                $q->where('slug', 'medium');
            })->inRandomOrder()->limit($mediumCount)->get();

            $hardQuestions = (clone $query)->whereHas('difficulty', function($q) {
                $q->where('slug', 'hard');
            })->inRandomOrder()->limit($hardCount)->get();

            $allQuestions = $allQuestions->merge($easyQuestions)
                ->merge($mediumQuestions)
                ->merge($hardQuestions);
        }

        // Randomize if needed
        if ($template->randomize_questions) {
            $allQuestions = $allQuestions->shuffle();
        }

        return $allQuestions->take(array_sum(array_column($structure, 'count')));
    }

    /**
     * Get points for a question based on template structure.
     */
    private function getPointsForQuestion(Question $question, ExamTemplate $template): float
    {
        $structure = $template->structure ?? [];
        
        // Find matching tag in structure
        foreach ($structure as $section) {
            $tag = $section['tag'] ?? '';
            $tagModel = Tag::where('slug', \Illuminate\Support\Str::slug($tag))
                ->orWhere('name', 'like', "%{$tag}%")
                ->first();

            if ($tagModel && $question->tags->contains($tagModel->id)) {
                return (float) ($section['points'] ?? 1.0);
            }
        }

        return 1.0; // Default points
    }
}

