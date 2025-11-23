<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Chỉnh sửa câu hỏi') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('teacher.questions.update', $question) }}" method="POST" id="question-form">
                @csrf
                @method('PUT')
                
                <div class="bg-white border border-gray-200 sm:rounded-xl p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-600 mb-1">Tiêu đề *</label>
                            <input 
                                id="f-title" 
                                name="title" 
                                type="text" 
                                required 
                                value="{{ old('title', $question->title) }}"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black" 
                                placeholder="Ví dụ: 2 + 2 = ?">
                            @error('title')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-600 mb-1">Nội dung (hỗ trợ MathJax)</label>
                            <textarea 
                                id="f-content" 
                                name="content" 
                                rows="3" 
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black" 
                                placeholder="Ví dụ: \(a^2 + b^2 = c^2\)">{{ old('content', $question->content) }}</textarea>
                            <p class="text-xs text-gray-600 mt-1">Xem trước công thức ngay bên dưới.</p>
                            <div id="content-preview" class="mt-2 p-3 border border-gray-200 rounded text-sm text-gray-900 bg-gray-50 min-h-[48px]">
                                {{ $question->content }}
                            </div>
                            @error('content')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Loại câu hỏi *</label>
                            <select 
                                id="f-type" 
                                name="type" 
                                required 
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                <option value="single" {{ $question->type === 'single' ? 'selected' : '' }}>Trắc nghiệm 1 đáp án</option>
                                <option value="multi" {{ $question->type === 'multi' ? 'selected' : '' }}>Nhiều đáp án</option>
                                <option value="boolean" {{ $question->type === 'boolean' ? 'selected' : '' }}>Đúng/Sai</option>
                                <option value="text" {{ $question->type === 'text' ? 'selected' : '' }}>Điền từ</option>
                                <option value="order" {{ $question->type === 'order' ? 'selected' : '' }}>Sắp xếp</option>
                                <option value="match" {{ $question->type === 'match' ? 'selected' : '' }}>Ghép cặp</option>
                                <option value="essay" {{ $question->type === 'essay' ? 'selected' : '' }}>Tự luận</option>
                            </select>
                            @error('type')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Độ khó</label>
                            <select 
                                id="f-difficulty" 
                                name="difficulty_id" 
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                <option value="">Chọn độ khó</option>
                                @foreach($difficulties as $diff)
                                    <option value="{{ $diff->id }}" {{ $question->difficulty_id == $diff->id ? 'selected' : '' }}>
                                        {{ $diff->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Kỹ năng</label>
                            <div class="grid grid-cols-2 gap-2 border border-gray-300 rounded-md p-2 text-sm">
                                @foreach($skills as $skill)
                                    <label class="flex items-center gap-2">
                                        <input 
                                            type="checkbox" 
                                            class="skill" 
                                            name="skills[]" 
                                            value="{{ $skill->id }}"
                                            {{ $question->skills->contains($skill->id) ? 'checked' : '' }}> 
                                        {{ $skill->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Tags</label>
                            <div id="f-tags" class="flex flex-wrap gap-2 border border-gray-300 rounded-md px-2 py-2 min-h-[40px]">
                                @foreach($question->tags as $tag)
                                    <span class="px-2 py-1 border border-black rounded-md text-xs text-black cursor-pointer" data-tag-name="{{ $tag->name }}">
                                        {{ $tag->name }}
                                        <input type="hidden" name="tag_names[]" value="{{ $tag->name }}">
                                    </span>
                                @endforeach
                                <input 
                                    id="f-tag-input" 
                                    type="text" 
                                    class="flex-1 min-w-[8rem] outline-none text-sm" 
                                    placeholder="Nhập & Enter">
                            </div>
                        </div>
                        
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Ảnh (URL)</label>
                                <input 
                                    id="f-image" 
                                    name="image_url" 
                                    type="text" 
                                    value="{{ old('image_url', $question->image_url) }}"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" 
                                    placeholder="https://...">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Audio (URL)</label>
                                <input 
                                    id="f-audio" 
                                    name="audio_url" 
                                    type="text" 
                                    value="{{ old('audio_url', $question->audio_url) }}"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" 
                                    placeholder="https://...">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Video (URL)</label>
                                <input 
                                    id="f-video" 
                                    name="video_url" 
                                    type="text" 
                                    value="{{ old('video_url', $question->video_url) }}"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" 
                                    placeholder="https://...">
                            </div>
                        </div>
                        
                        <!-- Answers builder -->
                        <div class="md:col-span-2">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs text-gray-600">Đáp án/Thiết lập</label>
                                <button id="add-option" type="button" class="text-xs underline text-gray-700 hover:text-black">Thêm tuỳ chọn</button>
                            </div>
                            <div id="options-box" class="space-y-2">
                                <!-- Will be populated by JavaScript based on question type -->
                            </div>
                            <p class="text-xs text-gray-600 mt-2">Chọn đáp án đúng (single/boolean), chọn nhiều đáp án đúng (multi), hoặc điền cấu hình phù hợp cho từng loại.</p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-600 mb-1">Giải thích (hiển thị cho học sinh sau khi nộp)</label>
                            <textarea 
                                id="f-explain" 
                                name="explanation" 
                                rows="3" 
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">{{ old('explanation', $question->explanation) }}</textarea>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-600 mb-1">Ghi chú thay đổi (cho lịch sử phiên bản)</label>
                            <input 
                                type="text" 
                                name="change_note" 
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" 
                                placeholder="Ví dụ: Sửa đáp án C, thêm hình minh họa...">
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-3">
                    <a 
                        href="{{ route('teacher.questions.index') }}" 
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                        Hủy
                    </a>
                    <button 
                        type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MathJax -->
    <script defer src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <script>
        const questionData = @json([
            'type' => $question->type,
            'options' => $question->options ?? [],
            'correct_answer' => $question->correct_answer ?? null
        ]);

        const fType = document.getElementById('f-type');
        const optionsBox = document.getElementById('options-box');
        const addOptionBtn = document.getElementById('add-option');
        const fContent = document.getElementById('f-content');
        const preview = document.getElementById('content-preview');

        // Render options based on type
        function renderOptions() {
            optionsBox.innerHTML = '';
            const type = fType.value;
            const options = questionData.options || [];
            const correctAnswer = questionData.correct_answer;

            if (type === 'single' || type === 'multi') {
                if (options.length === 0) {
                    for (let i = 0; i < 4; i++) addOptionRow();
                } else {
                    options.forEach((opt, index) => {
                        const isCorrect = type === 'single' 
                            ? (Array.isArray(correctAnswer) ? correctAnswer[0] === index : correctAnswer === index)
                            : (Array.isArray(correctAnswer) ? correctAnswer.includes(index) : false);
                        addOptionRow(opt, isCorrect);
                    });
                }
            } else if (type === 'boolean') {
                const isTrue = correctAnswer === 'true' || correctAnswer === true;
                const checkedTrue = isTrue ? 'checked' : '';
                const checkedFalse = !isTrue ? 'checked' : '';
                optionsBox.innerHTML = `
                    <div class="flex items-center gap-2">
                        <label class="flex items-center gap-2 text-sm text-gray-800">
                            <input type="radio" name="bool" value="true" ` + checkedTrue + `> Đúng
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-800">
                            <input type="radio" name="bool" value="false" ` + checkedFalse + `> Sai
                        </label>
                    </div>`;
            } else if (type === 'text') {
                const answer = Array.isArray(correctAnswer) ? correctAnswer.join('|') : (correctAnswer || '');
                optionsBox.innerHTML = '<input type="text" name="correct_answer" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Đáp án đúng (có thể nhiều, cách nhau bằng |)" value="' + answer + '">';
            } else if (type === 'order') {
                optionsBox.innerHTML = `<div class="space-y-2" id="order-build"></div>`;
                if (Array.isArray(correctAnswer) && correctAnswer.length > 0) {
                    correctAnswer.forEach(item => addOrderRow(item));
                } else {
                    for (let i = 0; i < 4; i++) addOrderRow();
                }
            } else if (type === 'match') {
                optionsBox.innerHTML = `<div class="grid grid-cols-2 gap-3"><div id="left-build" class="space-y-2"></div><div id="right-build" class="space-y-2"></div></div>`;
                if (Array.isArray(correctAnswer) && correctAnswer.length > 0) {
                    // Handle match pairs
                    correctAnswer.forEach((pair, index) => {
                        if (Array.isArray(pair) && pair.length === 2) {
                            addMatchRow('left-build', pair[0]);
                            addMatchRow('right-build', pair[1]);
                        }
                    });
                } else {
                    for (let i = 0; i < 3; i++) { 
                        addMatchRow('left-build'); 
                        addMatchRow('right-build'); 
                    }
                }
            } else if (type === 'essay') {
                optionsBox.innerHTML = `<p class="text-sm text-gray-700">Câu tự luận: không có đáp án đúng/sai cố định.</p>`;
            }
        }

        function addOptionRow(value = '', isCorrect = false) {
            const type = fType.value;
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2';
            const radioOrCheckbox = type === 'single' ? '<input type="radio" name="correct" value="">' : '<input type="checkbox" class="is-correct">';
            row.innerHTML = radioOrCheckbox +
                '<input type="text" class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm option-text" placeholder="Nội dung đáp án" value="' + value + '">' +
                '<button type="button" class="text-xs underline text-gray-700 remove-row hover:text-black">Xoá</button>';
            if (isCorrect) {
                const correctInput = row.querySelector(type === 'single' ? 'input[type="radio"]' : 'input[type="checkbox"]');
                if (correctInput) correctInput.checked = true;
            }
            row.querySelector('.remove-row').addEventListener('click', () => row.remove());
            optionsBox.appendChild(row);
        }

        function addOrderRow(value = '') {
            const box = document.getElementById('order-build');
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2';
            row.innerHTML = '<span class="text-xs text-gray-600">#</span><input type="text" class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Mục" value="' + value + '"> <button type="button" class="text-xs underline text-gray-700 remove-row hover:text-black">Xoá</button>';
            row.querySelector('.remove-row').addEventListener('click', () => row.remove());
            box.appendChild(row);
        }

        function addMatchRow(targetId, value = '') {
            const box = document.getElementById(targetId);
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2';
            row.innerHTML = '<input type="text" class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Khoá/giá trị" value="' + value + '"> <button type="button" class="text-xs underline text-gray-700 remove-row hover:text-black">Xoá</button>';
            row.querySelector('.remove-row').addEventListener('click', () => row.remove());
            box.appendChild(row);
        }

        fType.addEventListener('change', () => {
            questionData.type = fType.value;
            renderOptions();
        });

        addOptionBtn.addEventListener('click', () => {
            const type = fType.value;
            if (type === 'single' || type === 'multi') addOptionRow();
            if (type === 'order') addOrderRow();
            if (type === 'match') { addMatchRow('left-build'); addMatchRow('right-build'); }
        });

        // Content preview with MathJax
        function renderPreview() {
            preview.textContent = fContent.value;
            if (window.MathJax?.typesetPromise) {
                MathJax.typesetPromise([preview]).catch(() => {});
            }
        }
        fContent.addEventListener('input', renderPreview);

        // Tags input
        const tagInput = document.getElementById('f-tag-input');
        const tagsBox = document.getElementById('f-tags');
        
        tagInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const text = tagInput.value.trim();
                if (text) {
                    const chip = document.createElement('span');
                    chip.className = 'px-2 py-1 border border-black rounded-md text-xs text-black cursor-pointer';
                    chip.textContent = text;
                    chip.dataset.tagName = text;
                    chip.innerHTML = text + ' <input type="hidden" name="tag_names[]" value="' + text + '">';
                    chip.addEventListener('click', () => chip.remove());
                    tagsBox.insertBefore(chip, tagInput);
                    tagInput.value = '';
                }
            }
        });

        // Form submit handler
        document.getElementById('question-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            
            // Collect options and correct answers
            const type = fType.value;
            const options = [];
            const correctAnswers = [];
            
            if (type === 'single' || type === 'multi') {
                document.querySelectorAll('#options-box .option-text').forEach((input, index) => {
                    const value = input.value.trim();
                    if (value) {
                        options.push(value);
                        const isCorrect = type === 'single' 
                            ? input.closest('div').querySelector('input[type="radio"]')?.checked
                            : input.closest('div').querySelector('input[type="checkbox"]')?.checked;
                        if (isCorrect) correctAnswers.push(index);
                    }
                });
                formData.set('options', JSON.stringify(options));
                formData.set('correct_answer', JSON.stringify(correctAnswers));
            } else if (type === 'boolean') {
                const selected = document.querySelector('input[name="bool"]:checked')?.value;
                formData.set('correct_answer', selected);
            } else if (type === 'text') {
                const answer = document.querySelector('#options-box input[type="text"]')?.value;
                formData.set('correct_answer', answer);
            }
            
            // Submit form
            fetch(form.action, {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = '{{ route("teacher.questions.index") }}';
                } else {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Có lỗi xảy ra');
                    });
                }
            })
            .catch(error => {
                alert('Lỗi: ' + error.message);
            });
        });

        // Initialize
        renderOptions();
        renderPreview();
    </script>
</x-app-layout>

