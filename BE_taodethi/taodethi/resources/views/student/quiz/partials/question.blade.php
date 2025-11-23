@php
    $questionId = $question->id;
    $questionType = $question->type;
    $options = $question->options ?? [];
    $savedValue = $savedAnswer;
@endphp

<div class="bg-white border border-gray-200 sm:rounded-xl p-6 question" 
     data-question-id="{{ $questionId }}" 
     data-question-type="{{ $questionType }}">
    
    <h3 class="text-base font-semibold text-gray-900 mb-3">
        Câu {{ $index }}: 
        @if($questionType === 'single')
            Trắc nghiệm 1 đáp án
        @elseif($questionType === 'multi')
            Nhiều đáp án
        @elseif($questionType === 'boolean')
            Đúng/Sai
        @elseif($questionType === 'text')
            Điền từ
        @elseif($questionType === 'order')
            Sắp xếp
        @elseif($questionType === 'match')
            Ghép cặp
        @elseif($questionType === 'essay')
            Tự luận
        @endif
    </h3>

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

    @if($question->audio_url)
        <div class="mb-4">
            <audio controls class="w-full">
                <source src="{{ $question->audio_url }}" type="audio/mpeg">
                Trình duyệt của bạn không hỗ trợ audio.
            </audio>
        </div>
    @endif

    @if($question->video_url)
        <div class="mb-4">
            <video controls class="w-full max-h-96 bg-black rounded-lg">
                <source src="{{ $question->video_url }}" type="video/mp4">
                Trình duyệt của bạn không hỗ trợ video.
            </video>
        </div>
    @endif

    @if($questionType === 'single')
        @include('student.quiz.partials.types.single', ['question' => $question, 'options' => $options, 'savedValue' => $savedValue])
    @elseif($questionType === 'multi')
        @include('student.quiz.partials.types.multi', ['question' => $question, 'options' => $options, 'savedValue' => $savedValue])
    @elseif($questionType === 'boolean')
        @include('student.quiz.partials.types.boolean', ['question' => $question, 'savedValue' => $savedValue])
    @elseif($questionType === 'text')
        @include('student.quiz.partials.types.text', ['question' => $question, 'savedValue' => $savedValue])
    @elseif($questionType === 'order')
        @include('student.quiz.partials.types.order', ['question' => $question, 'options' => $options, 'savedValue' => $savedValue])
    @elseif($questionType === 'match')
        @include('student.quiz.partials.types.match', ['question' => $question, 'options' => $options, 'savedValue' => $savedValue])
    @elseif($questionType === 'essay')
        @include('student.quiz.partials.types.essay', ['question' => $question, 'savedValue' => $savedValue])
    @endif

    <div class="mt-4">
        <button type="button" class="text-xs underline text-gray-600 hover:text-black feedback-toggle" data-question-id="{{ $questionId }}">
            Ghi chú / Báo lỗi
        </button>
        <div class="mt-2 hidden feedback-box" data-question-id="{{ $questionId }}">
            <form class="feedback-form" data-question-id="{{ $questionId }}" data-exam-id="{{ $exam->id }}">
                @csrf
                <select name="type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mb-2">
                    <option value="note">Ghi chú</option>
                    <option value="error">Báo lỗi</option>
                    <option value="clarification">Xin giải thích</option>
                </select>
                <textarea name="message" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" rows="2" placeholder="Mô tả vấn đề hoặc thắc mắc..." required></textarea>
                <div class="mt-2 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">
                        Gửi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.feedback-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const qId = btn.getAttribute('data-question-id');
            const box = document.querySelector(`.feedback-box[data-question-id="${qId}"]`);
            if (box) box.classList.toggle('hidden');
        });
    });

    document.querySelectorAll('.feedback-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const qId = form.getAttribute('data-question-id');
            const examId = form.getAttribute('data-exam-id');
            
            try {
                const response = await fetch(`/student/quiz/${examId}/feedback/${qId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'HX-Request': 'true'
                    }
                });
                
                if (response.ok) {
                    const box = document.querySelector(`.feedback-box[data-question-id="${qId}"]`);
                    if (box) {
                        box.innerHTML = '<span class="text-xs text-green-600">Đã gửi phản hồi</span>';
                        setTimeout(() => box.classList.add('hidden'), 2000);
                    }
                }
            } catch (error) {
                alert('Có lỗi xảy ra khi gửi phản hồi');
            }
        });
    });
</script>

