@php
    $questionId = $question->id;
    $shuffledOptions = $options;
    if (is_array($shuffledOptions)) {
        shuffle($shuffledOptions);
    }
@endphp

<div class="space-y-2 options">
    @if(is_array($shuffledOptions))
        @foreach($shuffledOptions as $key => $option)
            <label class="flex items-center gap-3 text-sm text-gray-800 option cursor-pointer hover:bg-gray-50 p-2 rounded">
                <input 
                    type="radio" 
                    name="q_{{ $questionId }}" 
                    value="{{ $key }}" 
                    class="border-gray-400"
                    {{ $savedValue == $key ? 'checked' : '' }}
                > 
                <span>{{ $option }}</span>
            </label>
        @endforeach
    @endif
</div>

