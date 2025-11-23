<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('modal') && $request->modal === 'create') {
            if ($request->header('HX-Request')) {
                return view('teacher.classes.partials.create-form');
            }
        }

        $query = Classroom::with(['teacher', 'students'])
            ->where('teacher_id', Auth::id());

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        $classes = $query->latest()->paginate(15);

        if ($request->header('HX-Request')) {
            return view('teacher.classes.partials.class-list', compact('classes'));
        }

        return view('teacher.classes.index', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:classes,code',
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:100',
            'academic_year' => 'nullable|string|max:50',
        ]);

        $validated['code'] = $validated['code'] ?? Str::upper(Str::random(8));
        $validated['teacher_id'] = Auth::id();

        $class = Classroom::create($validated);

        if ($request->header('HX-Request')) {
            $query = Classroom::with(['teacher', 'students'])
                ->where('teacher_id', Auth::id());
            
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                      ->orWhere('code', 'like', "%{$request->search}%");
                });
            }
            
            $classes = $query->latest()->paginate(15);
            
            return response('<div id="success-trigger" hx-get="' . route('teacher.classes.index') . '?' . http_build_query($request->only(['search'])) . '" hx-target="#class-list" hx-swap="innerHTML" hx-trigger="load" hx-headers=\'{"HX-Request": "true"}\'></div><script>document.getElementById("modal").classList.add("hidden"); document.getElementById("modal").classList.remove("flex");</script>');
        }

        return redirect()->route('teacher.classes.index')->with('success', 'Đã tạo lớp học thành công');
    }

    public function show(Classroom $class, Request $request)
    {
        $class->load(['teacher', 'users']);

        if ($request->has('modal') && $request->modal === 'add-student') {
            if ($request->header('HX-Request')) {
                $students = User::where('role', 'student')->get();
                return view('teacher.classes.partials.add-student-form', compact('class', 'students'));
            }
        }

        if ($request->header('HX-Request')) {
            $class->load(['teacher', 'users']);
            return view('teacher.classes.partials.student-list', compact('class'));
        }

        $students = User::where('role', 'student')->get();
        return view('teacher.classes.show', compact('class', 'students'));
    }

    public function update(Classroom $class, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:classes,code,' . $class->id,
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:100',
            'academic_year' => 'nullable|string|max:50',
        ]);

        $class->update($validated);

        if ($request->header('HX-Request')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật lớp học thành công',
            ]);
        }

        return redirect()->back()->with('success', 'Đã cập nhật lớp học thành công');
    }

    public function destroy(Classroom $class)
    {
        $class->delete();

        if (request()->header('HX-Request')) {
            return response('', 200);
        }

        return redirect()->route('teacher.classes.index')->with('success', 'Đã xóa lớp học thành công');
    }

    public function addStudent(Classroom $class, Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'student_code' => 'nullable|string|max:50',
            'role' => 'nullable|in:student,assistant_teacher',
        ]);

        $validated['role'] = $validated['role'] ?? 'student';

        $class->users()->syncWithoutDetaching([
            $validated['user_id'] => [
                'role' => $validated['role'],
                'student_code' => $validated['student_code'],
            ]
        ]);

        if ($request->header('HX-Request')) {
            $class->load(['users']);
            return response('<div id="success-trigger" hx-get="' . route('teacher.classes.show', $class) . '" hx-target="#student-list" hx-swap="innerHTML" hx-trigger="load" hx-headers=\'{"HX-Request": "true"}\'></div><script>document.getElementById("modal").classList.add("hidden"); document.getElementById("modal").classList.remove("flex");</script>');
        }

        return redirect()->back()->with('success', 'Đã thêm học sinh vào lớp thành công');
    }

    public function removeStudent(Classroom $class, User $user)
    {
        $class->users()->detach($user->id);

        if (request()->header('HX-Request')) {
            return response('', 200);
        }

        return redirect()->back()->with('success', 'Đã xóa học sinh khỏi lớp thành công');
    }

    public function importStudents(Classroom $class, Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $lines = file($file->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        $imported = 0;
        $errors = [];

        foreach ($lines as $index => $line) {
            if ($index === 0) continue;

            $data = str_getcsv($line);
            
            if (count($data) < 2) {
                $errors[] = "Dòng " . ($index + 1) . ": Dữ liệu không đúng định dạng";
                continue;
            }

            $email = trim($data[0]);
            $studentCode = trim($data[1] ?? '');

            $user = User::where('email', $email)->first();

            if (!$user) {
                $errors[] = "Dòng " . ($index + 1) . ": Không tìm thấy người dùng với email: {$email}";
                continue;
            }

            $class->users()->syncWithoutDetaching([
                $user->id => [
                    'role' => 'student',
                    'student_code' => $studentCode,
                ]
            ]);

            $imported++;
        }

        if ($request->header('HX-Request')) {
            $class->load(['users']);
            $message = "Đã import {$imported} học sinh thành công";
            if (!empty($errors)) {
                $message .= ". Có " . count($errors) . " lỗi.";
            }
            return response('<div id="success-trigger" hx-get="' . route('teacher.classes.show', $class) . '" hx-target="#student-list" hx-swap="innerHTML" hx-trigger="load" hx-headers=\'{"HX-Request": "true"}\'></div><script>alert("' . $message . '");</script>');
        }

        return redirect()->back()->with([
            'success' => "Đã import {$imported} học sinh thành công",
            'errors' => $errors,
        ]);
    }

    public function updateStudentRole(Classroom $class, User $user, Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|in:student,assistant_teacher',
        ]);

        $class->users()->updateExistingPivot($user->id, [
            'role' => $validated['role'],
        ]);

        if ($request->header('HX-Request')) {
            $class->load(['users']);
            return response('<div id="success-trigger" hx-get="' . route('teacher.classes.show', $class) . '" hx-target="#student-list" hx-swap="innerHTML" hx-trigger="load" hx-headers=\'{"HX-Request": "true"}\'></div>');
        }

        return redirect()->back()->with('success', 'Đã cập nhật quyền thành công');
    }
}
