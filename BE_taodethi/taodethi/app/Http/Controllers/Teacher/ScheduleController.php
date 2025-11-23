<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\ExamTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = ExamSchedule::with(['examTemplate', 'exam', 'creator'])
            ->where('created_by', Auth::id());

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $schedules = $query->latest('start_time')->paginate(15);

        if ($request->header('HX-Request')) {
            return view('teacher.schedules.partials.schedule-list', compact('schedules'));
        }

        return view('teacher.schedules.index', compact('schedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_template_id' => 'nullable|exists:exam_templates,id',
            'exam_id' => 'nullable|exists:exams,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'duration_minutes' => 'required|integer|min:1',
            'max_attempts' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_active'] = $validated['is_active'] ?? true;

        $schedule = ExamSchedule::create($validated);

        if ($request->header('HX-Request')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã tạo lịch thi thành công',
                'schedule' => $schedule,
            ]);
        }

        return redirect()->route('teacher.schedules.index')->with('success', 'Đã tạo lịch thi thành công');
    }

    public function show(ExamSchedule $schedule)
    {
        $schedule->load(['examTemplate', 'exam', 'creator']);

        if (request()->header('HX-Request')) {
            return view('teacher.schedules.partials.schedule-detail', compact('schedule'));
        }

        return view('teacher.schedules.show', compact('schedule'));
    }

    public function update(ExamSchedule $schedule, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration_minutes' => 'required|integer|min:1',
            'max_attempts' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $schedule->update($validated);

        if ($request->header('HX-Request')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật lịch thi thành công',
            ]);
        }

        return redirect()->back()->with('success', 'Đã cập nhật lịch thi thành công');
    }

    public function destroy(ExamSchedule $schedule)
    {
        $schedule->delete();

        if (request()->header('HX-Request')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa lịch thi thành công',
            ]);
        }

        return redirect()->route('teacher.schedules.index')->with('success', 'Đã xóa lịch thi thành công');
    }

    public function toggleActive(ExamSchedule $schedule)
    {
        $schedule->update([
            'is_active' => !$schedule->is_active,
        ]);

        if (request()->header('HX-Request')) {
            return response()->json([
                'success' => true,
                'message' => $schedule->is_active ? 'Đã mở đề thi' : 'Đã đóng đề thi',
                'is_active' => $schedule->is_active,
            ]);
        }

        return redirect()->back()->with('success', $schedule->is_active ? 'Đã mở đề thi' : 'Đã đóng đề thi');
    }

    public function openExam(ExamSchedule $schedule)
    {
        $schedule->update(['is_active' => true]);

        if (request()->header('HX-Request')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã mở đề thi thành công',
            ]);
        }

        return redirect()->back()->with('success', 'Đã mở đề thi thành công');
    }

    public function closeExam(ExamSchedule $schedule)
    {
        $schedule->update(['is_active' => false]);

        if (request()->header('HX-Request')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã đóng đề thi thành công',
            ]);
        }

        return redirect()->back()->with('success', 'Đã đóng đề thi thành công');
    }
}
