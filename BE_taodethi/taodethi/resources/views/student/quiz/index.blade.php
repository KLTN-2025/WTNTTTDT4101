<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Danh sách bài thi') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="exam-list-container">
                @include('student.quiz.partials.exam-list', ['exams' => $exams])
            </div>
        </div>
    </div>
</x-app-layout>

