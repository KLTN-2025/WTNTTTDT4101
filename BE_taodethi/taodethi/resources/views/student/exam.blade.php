<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Làm bài trắc nghiệm') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Danh sách bài thi</h1>
                <p class="text-sm text-gray-600 mb-6">Chọn bài thi để bắt đầu làm bài</p>
            </div>

            <div id="exam-list-container">
                @include('student.quiz.partials.exam-list', ['exams' => $exams])
            </div>
        </div>
    </div>

</x-app-layout>


