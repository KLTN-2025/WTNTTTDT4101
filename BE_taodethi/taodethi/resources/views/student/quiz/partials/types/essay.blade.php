@php
    $questionId = $question->id;
@endphp

<textarea 
    name="q_{{ $questionId }}" 
    rows="8" 
    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black" 
    placeholder="Nhập bài làm của bạn..."
>{{ $savedValue ?? '' }}</textarea>

