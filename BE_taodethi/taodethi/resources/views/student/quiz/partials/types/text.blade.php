@php
    $questionId = $question->id;
@endphp

<input 
    type="text" 
    name="q_{{ $questionId }}" 
    value="{{ $savedValue ?? '' }}"
    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black" 
    placeholder="Nhập câu trả lời..."
>

