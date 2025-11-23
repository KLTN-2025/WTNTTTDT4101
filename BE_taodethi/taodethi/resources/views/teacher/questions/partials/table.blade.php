@forelse($questions as $question)
    <tr>
        <td class="px-4 py-2 text-sm text-gray-900">
            {{ $question->title }}
            @if($question->is_flagged)
                <span class="ml-2 text-xs px-2 py-0.5 border border-black rounded">Lỗi</span>
            @endif
        </td>
        <td class="px-4 py-2 text-sm text-gray-700">{{ $question->type }}</td>
        <td class="px-4 py-2 text-sm text-gray-700">{{ $question->difficulty->name ?? '-' }}</td>
        <td class="px-4 py-2 text-sm text-gray-700">
            {{ $question->tags->pluck('name')->join(', ') ?: '-' }}
        </td>
        <td class="px-4 py-2 text-sm text-gray-700">
            {{ $question->skills->pluck('name')->join(', ') ?: '-' }}
        </td>
        <td class="px-4 py-2 text-sm text-gray-700">
            @include('teacher.questions.partials.flag-button', ['question' => $question])
        </td>
        <td class="px-4 py-2 text-sm text-gray-700 text-right">
            <button 
                hx-get="{{ route('teacher.questions.history', $question) }}"
                hx-target="#history-modal-content"
                hx-swap="innerHTML"
                onclick="document.getElementById('history-modal').classList.remove('hidden'); document.getElementById('history-modal').classList.add('flex');"
                class="text-xs underline text-gray-700 hover:text-black mr-3">
                Lịch sử
            </button>
            <button 
                onclick="openEditModal({{ $question->id }})"
                class="text-xs underline text-gray-700 hover:text-black mr-3">
                Chỉnh sửa
            </button>
            <button 
                hx-delete="{{ route('teacher.questions.destroy', $question) }}"
                hx-confirm="Xoá câu hỏi này?"
                hx-target="closest tr"
                hx-swap="outerHTML"
                class="text-xs underline text-gray-700 hover:text-black">
                Xoá
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-4 py-8 text-sm text-gray-500 text-center">
            Chưa có câu hỏi nào. Hãy tạo câu hỏi đầu tiên!
        </td>
    </tr>
@endforelse

