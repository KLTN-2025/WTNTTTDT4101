<button 
    hx-post="{{ route('teacher.questions.toggle-flag', $question) }}"
    hx-target="closest td"
    hx-swap="innerHTML"
    class="text-xs underline text-gray-700 hover:text-black">
    {{ $question->is_flagged ? 'Bỏ đánh dấu lỗi' : 'Đánh dấu lỗi' }}
</button>

