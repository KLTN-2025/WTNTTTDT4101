<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Sinh câu hỏi tự động từ tài liệu') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Input sources -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Nguồn tài liệu</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Tải file (PDF, PPTX, DOCX, TXT)</label>
                        <input id="doc-file" type="file" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" accept=".pdf,.ppt,.pptx,.doc,.docx,.txt">
                        <p class="text-xs text-gray-500 mt-1">Tối đa 10MB</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Dán nội dung (tuỳ chọn)</label>
                        <textarea id="doc-text" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Dán bài giảng/ghi chú..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Generation options -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tuỳ chọn sinh câu hỏi</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Số câu</label>
                        <input id="q-count" type="number" min="1" max="50" value="10" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Loại câu hỏi</label>
                        <select id="q-type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="mcq">Multiple-choice</option>
                            <option value="tf">Đúng/Sai</option>
                            <option value="stem">Stem (đặt đề)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Độ khó mục tiêu</label>
                        <select id="q-diff" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="mix">Trộn</option>
                            <option value="easy">Dễ</option>
                            <option value="medium">Trung bình</option>
                            <option value="hard">Khó</option>
                        </select>
                    </div>
                    <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-center gap-2 text-sm text-gray-800">
                            <input id="opt-distractors" type="checkbox" class="border-gray-400" checked> 
                            Sinh distractors hợp lý
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-800">
                            <input id="opt-cite" type="checkbox" class="border-gray-400"> 
                            Đính kèm trích dẫn đoạn nguồn
                        </label>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-3">
                    <button id="generate" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">
                        Sinh câu hỏi
                    </button>
                    <button id="clear" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">
                        Xoá kết quả
                    </button>
                    <div id="loading" class="hidden text-sm text-gray-600">
                        <span class="animate-pulse">Đang xử lý...</span>
                    </div>
                </div>
            </div>

            <!-- Preview & selection -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900">Kết quả sinh</h3>
                    <div class="text-sm text-gray-700">Đã chọn: <span id="sel-count" class="text-gray-900">0</span></div>
                </div>
                <ul id="gen-list" class="space-y-4">
                    <li class="text-sm text-gray-500 text-center py-8">Chưa có câu hỏi nào. Hãy tải file hoặc dán nội dung và nhấn "Sinh câu hỏi".</li>
                </ul>
                <div class="mt-4 flex items-center justify-end gap-3">
                    <button id="import-qb" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">
                        Thêm vào Ngân hàng câu hỏi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const list = document.getElementById('gen-list');
        let generatedQuestions = [];

        function render(items) {
            generatedQuestions = items;
            window.generatedQuestions = items;
            list.innerHTML = '';
            
            if (items.length === 0) {
                list.innerHTML = '<li class="text-sm text-gray-500 text-center py-8">Chưa có câu hỏi nào.</li>';
                updateSel();
                return;
            }

            items.forEach((it, idx) => {
                const li = document.createElement('li');
                li.className = 'border border-gray-200 rounded-lg p-4';
                
                const optionsHtml = it.options && it.options.length > 0 
                    ? `<ul class='mt-2 ml-6 list-disc text-sm text-gray-800'>${it.options.map((o, i) => {
                        const isCorrect = o === it.correct || (Array.isArray(it.correct) && it.correct.includes(o));
                        return `<li>${String.fromCharCode(65 + i)}. ${o} ${isCorrect ? '<span class="text-green-600 font-semibold">(Đúng)</span>' : ''}</li>`;
                    }).join('')}</ul>` 
                    : '';
                
                const citationHtml = it.citation 
                    ? `<p class='mt-2 text-xs text-gray-600'>Nguồn: ${it.citation}</p>` 
                    : '';

                li.innerHTML = `
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <label class="flex items-center gap-2 text-sm text-gray-900">
                                <input type="checkbox" class="pick" data-index="${idx}" checked> 
                                <span class="font-semibold">[${it.type.toUpperCase()}]</span> ${it.stem}
                            </label>
                            ${optionsHtml}
                            ${citationHtml}
                        </div>
                        <button class="text-xs underline text-gray-700 hover:text-black ml-4" onclick="editQuestion(${idx})">Chỉnh</button>
                    </div>
                `;
                list.appendChild(li);
            });
            
            updateSel();
            list.querySelectorAll('.pick').forEach(c => c.addEventListener('change', updateSel));
        }

        function updateSel() {
            const cnt = list.querySelectorAll('.pick:checked').length;
            document.getElementById('sel-count').textContent = cnt;
        }

        function editQuestion(index) {
            const q = generatedQuestions[index];
            const newStem = prompt('Chỉnh sửa câu hỏi:', q.stem);
            if (newStem !== null && newStem.trim()) {
                generatedQuestions[index].stem = newStem.trim();
                render(generatedQuestions);
            }
        }

        document.getElementById('generate')?.addEventListener('click', async () => {
            const content = document.getElementById('doc-text')?.value || '';
            const file = document.getElementById('doc-file')?.files[0];
            
            if (!content && !file) {
                alert('Vui lòng nhập nội dung hoặc tải file');
                return;
            }

            const count = Number(document.getElementById('q-count').value || 10);
            const type = document.getElementById('q-type').value;
            const difficulty = document.getElementById('q-diff').value;
            const generateDistractors = document.getElementById('opt-distractors')?.checked || false;
            const includeCitations = document.getElementById('opt-cite')?.checked || false;

            const loadingEl = document.getElementById('loading');
            const generateBtn = document.getElementById('generate');
            
            loadingEl.classList.remove('hidden');
            generateBtn.disabled = true;

            try {
                const formData = new FormData();
                if (content) {
                    formData.append('content', content);
                }
                if (file) {
                    formData.append('file', file);
                }
                formData.append('count', count);
                formData.append('type', type);
                formData.append('difficulty', difficulty);
                formData.append('generate_distractors', generateDistractors ? '1' : '0');
                formData.append('include_citations', includeCitations ? '1' : '0');

                const response = await fetch('{{ route("teacher.ai.generator.generate") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    render(data.questions);
                } else {
                    alert('Lỗi: ' + (data.message || 'Có lỗi xảy ra'));
                }
            } catch (error) {
                alert('Lỗi: ' + error.message);
            } finally {
                loadingEl.classList.add('hidden');
                generateBtn.disabled = false;
            }
        });

        document.getElementById('clear')?.addEventListener('click', () => { 
            render([]);
            document.getElementById('doc-text').value = '';
            document.getElementById('doc-file').value = '';
        });

        document.getElementById('import-qb')?.addEventListener('click', async () => {
            const selected = Array.from(list.querySelectorAll('.pick:checked')).map(cb => {
                const index = parseInt(cb.getAttribute('data-index'));
                return generatedQuestions[index];
            }).filter(Boolean);

            if(selected.length === 0) { 
                alert('Chọn ít nhất 1 câu hỏi.'); 
                return; 
            }

            try {
                const response = await fetch('{{ route("teacher.ai.generator.import") }}', {
                    method: 'POST',
                    body: JSON.stringify({ questions: selected }),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    alert(`Đã thêm ${data.count} câu hỏi vào Ngân hàng câu hỏi!`);
                    window.location.href = '{{ route("teacher.questions.index") }}';
                } else {
                    alert('Lỗi: ' + (data.message || 'Có lỗi xảy ra'));
                }
            } catch (error) {
                alert('Lỗi: ' + error.message);
            }
        });
    </script>
</x-app-layout>
