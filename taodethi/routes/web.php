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
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/exam', function () {
        return view('student.exam');
    })->name('exam');

    Route::get('/teacher/questions', function () {
        return view('teacher.questions.index');
    })->name('teacher.questions');

    Route::get('/teacher/exams', function () {
        return view('teacher.exams.index');
    })->name('teacher.exams');

    Route::get('/teacher/grading', function () {
        return view('teacher.grading.index');
    })->name('teacher.grading');

    Route::get('/teacher/classes', function () {
        return view('teacher.classes.index');
    })->name('teacher.classes');

    Route::get('/teacher/schedules', function () {
        return view('teacher.schedules.index');
    })->name('teacher.schedules');

    Route::get('/analytics/personal', function () {
        return view('analytics.personal');
    })->name('analytics.personal');

    Route::get('/analytics/class', function () {
        return view('analytics.class');
    })->name('analytics.class');

    // AI / Rules
    Route::get('/teacher/ai/generator', function () {
        return view('teacher.ai.generator');
    })->name('teacher.ai.generator');

    Route::get('/teacher/ai/essay-grading', function () {
        return view('teacher.ai.essay_grading');
    })->name('teacher.ai.essay_grading');

    // Admin
    Route::get('/admin/users', function () {
        return view('admin.users.index');
    })->name('admin.users');

    Route::get('/admin/security', function () {
        return view('admin.security.index');
    })->name('admin.security');

    // Teacher dashboard
    Route::get('/teacher', function () {
        return view('teacher.dashboard');
    })->name('teacher.dashboard');
});
