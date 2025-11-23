<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Chi tiết bài thi') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $exam->title }}</h1>
                <div class="space-y-2 text-sm text-gray-700">
                    <p><strong>Môn học:</strong> {{ $exam->subject }}</p>
                    @if($exam->description)
                        <p><strong>Mô tả:</strong> {{ $exam->description }}</p>
                    @endif
                    <p><strong>Số câu hỏi:</strong> {{ $exam->questions->count() }}</p>
                    @if($exam->due_date)
                        <p><strong>Hạn nộp:</strong> {{ $exam->due_date->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
            </div>

            @if($isSubmitted)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <p class="text-green-800">Bạn đã nộp bài thi này. <a href="{{ route('student.quiz.result', $exam) }}" class="underline font-semibold">Xem kết quả</a></p>
                </div>
            @else
                @if($session)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <p class="text-blue-800">Bạn đã bắt đầu làm bài. <a href="{{ route('student.quiz.take', $exam) }}" class="underline font-semibold">Tiếp tục làm bài</a></p>
                    </div>
                @else
                    <form action="{{ route('student.quiz.start', $exam) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-white hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">
                            Bắt đầu làm bài
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>

