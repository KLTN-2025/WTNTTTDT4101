<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Tag;
use App\Models\Difficulty;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions.
     */
    public function index(Request $request)
    {
        $query = Question::with(['difficulty', 'tags', 'skills', 'creator']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by tags
        if ($request->filled('tags')) {
            $tagIds = is_array($request->tags) ? $request->tags : explode(',', $request->tags);
            $query->whereHas('tags', function($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            });
        }

        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->whereHas('difficulty', function($q) use ($request) {
                $q->where('slug', $request->difficulty);
            });
        }

        // Filter by skill
        if ($request->filled('skill')) {
            $query->whereHas('skills', function($q) use ($request) {
                $q->where('slug', $request->skill);
            });
        }

        // Filter by type
        if ($request->filled('qtype')) {
            $query->where('type', $request->qtype);
        }

        // Filter by flagged
        if ($request->filled('flagged')) {
            $query->where('is_flagged', $request->flagged === 'true');
        }

        $questions = $query->latest()->paginate(20);

        $tags = Tag::all();
        $difficulties = Difficulty::all();
        $skills = Skill::all();

        if ($request->wantsJson() || $request->header('HX-Request')) {
            return view('teacher.questions.partials.table', compact('questions'));
        }

        return view('teacher.questions.index', compact('questions', 'tags', 'difficulties', 'skills'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create()
    {
        $tags = Tag::all();
        $difficulties = Difficulty::all();
        $skills = Skill::all();

        return view('teacher.questions.create', compact('tags', 'difficulties', 'skills'));
    }

    /**
     * Store a newly created question.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'required|in:single,multi,boolean,text,order,match,essay',
            'difficulty_id' => 'nullable|exists:difficulties,id',
            'options' => 'nullable|array',
            'correct_answer' => 'nullable',
            'explanation' => 'nullable|string',
            'image_url' => 'nullable|url',
            'audio_url' => 'nullable|url',
            'video_url' => 'nullable|url',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
            'skill_matrix' => 'nullable|array',
        ]);

        // Parse options if it's a JSON string
        $options = $validated['options'] ?? null;
        if (is_string($options)) {
            $options = json_decode($options, true);
        }

        // Create question
        $question = Question::create([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'type' => $validated['type'],
            'difficulty_id' => $validated['difficulty_id'] ?? null,
            'options' => $options,
            'correct_answer' => $this->formatCorrectAnswer($validated['correct_answer'] ?? null, $validated['type']),
            'explanation' => $validated['explanation'] ?? null,
            'image_url' => $validated['image_url'] ?? null,
            'audio_url' => $validated['audio_url'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        // Handle tags - create if needed
        $tagIds = [];
        if (isset($validated['tags'])) {
            $tagIds = $validated['tags'];
        }
        if (isset($validated['tag_names'])) {
            foreach ($validated['tag_names'] as $tagName) {
                $tag = Tag::firstOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
        }
        if (!empty($tagIds)) {
            $question->tags()->attach(array_unique($tagIds));
        }

        // Attach skills
        if (isset($validated['skills'])) {
            $question->skills()->attach($validated['skills']);
        }

        // Create skill matrix entries
        if (isset($validated['skill_matrix'])) {
            foreach ($validated['skill_matrix'] as $skillId => $matrix) {
                $question->skillMatrix()->attach($skillId, [
                    'weight' => $matrix['weight'] ?? 1,
                    'level' => $matrix['level'] ?? 1,
                    'notes' => $matrix['notes'] ?? null,
                ]);
            }
        }

        // Create initial version
        $question->versions()->create([
            'version_number' => 1,
            'data' => $question->toArray(),
            'change_note' => 'Tạo mới câu hỏi',
            'created_by' => Auth::id(),
        ]);

        if ($request->header('HX-Request')) {
            return response()->view('teacher.questions.partials.table-row', ['question' => $question->load(['difficulty', 'tags', 'skills'])], 201)
                ->header('HX-Trigger', 'questionCreated');
        }

        return redirect()->route('teacher.questions')->with('success', 'Câu hỏi đã được tạo thành công.');
    }

    /**
     * Display the specified question.
     */
    public function show(Question $question)
    {
        $question->load(['difficulty', 'tags', 'skills', 'skillMatrix', 'creator', 'updater']);
        return view('teacher.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the question.
     */
    public function edit(Question $question)
    {
        $tags = Tag::all();
        $difficulties = Difficulty::all();
        $skills = Skill::all();
        $question->load(['tags', 'skills', 'skillMatrix']);

        return view('teacher.questions.edit', compact('question', 'tags', 'difficulties', 'skills'));
    }

    /**
     * Update the specified question.
     */
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'required|in:single,multi,boolean,text,order,match,essay',
            'difficulty_id' => 'nullable|exists:difficulties,id',
            'options' => 'nullable|array',
            'correct_answer' => 'nullable',
            'explanation' => 'nullable|string',
            'image_url' => 'nullable|url',
            'audio_url' => 'nullable|url',
            'video_url' => 'nullable|url',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
            'skill_matrix' => 'nullable|array',
        ]);

        // Save current version before update
        $latestVersion = $question->versions()->latest()->first();
        $versionNumber = $latestVersion ? $latestVersion->version_number + 1 : 1;

        $question->versions()->create([
            'version_number' => $versionNumber,
            'data' => $question->toArray(),
            'change_note' => $request->change_note ?? 'Cập nhật câu hỏi',
            'created_by' => Auth::id(),
        ]);

        // Update question
        $question->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'type' => $validated['type'],
            'difficulty_id' => $validated['difficulty_id'] ?? null,
            'options' => $validated['options'] ?? null,
            'correct_answer' => $this->formatCorrectAnswer($validated['correct_answer'] ?? null, $validated['type']),
            'explanation' => $validated['explanation'] ?? null,
            'image_url' => $validated['image_url'] ?? null,
            'audio_url' => $validated['audio_url'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'updated_by' => Auth::id(),
        ]);

        // Sync tags
        if (isset($validated['tags'])) {
            $question->tags()->sync($validated['tags']);
        } else {
            $question->tags()->detach();
        }

        // Sync skills
        if (isset($validated['skills'])) {
            $question->skills()->sync($validated['skills']);
        } else {
            $question->skills()->detach();
        }

        // Sync skill matrix
        $question->skillMatrix()->detach();
        if (isset($validated['skill_matrix'])) {
            foreach ($validated['skill_matrix'] as $skillId => $matrix) {
                $question->skillMatrix()->attach($skillId, [
                    'weight' => $matrix['weight'] ?? 1,
                    'level' => $matrix['level'] ?? 1,
                    'notes' => $matrix['notes'] ?? null,
                ]);
            }
        }

        if ($request->header('HX-Request')) {
            return response()->view('teacher.questions.partials.table-row', ['question' => $question->load(['difficulty', 'tags', 'skills'])])
                ->header('HX-Trigger', 'questionUpdated');
        }

        return redirect()->route('teacher.questions')->with('success', 'Câu hỏi đã được cập nhật thành công.');
    }

    /**
     * Remove the specified question.
     */
    public function destroy(Question $question)
    {
        $question->delete();

        if (request()->header('HX-Request')) {
            return response()->header('HX-Trigger', 'questionDeleted');
        }

        return redirect()->route('teacher.questions')->with('success', 'Câu hỏi đã được xóa thành công.');
    }

    /**
     * Toggle flagged status.
     */
    public function toggleFlag(Question $question)
    {
        $question->update(['is_flagged' => !$question->is_flagged]);

        if (request()->header('HX-Request')) {
            return response()->view('teacher.questions.partials.flag-button', ['question' => $question]);
        }

        return response()->json(['is_flagged' => $question->is_flagged]);
    }

    /**
     * Get question versions/history.
     */
    public function history(Question $question)
    {
        $versions = $question->versions()->with('creator')->latest()->get();
        return view('teacher.questions.partials.history', compact('question', 'versions'));
    }

    /**
     * Format correct answer based on question type.
     */
    private function formatCorrectAnswer($answer, $type)
    {
        if (empty($answer)) {
            return null;
        }

        switch ($type) {
            case 'single':
            case 'boolean':
                return is_array($answer) ? $answer[0] : $answer;
            case 'multi':
                return is_array($answer) ? $answer : [$answer];
            case 'text':
                return is_array($answer) ? $answer : explode('|', $answer);
            case 'order':
            case 'match':
                return is_array($answer) ? $answer : json_decode($answer, true);
            default:
                return $answer;
        }
    }
}

