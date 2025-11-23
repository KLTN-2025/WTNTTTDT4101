<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\Score;
use App\Models\Skill;
use App\Models\StudySuggestion;
use App\Models\Question;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function personal(Request $request)
    {
        $user = Auth::user();

        $scoresBySkill = $this->calculateScoresBySkill($user);
        $progressOverTime = $this->calculateProgressOverTime($user);
        $suggestions = $this->generateStudySuggestions($user, $scoresBySkill);

        if ($request->header('HX-Request')) {
            return view('analytics.partials.personal-data', compact('scoresBySkill', 'progressOverTime', 'suggestions'));
        }

        return view('analytics.personal', compact('scoresBySkill', 'progressOverTime', 'suggestions'));
    }

    public function class(Request $request)
    {
        $classId = $request->get('class_id');
        $examId = $request->get('exam_id');

        $scoreDistribution = $this->calculateScoreDistribution($classId, $examId);
        $itemAnalysis = $this->calculateItemAnalysis($examId);
        $difficultQuestions = $this->getDifficultQuestions($itemAnalysis);

        if ($request->header('HX-Request')) {
            return view('analytics.partials.class-data', compact('scoreDistribution', 'itemAnalysis', 'difficultQuestions'));
        }

        $classes = Classroom::where('teacher_id', Auth::id())->get();
        $exams = Exam::where('created_by', Auth::id())->get();

        return view('analytics.class', compact('scoreDistribution', 'itemAnalysis', 'difficultQuestions', 'classes', 'exams', 'classId', 'examId'));
    }

    private function calculateScoresBySkill($user)
    {
        $answers = ExamAnswer::where('user_id', $user->id)
            ->with(['question.skills', 'exam.questions'])
            ->get();

        $skillScores = [];
        $skillCounts = [];

        foreach ($answers as $answer) {
            $question = $answer->question;
            $exam = $answer->exam;
            
            $maxPoints = 1;
            if ($exam) {
                $examQuestion = $exam->questions->firstWhere('id', $question->id);
                $maxPoints = $examQuestion->pivot->points ?? 1;
            }
            
            $earnedPoints = $answer->points_earned ?? 0;
            $percentage = $maxPoints > 0 ? ($earnedPoints / $maxPoints) * 100 : 0;

            foreach ($question->skills as $skill) {
                $skillName = $skill->name;
                if (!isset($skillScores[$skillName])) {
                    $skillScores[$skillName] = 0;
                    $skillCounts[$skillName] = 0;
                }
                $skillScores[$skillName] += $percentage;
                $skillCounts[$skillName]++;
            }
        }

        $result = [];
        foreach ($skillScores as $skillName => $total) {
            $result[$skillName] = $skillCounts[$skillName] > 0 
                ? round($total / $skillCounts[$skillName], 2) 
                : 0;
        }

        return $result;
    }

    private function calculateProgressOverTime($user)
    {
        $scores = Score::where('user_id', $user->id)
            ->orderBy('test_date', 'asc')
            ->get();

        $progress = [];
        foreach ($scores as $score) {
            $progress[] = [
                'date' => $score->test_date->format('Y-m-d'),
                'label' => $score->test_date->format('d/m'),
                'score' => (float) $score->score,
                'test_name' => $score->test_name,
            ];
        }

        if (count($progress) > 0) {
            $runningAverage = [];
            $sum = 0;
            for ($i = 0; $i < count($progress); $i++) {
                $sum += $progress[$i]['score'];
                $runningAverage[] = [
                    'date' => $progress[$i]['date'],
                    'label' => $progress[$i]['label'],
                    'average' => round($sum / ($i + 1), 2),
                ];
            }
            return $runningAverage;
        }

        return [];
    }

    private function generateStudySuggestions($user, $scoresBySkill)
    {
        $suggestions = StudySuggestion::where('user_id', $user->id)
            ->where('is_completed', false)
            ->orderBy('priority', 'desc')
            ->limit(5)
            ->get();

        if ($suggestions->isEmpty()) {
            $lowSkills = array_filter($scoresBySkill, fn($score) => $score < 70);
            
            foreach ($lowSkills as $skillName => $score) {
                StudySuggestion::create([
                    'user_id' => $user->id,
                    'title' => "Ôn tập kỹ năng: {$skillName}",
                    'description' => "Điểm hiện tại: {$score}%. Cần cải thiện kỹ năng này.",
                    'subject' => 'General',
                    'type' => 'skill_practice',
                    'priority' => 100 - $score,
                    'is_completed' => false,
                ]);
            }

            $suggestions = StudySuggestion::where('user_id', $user->id)
                ->where('is_completed', false)
                ->orderBy('priority', 'desc')
                ->limit(5)
                ->get();
        }

        return $suggestions;
    }

    private function calculateScoreDistribution($classId = null, $examId = null)
    {
        $query = Score::query();

        if ($classId) {
            $class = Classroom::find($classId);
            if ($class) {
                $userIds = $class->students->pluck('id');
                $query->whereIn('user_id', $userIds);
            }
        }

        if ($examId) {
            $query->where('exam_id', $examId);
        }

        $scores = $query->get()->pluck('score')->toArray();

        if (empty($scores)) {
            return [
                'histogram' => [],
                'statistics' => [
                    'min' => 0,
                    'max' => 0,
                    'mean' => 0,
                    'median' => 0,
                    'q1' => 0,
                    'q3' => 0,
                ],
            ];
        }

        sort($scores);
        $count = count($scores);

        $buckets = [
            '0-2' => 0,
            '2-4' => 0,
            '4-6' => 0,
            '6-8' => 0,
            '8-10' => 0,
        ];

        foreach ($scores as $score) {
            if ($score < 2) $buckets['0-2']++;
            elseif ($score < 4) $buckets['2-4']++;
            elseif ($score < 6) $buckets['4-6']++;
            elseif ($score < 8) $buckets['6-8']++;
            else $buckets['8-10']++;
        }

        $mean = array_sum($scores) / $count;
        $median = $count % 2 === 0 
            ? ($scores[$count/2 - 1] + $scores[$count/2]) / 2 
            : $scores[floor($count/2)];
        $q1Index = floor($count * 0.25);
        $q3Index = floor($count * 0.75);
        $q1 = $scores[$q1Index] ?? 0;
        $q3 = $scores[$q3Index] ?? 0;

        return [
            'histogram' => $buckets,
            'statistics' => [
                'min' => min($scores),
                'max' => max($scores),
                'mean' => round($mean, 2),
                'median' => round($median, 2),
                'q1' => round($q1, 2),
                'q3' => round($q3, 2),
            ],
        ];
    }

    private function calculateItemAnalysis($examId)
    {
        if (!$examId) {
            return [];
        }

        $exam = Exam::with('questions')->find($examId);
        if (!$exam) {
            return [];
        }

        $allAnswers = ExamAnswer::where('exam_id', $examId)
            ->with('question')
            ->get();

        $userScores = [];
        foreach ($allAnswers->groupBy('user_id') as $userId => $userAnswers) {
            $totalScore = $userAnswers->sum('points_earned');
            $userScores[$userId] = $totalScore;
        }

        if (empty($userScores)) {
            return [];
        }

        arsort($userScores);
        $totalUsers = count($userScores);
        $top27Percent = max(1, floor($totalUsers * 0.27));
        $bottom27Percent = max(1, floor($totalUsers * 0.27));

        $topUsers = array_slice(array_keys($userScores), 0, $top27Percent, true);
        $bottomUsers = array_slice(array_keys($userScores), -$bottom27Percent, $bottom27Percent, true);

        $itemAnalysis = [];

        foreach ($exam->questions as $question) {
            $questionAnswers = $allAnswers->where('question_id', $question->id);
            $totalAttempts = $questionAnswers->count();

            if ($totalAttempts === 0) {
                continue;
            }

            $correctCount = $questionAnswers->where('is_correct', true)->count();
            $difficulty = $totalAttempts > 0 ? $correctCount / $totalAttempts : 0;

            $topCorrect = $questionAnswers->whereIn('user_id', $topUsers)->where('is_correct', true)->count();
            $bottomCorrect = $questionAnswers->whereIn('user_id', $bottomUsers)->where('is_correct', true)->count();
            $topAttempts = $questionAnswers->whereIn('user_id', $topUsers)->count();
            $bottomAttempts = $questionAnswers->whereIn('user_id', $bottomUsers)->count();

            $topProportion = $topAttempts > 0 ? $topCorrect / $topAttempts : 0;
            $bottomProportion = $bottomAttempts > 0 ? $bottomCorrect / $bottomAttempts : 0;

            $discrimination = $topProportion - $bottomProportion;

            $suggestion = $this->getItemSuggestion($difficulty, $discrimination);

            $itemAnalysis[] = [
                'question_id' => $question->id,
                'question_title' => $question->title ?? "Câu hỏi #{$question->id}",
                'correct_rate' => round($difficulty * 100, 1),
                'difficulty' => round($difficulty, 3),
                'discrimination' => round($discrimination, 3),
                'suggestion' => $suggestion,
            ];
        }

        usort($itemAnalysis, fn($a, $b) => $a['difficulty'] <=> $b['difficulty']);

        return $itemAnalysis;
    }

    private function getItemSuggestion($difficulty, $discrimination)
    {
        if ($difficulty < 0.3) {
            if ($discrimination < 0.2) {
                return 'Câu quá khó và phân biệt kém. Xem lại câu hỏi/đáp án; tăng gợi ý';
            }
            return 'Câu khó nhưng phân biệt tốt. Có thể giữ hoặc điều chỉnh nhẹ';
        } elseif ($difficulty > 0.7) {
            if ($discrimination < 0.2) {
                return 'Câu dễ nhưng phân biệt kém. Tăng độ khó hoặc thêm nhiễu';
            }
            return 'Câu dễ, phân biệt tốt. Phù hợp cho kiểm tra cơ bản';
        } else {
            if ($discrimination < 0.2) {
                return 'Độ khó vừa nhưng phân biệt kém. Điều chỉnh đáp án nhiễu';
            }
            return 'Câu hỏi tốt, độ khó và phân biệt phù hợp';
        }
    }

    private function getDifficultQuestions($itemAnalysis)
    {
        return array_filter($itemAnalysis, function($item) {
            return $item['difficulty'] < 0.5 || $item['discrimination'] < 0.2;
        });
    }
}
