<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Bảng điều khiển học sinh') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Auth::user()->role === 'student')
                <!-- Nút làm bài kiểm tra nổi bật -->
                <div class="mb-6 bg-gradient-to-r from-black to-gray-800 border border-gray-200 sm:rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-semibold mb-2">Sẵn sàng làm bài kiểm tra?</h3>
                            <p class="text-sm text-gray-300">Xem và làm các bài kiểm tra đã được giao</p>
                        </div>
                        <a href="{{ route('exam') }}" class="px-6 py-3 bg-white text-black rounded-lg font-semibold hover:bg-gray-100 transition">
                            Làm bài kiểm tra →
                        </a>
                    </div>
                </div>
            @endif
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Trạng thái bài kiểm tra -->
                <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Trạng thái bài kiểm tra</h3>
                        <a href="{{ route('exam') }}" class="text-xs underline text-gray-600 hover:text-black">Làm bài kiểm tra</a>
                    </div>
                    <ul class="divide-y divide-gray-100">
                        @forelse($exams as $exam)
                        <li class="py-3 flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $exam->subject }} - {{ $exam->title }}</p>
                                    <p class="text-xs text-gray-600">
                                       @php
    $pivotStatus = $exam->pivot->status ?? 'not_started';
    $submittedAt = $exam->pivot->submitted_at ?? null;

    $statusConfig = [
        'not_started' => ['text' => 'Sắp diễn ra', 'class' => 'border-black text-black'],
        'in_progress' => ['text' => 'Đang làm', 'class' => 'border-gray-400 text-gray-700'],
        'submitted' => ['text' => 'Đã nộp', 'class' => 'border-gray-400 text-gray-700'],
        'graded' => ['text' => 'Đã chấm', 'class' => 'border-gray-400 text-gray-700'],
    ];

    $status = $statusConfig[$pivotStatus] ?? $statusConfig['not_started'];
    $dueDate = \Carbon\Carbon::parse($exam->due_date);
    $submittedAt = $submittedAt ? \Carbon\Carbon::parse($submittedAt) : null;
@endphp

                                    </p>
                            </div>
                                <div class="flex items-center gap-2">
                                    @php
                                        $statusConfig = [
                                            'not_started' => ['text' => 'Sắp diễn ra', 'class' => 'border-black text-black'],
                                            'in_progress' => ['text' => 'Đang làm', 'class' => 'border-gray-400 text-gray-700'],
                                            'submitted' => ['text' => 'Đã nộp', 'class' => 'border-gray-400 text-gray-700'],
                                            'graded' => ['text' => 'Đã chấm', 'class' => 'border-gray-400 text-gray-700'],
                                        ];
                                        $status = $statusConfig[$pivotStatus] ?? $statusConfig['not_started'];
                                    @endphp
                                    <span class="text-xs px-2 py-1 border {{ $status['class'] }} rounded-md">{{ $status['text'] }}</span>
                                    @if($pivotStatus === 'not_started' || $pivotStatus === 'in_progress')
                                        <a href="{{ route('exam', ['exam_id' => $exam->id]) }}" class="text-xs px-2 py-1 bg-black text-white rounded-md hover:bg-gray-800">
                                            {{ $pivotStatus === 'not_started' ? 'Bắt đầu' : 'Tiếp tục' }}
                                        </a>
                                    @endif
                            </div>
                        </li>
                        @empty
                            <li class="py-3 text-sm text-gray-500 text-center">
                                <p class="mb-2">Chưa có bài kiểm tra nào</p>
                                <a href="{{ route('exam') }}" class="text-xs underline text-gray-600 hover:text-black">Xem tất cả bài kiểm tra</a>
                        </li>
                        @endforelse
                    </ul>
                </div>

                <!-- Tiến trình học -->
                <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Tiến trình học</h3>
                        <a href="#" class="text-xs underline text-gray-600 hover:text-black">Chi tiết</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($studyProgress as $progress)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                    <p class="text-sm text-gray-800">{{ $progress->subject }}</p>
                                    <span class="text-xs text-gray-600">{{ $progress->progress_percentage }}%</span>
                            </div>
                            <div class="h-2 bg-gray-100 rounded">
                                    <div class="h-2 bg-black rounded" style="width:{{ $progress->progress_percentage }}%"></div>
                        </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500 text-center py-4">Chưa có dữ liệu tiến trình học</div>
                        @endforelse
                    </div>
                </div>

                <!-- Điểm gần nhất -->
                <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Điểm gần nhất</h3>
                        <a href="#" class="text-xs underline text-gray-600 hover:text-black">Bảng điểm</a>
                    </div>
                    <div class="overflow-hidden border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Môn</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Bài</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Điểm</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($recentScores as $score)
                                <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $score->subject }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">{{ $score->test_name }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 text-right font-semibold">{{ number_format($score->score, 1) }}</td>
                                </tr>
                                @empty
                                <tr>
                                        <td colspan="3" class="px-4 py-2 text-sm text-gray-500 text-center">Chưa có điểm số nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Gợi ý học -->
            <div class="mt-6 bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Gợi ý học</h3>
                    <button 
                        hx-get="{{ route('dashboard.refresh-suggestions') }}"
                        hx-target="#study-suggestions-list"
                        hx-swap="innerHTML"
                        hx-indicator="#loading-suggestions"
                        class="text-xs underline text-gray-600 hover:text-black flex items-center gap-1">
                        <span id="loading-suggestions" class="htmx-indicator">
                            <svg class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        Làm mới
                    </button>
                </div>
                <ul id="study-suggestions-list" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($studySuggestions as $suggestion)
                    <li class="border border-gray-200 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-900">{{ $suggestion->title }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $suggestion->description }}</p>
                    </li>
                    @empty
                        <li class="col-span-2 text-sm text-gray-500 text-center py-4">Chưa có gợi ý học tập nào</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
