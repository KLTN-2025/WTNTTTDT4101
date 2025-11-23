<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamEssayGrade;
use App\Models\Question;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GradingController extends Controller
{
    public function index(Request $request)
    {
        $query = Exam::with(['creator', 'users', 'questions'])
            ->where('created_by', Auth::id());

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $exams = $query->latest()->paginate(15);

        if ($request->header('HX-Request')) {
            return view('teacher.grading.partials.exam-list', compact('exams'));
        }

        return view('teacher.grading.index', compact('exams'));
    }

    public function show(Exam $exam)
    {
        $exam->load(['questions', 'users', 'answers.user']);

        $submissions = DB::table('user_exams')
            ->where('user_exams.exam_id', $exam->id)
            ->where('user_exams.status', 'submitted')
            ->join('users', 'user_exams.user_id', '=', 'users.id')
            ->select('users.*', 'user_exams.submitted_at', 'user_exams.status')
            ->get();

        foreach ($submissions as $submission) {
            $answers = ExamAnswer::where('exam_id', $exam->id)
                ->where('user_id', $submission->id)
                ->with(['question', 'essayGrade'])
                ->get();

            $submission->answers = $answers;
            $submission->total_score = $answers->sum('points_earned');
            $submission->max_score = $exam->questions->sum(function($q) {
                return $q->pivot->points ?? 0;
            });
        }

        if (request()->header('HX-Request')) {
            return view('teacher.grading.partials.submission-list', compact('exam', 'submissions'));
        }

        return view('teacher.grading.show', compact('exam', 'submissions'));
    }

    public function gradeMultipleChoice(Exam $exam, Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = $request->user_id;
        $answers = ExamAnswer::where('exam_id', $exam->id)
            ->where('user_id', $userId)
            ->with('question')
            ->get();

        $totalScore = 0;
        $maxScore = 0;

        foreach ($answers as $answer) {
            $question = $answer->question;
            $maxScore += $question->pivot->points ?? 1;

            if ($question->type === 'multiple_choice' || $question->type === 'true_false') {
                $correctAnswer = is_array($question->correct_answer) 
                    ? $question->correct_answer 
                    : [$question->correct_answer];
                $selectedOptions = is_array($answer->selected_options) 
                    ? $answer->selected_options 
                    : [$answer->selected_options];

                $isCorrect = empty(array_diff($correctAnswer, $selectedOptions)) 
                    && empty(array_diff($selectedOptions, $correctAnswer));

                $points = $isCorrect ? ($question->pivot->points ?? 1) : 0;

                $answer->update([
                    'is_correct' => $isCorrect,
                    'points_earned' => $points,
                ]);

                $totalScore += $points;
            }
        }

        DB::table('user_exams')
            ->where('exam_id', $exam->id)
            ->where('user_id', $userId)
            ->update(['status' => 'graded']);

        Score::updateOrCreate(
            [
                'user_id' => $userId,
                'exam_id' => $exam->id,
            ],
            [
                'subject' => $exam->subject,
                'test_name' => $exam->title,
                'score' => $totalScore,
                'test_date' => now(),
            ]
        );

        if ($request->header('HX-Request')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã chấm điểm tự động thành công',
                'total_score' => $totalScore,
                'max_score' => $maxScore,
            ]);
        }

        return redirect()->back()->with('success', 'Đã chấm điểm tự động thành công');
    }

    public function gradeEssay(Exam $exam, Request $request)
    {
        $request->validate([
            'answer_id' => 'required|exists:exam_answers,id',
            'score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:0',
            'feedback' => 'nullable|string',
            'rubric_scores' => 'nullable|array',
        ]);

        $answer = ExamAnswer::findOrFail($request->answer_id);

        ExamEssayGrade::updateOrCreate(
            ['exam_answer_id' => $answer->id],
            [
                'graded_by' => Auth::id(),
                'score' => $request->score,
                'max_score' => $request->max_score,
                'rubric_scores' => $request->rubric_scores,
                'feedback' => $request->feedback,
                'status' => 'graded',
            ]
        );

        $answer->update([
            'points_earned' => $request->score,
            'teacher_feedback' => $request->feedback,
        ]);

        $this->updateExamScore($exam->id, $answer->user_id);

        if ($request->header('HX-Request')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã chấm điểm tự luận thành công',
            ]);
        }

        return redirect()->back()->with('success', 'Đã chấm điểm tự luận thành công');
    }

    public function getAISuggestion(Exam $exam, ExamAnswer $answer)
    {
        $question = $answer->question;
        $correctAnswer = $question->correct_answer;
        $studentAnswer = $answer->answer;

        $suggestion = "Gợi ý chấm điểm:\n";
        $suggestion .= "- Đáp án đúng: " . (is_array($correctAnswer) ? implode(', ', $correctAnswer) : $correctAnswer) . "\n";
        $suggestion .= "- Câu trả lời của học sinh: " . $studentAnswer . "\n";
        $suggestion .= "- Đánh giá: Cần kiểm tra độ chính xác, đầy đủ và logic của câu trả lời.";

        ExamEssayGrade::updateOrCreate(
            ['exam_answer_id' => $answer->id],
            [
                'ai_suggestion' => $suggestion,
                'status' => 'pending',
            ]
        );

        if (request()->header('HX-Request')) {
            return response()->json([
                'success' => true,
                'suggestion' => $suggestion,
            ]);
        }

        return redirect()->back()->with('suggestion', $suggestion);
    }

    private function updateExamScore($examId, $userId)
    {
        $answers = ExamAnswer::where('exam_id', $examId)
            ->where('user_id', $userId)
            ->get();

        $totalScore = $answers->sum('points_earned');
        $exam = Exam::find($examId);

        Score::updateOrCreate(
            [
                'user_id' => $userId,
                'exam_id' => $examId,
            ],
            [
                'subject' => $exam->subject,
                'test_name' => $exam->title,
                'score' => $totalScore,
                'test_date' => now(),
            ]
        );
    }
}
