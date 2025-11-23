<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Chấm thi: ') . $exam->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white border border-gray-200 sm:rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $exam->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $exam->subject }} - {{ $exam->description }}</p>
                    </div>
                    <a href="{{ route('teacher.grading.index') }}" class="text-sm underline text-gray-700 hover:text-black">
                        Quay lại
                    </a>
                </div>
            </div>

            <div class="bg-white border border-gray-200 sm:rounded-xl overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Học sinh</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Thời gian nộp</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Trắc nghiệm</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tự luận</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tổng điểm</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($submissions as $submission)
                            <tr>
                                <td class="px-3 py-2 text-gray-900">{{ $submission->name }}</td>
                                <td class="px-3 py-2 text-gray-700">
                                    {{ $submission->submitted_at ? \Carbon\Carbon::parse($submission->submitted_at)->format('d/m/Y H:i') : 'Chưa nộp' }}
                                </td>
                                <td class="px-3 py-2 text-gray-700">
                                    @php
                                        $mcqAnswers = $submission->answers->filter(fn($a) => in_array($a->question->type, ['multiple_choice', 'true_false']));
                                        $mcqScore = $mcqAnswers->sum('points_earned');
                                        $mcqMax = $mcqAnswers->sum(fn($a) => $a->question->pivot->points ?? 1);
                                    @endphp
                                    {{ $mcqScore }}/{{ $mcqMax }}
                                </td>
                                <td class="px-3 py-2 text-gray-700">
                                    @php
                                        $essayAnswers = $submission->answers->filter(fn($a) => $a->question->type === 'essay');
                                        $essayScore = $essayAnswers->sum('points_earned');
                                        $essayMax = $essayAnswers->sum(fn($a) => $a->question->pivot->points ?? 10);
                                    @endphp
                                    @if($essayMax > 0)
                                        {{ $essayScore }}/{{ $essayMax }}
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 font-semibold text-gray-900">
                                    {{ $submission->total_score }}/{{ $submission->max_score }}
                                </td>
                                <td class="px-3 py-2 text-right">
                                    <button 
                                        hx-get="{{ route('teacher.grading.show', $exam) }}?user_id={{ $submission->id }}"
                                        hx-target="#review-panel"
                                        hx-swap="innerHTML"
                                        class="text-xs underline text-gray-700 hover:text-black">
                                        Chấm bài
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-4 text-center text-gray-500">Chưa có bài nộp nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="review-panel" class="hidden bg-white border border-gray-200 sm:rounded-xl p-6">
            </div>
        </div>
    </div>
</x-app-layout>

