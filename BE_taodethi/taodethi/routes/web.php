<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/refresh-suggestions', [App\Http\Controllers\DashboardController::class, 'refreshSuggestions'])->name('dashboard.refresh-suggestions');

    Route::get('/exam', [App\Http\Controllers\Student\QuizController::class, 'index'])->name('exam');

    Route::prefix('student/quiz')->name('student.quiz')->group(function () {
        Route::get('/', [App\Http\Controllers\Student\QuizController::class, 'index'])->name('.index');
        Route::get('/{exam}', [App\Http\Controllers\Student\QuizController::class, 'show'])->name('.show');
        Route::post('/{exam}/start', [App\Http\Controllers\Student\QuizController::class, 'start'])->name('.start');
        Route::get('/{exam}/take', [App\Http\Controllers\Student\QuizController::class, 'take'])->name('.take');
        Route::post('/{exam}/answer/{question}', [App\Http\Controllers\Student\QuizController::class, 'saveAnswer'])->name('.save-answer');
        Route::post('/{exam}/draft', [App\Http\Controllers\Student\QuizController::class, 'saveDraft'])->name('.save-draft');
        Route::post('/{exam}/submit', [App\Http\Controllers\Student\QuizController::class, 'submit'])->name('.submit');
        Route::get('/{exam}/result', [App\Http\Controllers\Student\QuizController::class, 'result'])->name('.result');
        Route::post('/{exam}/feedback/{question}', [App\Http\Controllers\Student\QuizController::class, 'feedback'])->name('.feedback');
    });

    // Teacher Questions (CRUD)
    Route::prefix('teacher/questions')->name('teacher.questions')->group(function () {
        Route::get('/', [App\Http\Controllers\Teacher\QuestionController::class, 'index'])->name('.index');
        Route::get('/create', [App\Http\Controllers\Teacher\QuestionController::class, 'create'])->name('.create');
        Route::post('/', [App\Http\Controllers\Teacher\QuestionController::class, 'store'])->name('.store');
        Route::get('/{question}', [App\Http\Controllers\Teacher\QuestionController::class, 'show'])->name('.show');
        Route::get('/{question}/edit', [App\Http\Controllers\Teacher\QuestionController::class, 'edit'])->name('.edit');
        Route::put('/{question}', [App\Http\Controllers\Teacher\QuestionController::class, 'update'])->name('.update');
        Route::delete('/{question}', [App\Http\Controllers\Teacher\QuestionController::class, 'destroy'])->name('.destroy');
        Route::post('/{question}/toggle-flag', [App\Http\Controllers\Teacher\QuestionController::class, 'toggleFlag'])->name('.toggle-flag');
        Route::get('/{question}/history', [App\Http\Controllers\Teacher\QuestionController::class, 'history'])->name('.history');
    });

    // Teacher Exam Templates
    Route::prefix('teacher/exams')->name('teacher.exams')->group(function () {
        Route::get('/', [App\Http\Controllers\Teacher\ExamTemplateController::class, 'index'])->name('.index');
        Route::post('/templates', [App\Http\Controllers\Teacher\ExamTemplateController::class, 'store'])->name('.templates.store');
        Route::post('/templates/{template}/generate', [App\Http\Controllers\Teacher\ExamTemplateController::class, 'generateExam'])->name('.templates.generate');
        Route::post('/schedules', [App\Http\Controllers\Teacher\ExamTemplateController::class, 'storeSchedule'])->name('.schedules.store');
    });

    Route::prefix('teacher/grading')->name('teacher.grading')->group(function () {
        Route::get('/', [App\Http\Controllers\Teacher\GradingController::class, 'index'])->name('.index');
        Route::get('/{exam}', [App\Http\Controllers\Teacher\GradingController::class, 'show'])->name('.show');
        Route::post('/{exam}/grade-multiple-choice', [App\Http\Controllers\Teacher\GradingController::class, 'gradeMultipleChoice'])->name('.grade-multiple-choice');
        Route::post('/{exam}/grade-essay', [App\Http\Controllers\Teacher\GradingController::class, 'gradeEssay'])->name('.grade-essay');
        Route::get('/{exam}/answers/{answer}/ai-suggestion', [App\Http\Controllers\Teacher\GradingController::class, 'getAISuggestion'])->name('.ai-suggestion');
    });

    Route::prefix('teacher/classes')->name('teacher.classes')->group(function () {
        Route::get('/', [App\Http\Controllers\Teacher\ClassController::class, 'index'])->name('.index');
        Route::post('/', [App\Http\Controllers\Teacher\ClassController::class, 'store'])->name('.store');
        Route::get('/{class}', [App\Http\Controllers\Teacher\ClassController::class, 'show'])->name('.show');
        Route::put('/{class}', [App\Http\Controllers\Teacher\ClassController::class, 'update'])->name('.update');
        Route::delete('/{class}', [App\Http\Controllers\Teacher\ClassController::class, 'destroy'])->name('.destroy');
        Route::post('/{class}/students', [App\Http\Controllers\Teacher\ClassController::class, 'addStudent'])->name('.add-student');
        Route::delete('/{class}/students/{user}', [App\Http\Controllers\Teacher\ClassController::class, 'removeStudent'])->name('.remove-student');
        Route::post('/{class}/import-students', [App\Http\Controllers\Teacher\ClassController::class, 'importStudents'])->name('.import-students');
        Route::put('/{class}/students/{user}/role', [App\Http\Controllers\Teacher\ClassController::class, 'updateStudentRole'])->name('.update-student-role');
    });

    Route::prefix('teacher/schedules')->name('teacher.schedules')->group(function () {
        Route::get('/', [App\Http\Controllers\Teacher\ScheduleController::class, 'index'])->name('.index');
        Route::post('/', [App\Http\Controllers\Teacher\ScheduleController::class, 'store'])->name('.store');
        Route::get('/{schedule}', [App\Http\Controllers\Teacher\ScheduleController::class, 'show'])->name('.show');
        Route::put('/{schedule}', [App\Http\Controllers\Teacher\ScheduleController::class, 'update'])->name('.update');
        Route::delete('/{schedule}', [App\Http\Controllers\Teacher\ScheduleController::class, 'destroy'])->name('.destroy');
        Route::post('/{schedule}/toggle-active', [App\Http\Controllers\Teacher\ScheduleController::class, 'toggleActive'])->name('.toggle-active');
        Route::post('/{schedule}/open', [App\Http\Controllers\Teacher\ScheduleController::class, 'openExam'])->name('.open');
        Route::post('/{schedule}/close', [App\Http\Controllers\Teacher\ScheduleController::class, 'closeExam'])->name('.close');
    });

    Route::get('/analytics/personal', [App\Http\Controllers\AnalyticsController::class, 'personal'])->name('analytics.personal');
    Route::get('/analytics/class', [App\Http\Controllers\AnalyticsController::class, 'class'])->name('analytics.class');

    // AI / Rules
    Route::prefix('teacher/ai')->name('teacher.ai')->group(function () {
        Route::get('/generator', [App\Http\Controllers\Teacher\AIQuestionGeneratorController::class, 'index'])->name('.generator');
        Route::post('/generator/generate', [App\Http\Controllers\Teacher\AIQuestionGeneratorController::class, 'generate'])->name('.generator.generate');
        Route::post('/generator/import', [App\Http\Controllers\Teacher\AIQuestionGeneratorController::class, 'import'])->name('.generator.import');
    });

    Route::get('/teacher/ai/essay-grading', function () {
        return view('teacher.ai.essay_grading');
    })->name('teacher.ai.essay_grading');

    // Admin
    Route::prefix('admin/users')->name('admin.users')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('.index');
        Route::get('/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('.create');
        Route::post('/', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('.store');
        Route::get('/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('.edit');
        Route::put('/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('.update');
        Route::post('/bulk-disable', [App\Http\Controllers\Admin\UserController::class, 'disable'])->name('.bulk-disable');
        Route::post('/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('.toggle-status');
    });
    
    Route::get('/admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users');

    Route::get('/admin/security', function () {
        return view('admin.security.index');
    })->name('admin.security');

    // Teacher dashboard
    Route::get('/teacher', function () {
        return view('teacher.dashboard');
    })->name('teacher.dashboard');
});
