<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Kết quả bài thi: ') . $exam->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $exam->title }}</h1>
                <div class="space-y-2">
                    <p class="text-lg"><strong>Điểm số:</strong> <span class="text-2xl font-bold text-black">{{ number_format($score, 2) }}</span> / <span class="text-xl">{{ number_format($maxScore, 2) }}</span></p>
                    <p class="text-sm text-gray-600">Tỷ lệ: {{ $maxScore > 0 ? number_format(($score / $maxScore) * 100, 1) : 0 }}%</p>
                </div>
            </div>

            <div class="space-y-6">
                @foreach($exam->questions as $index => $question)
                    @php
                        $answer = $answers[$question->id] ?? null;
                        $isCorrect = $answer ? $answer->is_correct : false;
                        $pointsEarned = $answer ? $answer->points_earned : 0;
                        $points = $exam->questions->find($question->id)->pivot->points ?? 1;
                    @endphp
                    
                    <div class="bg-white border {{ $isCorrect ? 'border-green-200' : 'border-red-200' }} sm:rounded-xl p-6">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-base font-semibold text-gray-900">
                                Câu {{ $index + 1 }}: 
                                @if($isCorrect)
                                    <span class="text-green-600">Đúng (+{{ $pointsEarned }}/{{ $points }})</span>
                                @else
                                    <span class="text-red-600">Sai (0/{{ $points }})</span>
                                @endif
                            </h3>
                        </div>

                        @if($question->title)
                            <p class="text-sm font-medium text-gray-800 mb-2">{{ $question->title }}</p>
                        @endif

                        @if($question->content)
                            <div class="text-sm text-gray-700 mb-4 question-content">{!! $question->content !!}</div>
                        @endif

                        @if($question->image_url)
                            <div class="mb-4">
                                <img src="{{ $question->image_url }}" alt="Hình minh họa" class="max-w-full h-auto rounded-lg border border-gray-200">
                            </div>
                        @endif

                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-2">Câu trả lời của bạn:</p>
                            <p class="text-sm text-gray-800">
                                @if($answer)
                                    @if(is_array($answer->selected_options))
                                        {{ implode(', ', $answer->selected_options) }}
                                    @else
                                        {{ $answer->answer }}
                                    @endif
                                @else
                                    <span class="text-gray-400">Chưa trả lời</span>
                                @endif
                            </p>
                        </div>

                        @if($question->explanation)
                            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                <p class="text-xs text-blue-600 mb-2">Giải thích:</p>
                                <p class="text-sm text-blue-800">{!! $question->explanation !!}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('student.quiz.index') }}" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-white hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">
                    Quay lại danh sách
                </a>
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
</x-app-layout>

