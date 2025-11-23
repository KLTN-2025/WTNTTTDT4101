<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Score;
use App\Models\StudyProgress;
use App\Models\StudySuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Redirect teachers and admins to their dashboard
        if ($user->role === 'teacher' || $user->role === 'admin') {
            return redirect()->route('teacher.dashboard');
        }

        // Lấy các bài kiểm tra của học sinh
        $exams = $user->exams()
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        // Lấy tiến trình học
        $studyProgress = $user->studyProgress()
            ->orderBy('subject')
            ->get();

        // Lấy điểm gần nhất
        $recentScores = $user->scores()
            ->orderBy('test_date', 'desc')
            ->limit(5)
            ->get();

        // Lấy gợi ý học
        $studySuggestions = $user->studySuggestions()
            ->where('is_completed', false)
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('dashboard', compact('exams', 'studyProgress', 'recentScores', 'studySuggestions'));
    }

    /**
     * Refresh study suggestions (HTMX endpoint).
     */
    public function refreshSuggestions()
    {
        $user = Auth::user();

        $studySuggestions = $user->studySuggestions()
            ->where('is_completed', false)
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('partials.study-suggestions', compact('studySuggestions'));
    }
}

