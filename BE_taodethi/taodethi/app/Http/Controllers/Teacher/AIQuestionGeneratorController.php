<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\AIQuestionGeneratorService;
use App\Models\Question;
use App\Models\Tag;
use App\Models\Difficulty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AIQuestionGeneratorController extends Controller
{
    protected $aiService;

    public function __construct(AIQuestionGeneratorService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Display the AI generator page.
     */
    public function index()
    {
        return view('teacher.ai.generator');
    }

    /**
     * Generate questions from text content or file.
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'content' => 'nullable|string|min:50',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,txt|max:10240',
            'count' => 'required|integer|min:1|max:50',
            'type' => 'required|in:mcq,tf,stem',
            'difficulty' => 'nullable|in:mix,easy,medium,hard',
            'generate_distractors' => 'boolean',
            'include_citations' => 'boolean',
        ]);

        try {
            $content = $validated['content'] ?? '';

            if ($request->hasFile('file')) {
                $fileContent = $this->aiService->extractTextFromFile($request->file('file'));
                $content = $content ? $content . "\n\n" . $fileContent : $fileContent;
            }

            if (empty(trim($content))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng nhập nội dung hoặc tải file'
                ], 400);
            }

            if (strlen($content) < 50) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nội dung quá ngắn. Vui lòng nhập ít nhất 50 ký tự.'
                ], 400);
            }

            $questions = $this->aiService->generateQuestions(
                $content,
                $validated['count'],
                $validated['type'],
                $validated['difficulty'] ?? 'mix',
                $validated['generate_distractors'] ?? true,
                $validated['include_citations'] ?? false
            );

            return response()->json([
                'success' => true,
                'questions' => $questions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi sinh câu hỏi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import generated questions to question bank.
     */
    public function import(Request $request)
    {
        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.*.stem' => 'required|string',
            'questions.*.options' => 'nullable|array',
            'questions.*.correct' => 'nullable',
            'questions.*.type' => 'required|in:mcq,tf,stem',
            'questions.*.difficulty' => 'nullable|in:easy,medium,hard',
            'questions.*.citation' => 'nullable|string',
        ]);

        $imported = [];
        $difficulties = Difficulty::all()->keyBy('slug');

        foreach ($validated['questions'] as $qData) {
            $type = $qData['type'] === 'mcq' ? 'single' : ($qData['type'] === 'tf' ? 'boolean' : 'essay');
            
            $difficultyId = null;
            if (isset($qData['difficulty'])) {
                $difficulty = $difficulties->get($qData['difficulty']);
                $difficultyId = $difficulty ? $difficulty->id : null;
            }

            $options = null;
            $correctAnswer = null;

            if ($type === 'single' && isset($qData['options'])) {
                $options = $qData['options'];
                $correctIndex = array_search($qData['correct'] ?? '', $qData['options']);
                $correctAnswer = $correctIndex !== false ? $correctIndex : null;
            } elseif ($type === 'boolean') {
                $correctAnswer = ($qData['correct'] ?? 'Đúng') === 'Đúng' ? 'true' : 'false';
            }

            $question = Question::create([
                'title' => $qData['stem'],
                'content' => $qData['stem'],
                'type' => $type,
                'difficulty_id' => $difficultyId,
                'options' => $options,
                'correct_answer' => $correctAnswer,
                'explanation' => isset($qData['citation']) ? "Nguồn: {$qData['citation']}" : null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            $imported[] = $question;
        }

        return response()->json([
            'success' => true,
            'message' => "Đã thêm " . count($imported) . " câu hỏi vào ngân hàng",
            'count' => count($imported)
        ]);
    }
}

