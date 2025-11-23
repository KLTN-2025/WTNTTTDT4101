<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\Question;
use App\Models\QuestionFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $exams = $user->exams()
            ->with(['creator', 'questions'])
            ->orderBy('due_date', 'asc')
            ->get();

        if (request()->header('HX-Request')) {
            return view('student.quiz.partials.exam-list', compact('exams'));
        }

        return view('student.quiz.index', compact('exams'));
    }

    public function show(Exam $exam)
    {
        $user = Auth::user();

        if (!$user->exams()->where('exams.id', $exam->id)->exists()) {
            abort(403, 'Bạn không có quyền truy cập bài thi này.');
        }

        $exam->load(['questions' => function ($query) {
            $query->orderBy('exam_questions.order');
        }]);

        $session = ExamSession::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->first();

        $userExam = $user->exams()->where('exams.id', $exam->id)->first();
        $isSubmitted = $userExam->pivot->status === 'submitted';

        if (request()->header('HX-Request')) {
            return view('student.quiz.partials.exam-detail', compact('exam', 'session', 'isSubmitted'));
        }

        return view('student.quiz.show', compact('exam', 'session', 'isSubmitted'));
    }

    public function start(Exam $exam)
    {
        $user = Auth::user();

        if (!$user->exams()->where('exams.id', $exam->id)->exists()) {
            abort(403);
        }

        $userExam = $user->exams()->where('exams.id', $exam->id)->first();
        if ($userExam->pivot->status === 'submitted') {
            return redirect()->route('student.quiz.show', $exam)
                ->with('error', 'Bạn đã nộp bài thi này.');
        }

        $exam->load(['questions' => function ($query) {
            $query->orderBy('exam_questions.order');
        }]);

        $questionIds = $exam->questions->pluck('id')->toArray();
        $shuffledIds = $questionIds;
        shuffle($shuffledIds);

        $durationMinutes = $userExam->pivot->duration_minutes ?? 60;
        $remainingSeconds = $durationMinutes * 60;

        $session = ExamSession::updateOrCreate(
            [
                'user_id' => $user->id,
                'exam_id' => $exam->id,
            ],
            [
                'question_order' => $shuffledIds,
                'answers' => [],
                'remaining_seconds' => $remainingSeconds,
                'started_at' => now(),
            ]
        );

        DB::table('user_exams')
            ->where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->update(['started_at' => now(), 'status' => 'in_progress']);

        return redirect()->route('student.quiz.take', $exam);
    }

    public function take(Exam $exam)
    {
        $user = Auth::user();

        if (!$user->exams()->where('exams.id', $exam->id)->exists()) {
            abort(403);
        }

        $session = ExamSession::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->first();

        if (!$session) {
            return redirect()->route('student.quiz.show', $exam)
                ->with('error', 'Vui lòng bắt đầu làm bài.');
        }

        $userExam = $user->exams()->where('exams.id', $exam->id)->first();
        if ($userExam->pivot->status === 'submitted') {
            return redirect()->route('student.quiz.show', $exam)
                ->with('error', 'Bạn đã nộp bài thi này.');
        }

        $questionIds = $session->question_order ?? [];
        $questions = Question::whereIn('id', $questionIds)
            ->with(['difficulty', 'tags', 'skills'])
            ->get()
            ->sortBy(function ($question) use ($questionIds) {
                return array_search($question->id, $questionIds);
            });

        $savedAnswers = $session->answers ?? [];

        $durationMinutes = $userExam->pivot->duration_minutes ?? 60;

        return view('student.quiz.take', compact('exam', 'questions', 'session', 'savedAnswers', 'durationMinutes'));
    }

    public function saveAnswer(Request $request, Exam $exam, Question $question)
    {
        $user = Auth::user();

        $session = ExamSession::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->first();

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $answers = $session->answers ?? [];
        
        $answerData = match($question->type) {
            'single', 'boolean' => $request->input('answer'),
            'multi' => $request->input('answers', []),
            'text' => $request->input('answer', ''),
            'order' => $request->input('order', []),
            'match' => $request->input('pairs', []),
            'essay' => $request->input('answer', ''),
            default => null,
        };

        $answers[$question->id] = $answerData;
        $session->update(['answers' => $answers]);

        if ($request->header('HX-Request')) {
            return response()->json(['success' => true, 'message' => 'Đã lưu']);
        }

        return response()->json(['success' => true]);
    }

    public function saveDraft(Request $request, Exam $exam)
    {
        $user = Auth::user();

        $session = ExamSession::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->first();

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $answers = $request->input('answers', []);
        $remainingSeconds = $request->input('remaining_seconds');

        $session->update([
            'answers' => $answers,
            'remaining_seconds' => $remainingSeconds,
        ]);

        if ($request->header('HX-Request')) {
            return response('<span class="text-xs text-gray-600">Đã lưu nháp</span>');
        }

        return response()->json(['success' => true, 'message' => 'Đã lưu nháp']);
    }

    public function submit(Request $request, Exam $exam)
    {
        $user = Auth::user();

        $session = ExamSession::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->first();

        if (!$session) {
            return redirect()->route('student.quiz.show', $exam)
                ->with('error', 'Session not found');
        }

        $userExam = $user->exams()->where('exams.id', $exam->id)->first();
        if ($userExam->pivot->status === 'submitted') {
            return redirect()->route('student.quiz.show', $exam)
                ->with('error', 'Bạn đã nộp bài thi này.');
        }

        $exam->load(['questions' => function ($query) {
            $query->orderBy('exam_questions.order');
        }]);

        $answersJson = $request->input('answers');
        $answers = is_string($answersJson) ? json_decode($answersJson, true) : ($answersJson ?? $session->answers ?? []);
        $totalScore = 0;
        $maxScore = 0;

        DB::transaction(function () use ($user, $exam, $answers, $session, &$totalScore, &$maxScore) {
            foreach ($exam->questions as $question) {
                $points = $exam->questions->find($question->id)->pivot->points ?? 1;
                $maxScore += $points;

                $userAnswer = $answers[$question->id] ?? null;
                $isCorrect = false;
                $pointsEarned = 0;

                if ($userAnswer !== null) {
                    $isCorrect = $this->gradeAnswer($question, $userAnswer);
                    $pointsEarned = $isCorrect ? $points : 0;
                    $totalScore += $pointsEarned;
                }

                ExamAnswer::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'exam_id' => $exam->id,
                        'question_id' => $question->id,
                    ],
                    [
                        'answer' => is_array($userAnswer) ? json_encode($userAnswer) : $userAnswer,
                        'selected_options' => is_array($userAnswer) ? $userAnswer : null,
                        'is_correct' => $isCorrect,
                        'points_earned' => $pointsEarned,
                    ]
                );
            }

            DB::table('user_exams')
                ->where('user_id', $user->id)
                ->where('exam_id', $exam->id)
                ->update([
                    'status' => 'submitted',
                    'submitted_at' => now(),
                    'completed_at' => now(),
                    'score' => $totalScore,
                    'max_score' => $maxScore,
                ]);

            $session->delete();
        });

        if ($request->header('HX-Request')) {
            return redirect()->route('student.quiz.result', $exam)
                ->with('success', 'Đã nộp bài thành công');
        }

        return redirect()->route('student.quiz.result', $exam)
            ->with('success', 'Đã nộp bài thành công');
    }

    public function result(Exam $exam)
    {
        $user = Auth::user();

        if (!$user->exams()->where('exams.id', $exam->id)->exists()) {
            abort(403);
        }

        $userExam = $user->exams()->where('exams.id', $exam->id)->first();
        if ($userExam->pivot->status !== 'submitted') {
            return redirect()->route('student.quiz.show', $exam);
        }

        $exam->load(['questions' => function ($query) {
            $query->orderBy('exam_questions.order');
        }]);

        $answers = ExamAnswer::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->with('question')
            ->get()
            ->keyBy('question_id');

        $score = $userExam->pivot->score ?? 0;
        $maxScore = $userExam->pivot->max_score ?? 0;

        return view('student.quiz.result', compact('exam', 'answers', 'score', 'maxScore'));
    }

    public function feedback(Request $request, Exam $exam, Question $question)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'type' => 'required|in:error,clarification,note',
            'message' => 'required|string|max:1000',
        ]);

        QuestionFeedback::create([
            'user_id' => $user->id,
            'exam_id' => $exam->id,
            'question_id' => $question->id,
            'type' => $validated['type'],
            'message' => $validated['message'],
        ]);

        if ($request->header('HX-Request')) {
            return response('<span class="text-xs text-green-600">Đã gửi phản hồi</span>');
        }

        return response()->json(['success' => true, 'message' => 'Đã gửi phản hồi']);
    }

    private function gradeAnswer(Question $question, $userAnswer): bool
    {
        $correctAnswer = $question->correct_answer;

        if ($correctAnswer === null) {
            return false;
        }

        return match($question->type) {
            'single' => $this->gradeSingle($userAnswer, $correctAnswer),
            'boolean' => $this->gradeBoolean($userAnswer, $correctAnswer),
            'multi' => $this->gradeMulti($userAnswer, $correctAnswer),
            'text' => $this->gradeText($userAnswer, $correctAnswer),
            'order' => $this->gradeOrder($userAnswer, $correctAnswer),
            'match' => $this->gradeMatch($userAnswer, $correctAnswer),
            'essay' => false,
            default => false,
        };
    }

    private function gradeSingle($userAnswer, $correctAnswer): bool
    {
        if (!is_string($userAnswer)) {
            return false;
        }

        if (is_array($correctAnswer)) {
            if (empty($correctAnswer)) {
                return false;
            }
            $correctIndex = $correctAnswer[0];
            return (string)$userAnswer === (string)$correctIndex;
        }

        return (string)$userAnswer === (string)$correctAnswer;
    }

    private function gradeBoolean($userAnswer, $correctAnswer): bool
    {
        if (!is_string($userAnswer)) {
            return false;
        }

        if (is_array($correctAnswer)) {
            if (empty($correctAnswer)) {
                return false;
            }
            $correctValue = $correctAnswer[0];
            return (string)$userAnswer === (string)$correctValue;
        }

        return (string)$userAnswer === (string)$correctAnswer;
    }

    private function gradeMulti($userAnswer, $correctAnswer): bool
    {
        if (!is_array($userAnswer) || !is_array($correctAnswer)) {
            return false;
        }

        if (count($userAnswer) !== count($correctAnswer)) {
            return false;
        }

        sort($userAnswer);
        sort($correctAnswer);

        return $userAnswer === $correctAnswer;
    }

    private function gradeText($userAnswer, $correctAnswer): bool
    {
        if (!is_string($userAnswer)) {
            return false;
        }

        $userAnswer = strtolower(trim($userAnswer));

        if (is_array($correctAnswer)) {
            foreach ($correctAnswer as $correct) {
                if (strtolower(trim($correct)) === $userAnswer) {
                    return true;
                }
            }
            return false;
        }

        if (is_string($correctAnswer)) {
            return $userAnswer === strtolower(trim($correctAnswer));
        }

        return false;
    }

    private function gradeOrder($userAnswer, $correctAnswer): bool
    {
        if (!is_array($userAnswer) || !is_array($correctAnswer)) {
            return false;
        }

        if (count($userAnswer) !== count($correctAnswer)) {
            return false;
        }

        return $userAnswer === $correctAnswer;
    }

    private function gradeMatch($userAnswer, $correctAnswer): bool
    {
        if (!is_array($userAnswer) || !is_array($correctAnswer)) {
            return false;
        }

        if (count($userAnswer) !== count($correctAnswer)) {
            return false;
        }

        foreach ($correctAnswer as $key => $value) {
            if (!isset($userAnswer[$key]) || $userAnswer[$key] !== $value) {
                return false;
            }
        }

        return true;
    }
}

