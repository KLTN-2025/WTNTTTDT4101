<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Tạo đề/đề thi tự động') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Template meta + options -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin chung</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Tên mẫu đề</label>
                        <input id="tpl-name" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black" placeholder="Ví dụ: Đề Toán giữa kỳ lớp 10">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Mô tả</label>
                        <input id="tpl-desc" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Ghi chú ngắn...">
                    </div>
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                        <label class="flex items-center gap-2 text-sm text-gray-800"><input id="rand-questions" type="checkbox" class="border-gray-400"> Ngẫu nhiên hoá thứ tự câu hỏi</label>
                        <label class="flex items-center gap-2 text-sm text-gray-800"><input id="rand-options" type="checkbox" class="border-gray-400"> Ngẫu nhiên hoá đáp án</label>
                        <label class="flex items-center gap-2 text-sm text-gray-800"><input id="unique-per-student" type="checkbox" class="border-gray-400"> Đề khác nhau theo học sinh</label>
                    </div>
                </div>
            </div>

            <!-- Structure by tags/topics -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Cấu trúc đề theo chủ đề/tags</h3>
                    <button id="add-section" class="text-xs underline text-gray-700 hover:text-black">Thêm dòng</button>
                </div>
                <div class="overflow-hidden border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Chủ đề/Tag</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Số câu</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Điểm mỗi câu</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Xoá</th>
                            </tr>
                        </thead>
                        <tbody id="sections" class="bg-white divide-y divide-gray-100">
                            <!-- rows by JS -->
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td class="px-3 py-2 text-gray-700">Tổng</td>
                                <td class="px-3 py-2 text-gray-900 font-semibold" id="sum-questions">0</td>
                                <td class="px-3 py-2 text-gray-700">—</td>
                                <td class="px-3 py-2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Difficulty distribution -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tỉ lệ độ khó</h3>
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-xs text-gray-600 mb-1">Dễ (%)</label>
                        <input id="pct-easy" type="number" min="0" max="100" value="40" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs text-gray-600 mb-1">Trung bình (%)</label>
                        <input id="pct-medium" type="number" min="0" max="100" value="40" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs text-gray-600 mb-1">Khó (%)</label>
                        <input id="pct-hard" type="number" min="0" max="100" value="20" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    </div>
                    <div class="md:col-span-6">
                        <p id="pct-warning" class="text-xs text-gray-600">Tổng phải = 100%.</p>
                    </div>
                </div>
            </div>

            <!-- Schedule -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Lịch thi</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Bắt đầu</label>
                        <input id="sched-start" type="datetime-local" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Kết thúc</label>
                        <input id="sched-end" type="datetime-local" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Thời lượng (phút)</label>
                        <input id="sched-duration" type="number" min="1" value="60" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Số lần làm tối đa</label>
                        <input id="sched-attempts" type="number" min="1" value="1" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    </div>
                </div>
            </div>

            <!-- Actions + Preview -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <button id="generate-sample" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Tạo bản xem trước</button>
                        <button id="save-template" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">Lưu mẫu đề</button>
                        <button id="save-schedule" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Lưu lịch thi</button>
                    </div>
                    <div class="text-sm text-gray-700">Tổng câu dự kiến: <span id="preview-total" class="font-semibold text-gray-900">0</span></div>
                </div>
                <div id="preview-box" class="border border-gray-200 rounded-lg p-4 text-sm text-gray-800 hidden"></div>
            </div>

            <!-- Create Exam from Template -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tạo bài thi từ mẫu đề</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Chọn mẫu đề</label>
                        <select id="select-template" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="">-- Chọn mẫu đề --</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Tên bài thi</label>
                        <input id="exam-name" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Ví dụ: Đề thi Toán giữa kỳ">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Môn học</label>
                        <input id="exam-subject" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Ví dụ: Toán">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Mô tả</label>
                        <input id="exam-description" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Mô tả ngắn...">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Gán cho lớp học</label>
                        <select id="exam-class" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="">-- Chọn lớp (tùy chọn) --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Thời lượng (phút)</label>
                        <input id="exam-duration" type="number" min="1" value="60" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Thời gian bắt đầu</label>
                        <input id="exam-start-date" type="datetime-local" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Hạn nộp</label>
                        <input id="exam-due-date" type="datetime-local" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    </div>
                </div>
                <div class="mt-4">
                    <button id="create-exam-btn" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">
                        Tạo bài thi và gán cho học sinh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sections dynamic rows
        const sections = document.getElementById('sections');
        const addBtn = document.getElementById('add-section');
        const sumQuestions = document.getElementById('sum-questions');
        const previewTotal = document.getElementById('preview-total');
        function recalc() {
            let total = 0;
            sections.querySelectorAll('tr').forEach(tr => {
                const n = parseInt(tr.querySelector('[data-n]')?.value || '0', 10);
                total += isNaN(n) ? 0 : n;
            });
            sumQuestions.textContent = total;
            previewTotal.textContent = total;
        }
        function addRow(tag = '', n = 5, pt = 1) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="px-3 py-2"><input type="text" value="${tag}" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Ví dụ: algebra, reading"></td>
                <td class="px-3 py-2"><input data-n type="number" min="1" value="${n}" class="w-28 border border-gray-300 rounded-md px-3 py-2 text-sm"></td>
                <td class="px-3 py-2"><input type="number" min="0" step="0.1" value="${pt}" class="w-28 border border-gray-300 rounded-md px-3 py-2 text-sm"></td>
                <td class="px-3 py-2 text-right"><button class="text-xs underline text-gray-700 hover:text-black" data-del>Xoá</button></td>`;
            tr.querySelector('[data-n]').addEventListener('input', recalc);
            tr.querySelector('[data-del]').addEventListener('click', () => { tr.remove(); recalc(); });
            sections.appendChild(tr);
            recalc();
        }
        addBtn?.addEventListener('click', () => addRow());
        // default two rows
        addRow('algebra', 10, 1);
        addRow('geometry', 5, 2);

        // Validate difficulty percentages
        const pctEasy = document.getElementById('pct-easy');
        const pctMed = document.getElementById('pct-medium');
        const pctHard = document.getElementById('pct-hard');
        const pctWarn = document.getElementById('pct-warning');
        function validatePct() {
            const s = Number(pctEasy.value||0) + Number(pctMed.value||0) + Number(pctHard.value||0);
            pctWarn.textContent = `Tổng phải = 100%. Hiện tại: ${s}%`;
            pctWarn.className = 'text-xs ' + (s === 100 ? 'text-gray-600' : 'text-red-600');
        }
        [pctEasy, pctMed, pctHard].forEach(el => el.addEventListener('input', validatePct));
        validatePct();

        // Generate sample preview (UI demo)
        const previewBox = document.getElementById('preview-box');
        document.getElementById('generate-sample')?.addEventListener('click', () => {
            const total = Number(sumQuestions.textContent||0);
            const e = Number(pctEasy.value||0), m = Number(pctMed.value||0), h = Number(pctHard.value||0);
            const cntE = Math.round(total * e/100);
            const cntM = Math.round(total * m/100);
            const cntH = Math.max(0, total - cntE - cntM);
            const rows = Array.from(sections.querySelectorAll('tr')).map(tr => {
                const tag = tr.querySelector('td:first-child input')?.value || '';
                const n = Number(tr.querySelector('[data-n]')?.value||0);
                const pt = Number(tr.querySelector('td:nth-child(3) input')?.value||0);
                return { tag, n, pt };
            });
            let html = `<div class="text-sm text-gray-900 mb-2">Phân bổ độ khó dự kiến: Dễ ${cntE}, Trung bình ${cntM}, Khó ${cntH}</div>`;
            html += `<ul class="list-disc ml-5 space-y-1">`;
            rows.forEach(r => { html += `<li>${r.tag}: ${r.n} câu • ${r.pt} điểm/câu</li>`; });
            html += `</ul>`;
            previewBox.innerHTML = html;
            previewBox.classList.remove('hidden');
        });

        // Save template
        document.getElementById('save-template')?.addEventListener('click', async () => {
            const name = document.getElementById('tpl-name')?.value;
            const desc = document.getElementById('tpl-desc')?.value;
            
            if (!name) {
                alert('Vui lòng nhập tên mẫu đề');
                return;
            }

            const structure = Array.from(sections.querySelectorAll('tr')).map(tr => {
                const tag = tr.querySelector('td:first-child input')?.value || '';
                const count = parseInt(tr.querySelector('[data-n]')?.value || '0', 10);
                const points = parseFloat(tr.querySelector('td:nth-child(3) input')?.value || '1', 10);
                return { tag, count, points };
            }).filter(s => s.tag && s.count > 0);

            if (structure.length === 0) {
                alert('Vui lòng thêm ít nhất một chủ đề');
                return;
            }

            const difficultyDist = {
                easy: parseFloat(pctEasy.value || '0', 10),
                medium: parseFloat(pctMed.value || '0', 10),
                hard: parseFloat(pctHard.value || '0', 10)
            };

            const formData = new FormData();
            formData.append('name', name);
            formData.append('description', desc || '');
            formData.append('structure', JSON.stringify(structure));
            formData.append('difficulty_distribution', JSON.stringify(difficultyDist));
            formData.append('randomize_questions', document.getElementById('rand-questions')?.checked ? '1' : '0');
            formData.append('randomize_options', document.getElementById('rand-options')?.checked ? '1' : '0');
            formData.append('unique_per_student', document.getElementById('unique-per-student')?.checked ? '1' : '0');

            try {
                const response = await fetch('{{ route("teacher.exams.templates.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    alert('Đã lưu mẫu đề thành công!');
                    window.location.reload();
                } else {
                    const error = await response.json();
                    alert('Lỗi: ' + (error.message || 'Có lỗi xảy ra'));
                }
            } catch (error) {
                alert('Lỗi: ' + error.message);
            }
        });

        // Save schedule
        document.getElementById('save-schedule')?.addEventListener('click', async () => {
            const start = document.getElementById('sched-start')?.value;
            const end = document.getElementById('sched-end')?.value;
            const dur = document.getElementById('sched-duration')?.value;
            const att = document.getElementById('sched-attempts')?.value;
            const name = document.getElementById('tpl-name')?.value || 'Lịch thi';

            if (!start || !end) {
                alert('Vui lòng nhập thời gian bắt đầu và kết thúc');
                return;
            }

            const formData = new FormData();
            formData.append('name', name);
            formData.append('start_time', start);
            formData.append('end_time', end);
            formData.append('duration_minutes', dur || '60');
            formData.append('max_attempts', att || '1');

            try {
                const response = await fetch('{{ route("teacher.exams.schedules.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    alert('Đã lưu lịch thi thành công!');
                } else {
                    const error = await response.json();
                    alert('Lỗi: ' + (error.message || 'Có lỗi xảy ra'));
                }
            } catch (error) {
                alert('Lỗi: ' + error.message);
            }
        });

        // Create Exam from Template
        document.getElementById('create-exam-btn')?.addEventListener('click', async () => {
            const templateId = document.getElementById('select-template')?.value;
            const examName = document.getElementById('exam-name')?.value;
            const examSubject = document.getElementById('exam-subject')?.value;
            const examDescription = document.getElementById('exam-description')?.value;
            const classId = document.getElementById('exam-class')?.value;
            const duration = document.getElementById('exam-duration')?.value;
            const startDate = document.getElementById('exam-start-date')?.value;
            const dueDate = document.getElementById('exam-due-date')?.value;

            if (!templateId) {
                alert('Vui lòng chọn mẫu đề');
                return;
            }

            if (!examName || !examSubject) {
                alert('Vui lòng nhập tên bài thi và môn học');
                return;
            }

            const formData = new FormData();
            formData.append('name', examName);
            formData.append('subject', examSubject);
            formData.append('description', examDescription || '');
            if (classId) {
                formData.append('class_id', classId);
            }
            if (duration) {
                formData.append('duration_minutes', duration);
            }
            if (startDate) {
                formData.append('start_date', startDate);
            }
            if (dueDate) {
                formData.append('due_date', dueDate);
            }

            const btn = document.getElementById('create-exam-btn');
            btn.disabled = true;
            btn.textContent = 'Đang tạo...';

            try {
                const response = await fetch(`/teacher/exams/templates/${templateId}/generate`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                let data;
                const contentType = response.headers.get('content-type');
                
                if (contentType && contentType.includes('application/json')) {
                    data = await response.json();
                } else {
                    const text = await response.text();
                    throw new Error('Server trả về dữ liệu không hợp lệ. Vui lòng thử lại.');
                }

                if (response.ok && data.success) {
                    alert(data.message || 'Đã tạo bài thi thành công!');
                    window.location.href = '{{ route("teacher.grading.index") }}';
                } else {
                    let errorMsg = data.message || 'Có lỗi xảy ra';
                    if (data.errors) {
                        const errorList = Object.values(data.errors).flat().join('\n');
                        errorMsg += '\n' + errorList;
                    }
                    alert('Lỗi: ' + errorMsg);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Lỗi: ' + (error.message || 'Có lỗi xảy ra khi tạo bài thi. Vui lòng thử lại.'));
            } finally {
                btn.disabled = false;
                btn.textContent = 'Tạo bài thi và gán cho học sinh';
            }
        });
    </script>
</x-app-layout>


