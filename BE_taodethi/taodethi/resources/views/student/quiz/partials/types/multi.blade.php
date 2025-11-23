@php
    $questionId = $question->id;
    $shuffledOptions = $options;
    if (is_array($shuffledOptions)) {
        shuffle($shuffledOptions);
    }
    $savedArray = is_array($savedValue) ? $savedValue : [];
@endphp

<div class="space-y-2 options">
    @if(is_array($shuffledOptions))
        @foreach($shuffledOptions as $key => $option)
            <label class="flex items-center gap-3 text-sm text-gray-800 option cursor-pointer hover:bg-gray-50 p-2 rounded">
                <input 
                    type="checkbox" 
                    name="q_{{ $questionId }}[]" 
                    value="{{ $key }}" 
                    class="border-gray-400"
                    {{ in_array($key, $savedArray) ? 'checked' : '' }}
                > 
                <span>{{ $option }}</span>
            </label>
        @endforeach
    @endif
</div>

