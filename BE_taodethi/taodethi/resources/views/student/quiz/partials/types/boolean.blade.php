@php
    $questionId = $question->id;
@endphp

<div class="flex items-center gap-6">
    <label class="flex items-center gap-2 text-sm text-gray-800 cursor-pointer hover:bg-gray-50 p-2 rounded">
        <input 
            type="radio" 
            name="q_{{ $questionId }}" 
            value="true" 
            class="border-gray-400"
            {{ $savedValue === 'true' || $savedValue === true ? 'checked' : '' }}
        > 
        Đúng
    </label>
    <label class="flex items-center gap-2 text-sm text-gray-800 cursor-pointer hover:bg-gray-50 p-2 rounded">
        <input 
            type="radio" 
            name="q_{{ $questionId }}" 
            value="false" 
            class="border-gray-400"
            {{ $savedValue === 'false' || $savedValue === false ? 'checked' : '' }}
        > 
        Sai
    </label>
</div>

