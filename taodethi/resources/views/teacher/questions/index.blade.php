<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Ngân hàng câu hỏi') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Toolbar: Create + Search + Filters -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-4 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <button id="open-create" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">Tạo câu hỏi</button>
                        <div class="relative">
                            <button id="import-btn" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">Import</button>
                            <div id="import-menu" class="hidden absolute z-10 mt-2 w-40 bg-white border border-gray-200 rounded-md shadow">
                                <button data-import="csv" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50">CSV</button>
                                <button data-import="xlsx" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50">Excel (.xlsx)</button>
                                <button data-import="qti" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50">QTI</button>
                            </div>
                            <input id="import-file" type="file" class="hidden" accept=".csv,.xlsx,.xml,.zip">
                        </div>
                        <div class="relative">
                            <button id="export-btn" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">Export</button>
                            <div id="export-menu" class="hidden absolute z-10 mt-2 w-44 bg-white border border-gray-200 rounded-md shadow">
                                <button data-export="csv" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50">CSV</button>
                                <button data-export="xlsx" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50">Excel (.xlsx)</button>
                                <button data-export="qti" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50">QTI</button>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1">
                        <input id="search" type="text" placeholder="Tìm theo tiêu đề/nội dung..." class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Chủ đề (tags)</label>
                        <div id="tags-box" class="flex flex-wrap gap-2 border border-gray-300 rounded-md px-2 py-2">
                            <input id="tag-input" type="text" class="flex-1 min-w-[8rem] outline-none text-sm" placeholder="Nhập và nhấn Enter">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Độ khó</label>
                        <select id="difficulty" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="">Tất cả</option>
                            <option value="easy">Dễ</option>
                            <option value="medium">Trung bình</option>
                            <option value="hard">Khó</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Kỹ năng</label>
                        <select id="skill" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="">Tất cả</option>
                            <option value="reading">Đọc hiểu</option>
                            <option value="writing">Viết</option>
                            <option value="listening">Nghe</option>
                            <option value="speaking">Nói</option>
                            <option value="algebra">Đại số</option>
                            <option value="geometry">Hình học</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Loại câu hỏi</label>
                        <select id="qtype" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="">Tất cả</option>
                            <option value="single">Trắc nghiệm 1 đáp án</option>
                            <option value="multi">Nhiều đáp án</option>
                            <option value="boolean">Đúng/Sai</option>
                            <option value="text">Điền từ</option>
                            <option value="order">Sắp xếp</option>
                            <option value="match">Ghép cặp</option>
                            <option value="essay">Tự luận</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- List -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-0 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tiêu đề</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Loại</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Độ khó</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tags</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Kỹ năng</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="qb-rows" class="bg-white divide-y divide-gray-100">
                        <!-- Sample static rows -->
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">2 + 2 = ? <span class="ml-2 hidden text-xs px-2 py-0.5 border border-black rounded" data-flag-badge>Lỗi</span></td>
                            <td class="px-4 py-2 text-sm text-gray-700">single</td>
                            <td class="px-4 py-2 text-sm text-gray-700">Dễ</td>
                            <td class="px-4 py-2 text-sm text-gray-700">toán, cơ bản</td>
                            <td class="px-4 py-2 text-sm text-gray-700">algebra</td>
                            <td class="px-4 py-2 text-sm text-gray-700"><button class="text-xs underline text-gray-700 hover:text-black" data-flag>Đánh dấu lỗi</button></td>
                            <td class="px-4 py-2 text-sm text-gray-700 text-right">
                                <button class="text-xs underline text-gray-700 hover:text-black mr-3" data-history>Lịch sử</button>
                                <button class="text-xs underline text-gray-700 hover:text-black mr-3" data-edit>Chỉnh sửa</button>
                                <button class="text-xs underline text-gray-700 hover:text-black" data-delete>Xoá</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">Số nguyên tố &lt; 10 <span class="ml-2 hidden text-xs px-2 py-0.5 border border-black rounded" data-flag-badge>Lỗi</span></td>
                            <td class="px-4 py-2 text-sm text-gray-700">multi</td>
                            <td class="px-4 py-2 text-sm text-gray-700">Trung bình</td>
                            <td class="px-4 py-2 text-sm text-gray-700">số học</td>
                            <td class="px-4 py-2 text-sm text-gray-700">algebra</td>
                            <td class="px-4 py-2 text-sm text-gray-700"><button class="text-xs underline text-gray-700 hover:text-black" data-flag>Đánh dấu lỗi</button></td>
                            <td class="px-4 py-2 text-sm text-gray-700 text-right">
                                <button class="text-xs underline text-gray-700 hover:text-black mr-3" data-history>Lịch sử</button>
                                <button class="text-xs underline text-gray-700 hover:text-black mr-3" data-edit>Chỉnh sửa</button>
                                <button class="text-xs underline text-gray-700 hover:text-black" data-delete>Xoá</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal Create/Edit -->
            <div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4">
                <div class="bg-white w-full max-w-2xl border border-gray-200 sm:rounded-xl p-0 overflow-hidden shadow-xl">
                    <div class="px-5 py-3 border-b border-gray-200 flex items-center justify-between">
                        <h3 id="modal-title" class="text-base font-semibold text-gray-900">Tạo câu hỏi</h3>
                        <button id="close-modal" class="text-sm text-gray-600 hover:text-black">Đóng</button>
                    </div>
                    <div class="p-5 md:max-h-[70vh] overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-600 mb-1">Tiêu đề</label>
                                <input id="f-title" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black" placeholder="Ví dụ: 2 + 2 = ?">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-600 mb-1">Nội dung (hỗ trợ MathJax)</label>
                                <textarea id="f-content" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black" placeholder="Ví dụ: \(a^2 + b^2 = c^2\)"></textarea>
                                <p class="text-xs text-gray-600 mt-1">Xem trước công thức ngay bên dưới.</p>
                                <div id="content-preview" class="mt-2 p-3 border border-gray-200 rounded text-sm text-gray-900 bg-gray-50 min-h-[48px]"></div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Loại câu hỏi</label>
                                <select id="f-type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                    <option value="single">Trắc nghiệm 1 đáp án</option>
                                    <option value="multi">Nhiều đáp án</option>
                                    <option value="boolean">Đúng/Sai</option>
                                    <option value="text">Điền từ</option>
                                    <option value="order">Sắp xếp</option>
                                    <option value="match">Ghép cặp</option>
                                    <option value="essay">Tự luận</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Độ khó</label>
                                <select id="f-difficulty" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                    <option value="easy">Dễ</option>
                                    <option value="medium">Trung bình</option>
                                    <option value="hard">Khó</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Kỹ năng</label>
                                <div class="grid grid-cols-2 gap-2 border border-gray-300 rounded-md p-2 text-sm">
                                    <label class="flex items-center gap-2"><input type="checkbox" class="skill" value="reading"> Đọc</label>
                                    <label class="flex items-center gap-2"><input type="checkbox" class="skill" value="writing"> Viết</label>
                                    <label class="flex items-center gap-2"><input type="checkbox" class="skill" value="listening"> Nghe</label>
                                    <label class="flex items-center gap-2"><input type="checkbox" class="skill" value="speaking"> Nói</label>
                                    <label class="flex items-center gap-2"><input type="checkbox" class="skill" value="algebra"> Đại số</label>
                                    <label class="flex items-center gap-2"><input type="checkbox" class="skill" value="geometry"> Hình học</label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Tags</label>
                                <div id="f-tags" class="flex flex-wrap gap-2 border border-gray-300 rounded-md px-2 py-2">
                                    <input id="f-tag-input" type="text" class="flex-1 min-w-[8rem] outline-none text-sm" placeholder="Nhập & Enter">
                                </div>
                            </div>
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Ảnh (URL)</label>
                                    <input id="f-image" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="https://...">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Audio (URL)</label>
                                    <input id="f-audio" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="https://...">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Video (URL)</label>
                                    <input id="f-video" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="https://...">
                                </div>
                            </div>
                            <!-- Answers builder -->
                            <div class="md:col-span-2">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs text-gray-600">Đáp án/Thiết lập</label>
                                    <button id="add-option" type="button" class="text-xs underline text-gray-700 hover:text-black">Thêm tuỳ chọn</button>
                                </div>
                                <div id="options-box" class="space-y-2">
                                    <!-- rows injected by JS depending on type -->
                                </div>
                                <p class="text-xs text-gray-600 mt-2">Chọn đáp án đúng (single/boolean), chọn nhiều đáp án đúng (multi), hoặc điền cấu hình phù hợp cho từng loại.</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-600 mb-1">Giải thích (hiển thị cho học sinh sau khi nộp)</label>
                                <textarea id="f-explain" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 py-3 border-t border-gray-200 flex items-center justify-end gap-3">
                        <button id="save-question" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">Lưu</button>
                    </div>
                </div>
            </div>

            <!-- Modal Lịch sử phiên bản -->
            <div id="history-modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4">
                <div class="bg-white w-full max-w-xl border border-gray-200 sm:rounded-xl p-0 overflow-hidden shadow-xl">
                    <div class="px-5 py-3 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-900">Lịch sử phiên bản</h3>
                        <button id="close-history" class="text-sm text-gray-600 hover:text-black">Đóng</button>
                    </div>
                    <div class="p-5 md:max-h-[70vh] overflow-y-auto">
                        <ul class="divide-y divide-gray-100" id="history-list">
                            <li class="py-3">
                                <p class="text-sm text-gray-900">v3 • 31/10/2025 14:00</p>
                                <p class="text-xs text-gray-600">Sửa chính tả đáp án C.</p>
                                <button class="text-xs underline text-gray-700 hover:text-black mt-1">Xem diff</button>
                            </li>
                            <li class="py-3">
                                <p class="text-sm text-gray-900">v2 • 30/10/2025 09:20</p>
                                <p class="text-xs text-gray-600">Thêm hình minh hoạ.</p>
                                <button class="text-xs underline text-gray-700 hover:text-black mt-1">Xem diff</button>
                            </li>
                            <li class="py-3">
                                <p class="text-sm text-gray-900">v1 • 29/10/2025 16:45</p>
                                <p class="text-xs text-gray-600">Tạo mới câu hỏi.</p>
                                <button class="text-xs underline text-gray-700 hover:text-black mt-1">Xem diff</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- MathJax -->
    <script defer src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <script>
        // Modal controls
        const modal = document.getElementById('modal');
        const openBtn = document.getElementById('open-create');
        const closeBtn = document.getElementById('close-modal');
        const titleEl = document.getElementById('modal-title');
        const optionsBox = document.getElementById('options-box');
        const fType = document.getElementById('f-type');
        const addOptionBtn = document.getElementById('add-option');
        const fContent = document.getElementById('f-content');
        const preview = document.getElementById('content-preview');
        const importBtn = document.getElementById('import-btn');
        const importMenu = document.getElementById('import-menu');
        const importFile = document.getElementById('import-file');
        const exportBtn = document.getElementById('export-btn');
        const exportMenu = document.getElementById('export-menu');
        const historyModal = document.getElementById('history-modal');
        const closeHistory = document.getElementById('close-history');

        function openModal(mode = 'create') {
            titleEl.textContent = mode === 'edit' ? 'Chỉnh sửa câu hỏi' : 'Tạo câu hỏi';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            renderOptions();
            renderPreview();
        }
        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        openBtn?.addEventListener('click', () => openModal('create'));
        closeBtn?.addEventListener('click', closeModal);

        // Tags input (toolbar)
        function chipify(containerId, inputId) {
            const box = document.getElementById(containerId);
            const input = document.getElementById(inputId);
            function addChip(text) {
                if (!text.trim()) return;
                const chip = document.createElement('span');
                chip.className = 'px-2 py-1 border border-black rounded-md text-xs text-black';
                chip.textContent = text.trim();
                chip.addEventListener('click', () => chip.remove());
                box.insertBefore(chip, input);
                input.value = '';
            }
            input.addEventListener('keydown', e => {
                if (e.key === 'Enter') { e.preventDefault(); addChip(input.value); }
            });
        }
        chipify('tags-box', 'tag-input');
        chipify('f-tags', 'f-tag-input');

        // Answers builder
        function renderOptions() {
            optionsBox.innerHTML = '';
            const t = fType.value;
            if (t === 'single' || t === 'multi') {
                for (let i = 0; i < 4; i++) addOptionRow();
            } else if (t === 'boolean') {
                optionsBox.innerHTML = `
                    <div class="flex items-center gap-2">
                        <label class="flex items-center gap-2 text-sm text-gray-800"><input type="radio" name="bool" value="true"> Đúng</label>
                        <label class="flex items-center gap-2 text-sm text-gray-800"><input type="radio" name="bool" value="false"> Sai</label>
                    </div>`;
            } else if (t === 'text') {
                optionsBox.innerHTML = `<input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Đáp án đúng (có thể nhiều, cách nhau bằng |)">`;
            } else if (t === 'order') {
                optionsBox.innerHTML = `<div class="space-y-2" id="order-build"></div>`;
                for (let i = 0; i < 4; i++) addOrderRow();
            } else if (t === 'match') {
                optionsBox.innerHTML = `<div class="grid grid-cols-2 gap-3"><div id="left-build" class="space-y-2"></div><div id="right-build" class="space-y-2"></div></div>`;
                for (let i = 0; i < 3; i++) { addMatchRow('left-build'); addMatchRow('right-build'); }
            } else if (t === 'essay') {
                optionsBox.innerHTML = `<p class="text-sm text-gray-700">Câu tự luận: không có đáp án đúng/sai cố định.</p>`;
            }
        }
        function addOptionRow() {
            const t = fType.value;
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2';
            row.innerHTML = `
                ${t === 'single' ? '<input type=\'radio\' name=\'correct\'>' : '<input type=\'checkbox\' class=\'is-correct\'>'}
                <input type="text" class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Nội dung đáp án">
                <button type="button" class="text-xs underline text-gray-700 remove-row">Xoá</button>`;
            row.querySelector('.remove-row').addEventListener('click', () => row.remove());
            optionsBox.appendChild(row);
        }
        function addOrderRow() {
            const box = document.getElementById('order-build');
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2';
            row.innerHTML = `<span class="text-xs text-gray-600">#</span><input type="text" class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Mục"> <button type="button" class="text-xs underline text-gray-700 remove-row">Xoá</button>`;
            row.querySelector('.remove-row').addEventListener('click', () => row.remove());
            box.appendChild(row);
        }
        function addMatchRow(targetId) {
            const box = document.getElementById(targetId);
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2';
            row.innerHTML = `<input type="text" class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Khoá/giá trị"> <button type="button" class="text-xs underline text-gray-700 remove-row">Xoá</button>`;
            row.querySelector('.remove-row').addEventListener('click', () => row.remove());
            box.appendChild(row);
        }
        fType.addEventListener('change', renderOptions);
        addOptionBtn.addEventListener('click', () => {
            const t = fType.value;
            if (t === 'single' || t === 'multi') addOptionRow();
            if (t === 'order') addOrderRow();
            if (t === 'match') { addMatchRow('left-build'); addMatchRow('right-build'); }
        });

        // Content preview with MathJax
        function renderPreview() {
            preview.textContent = fContent.value;
            if (window.MathJax?.typesetPromise) MathJax.typesetPromise([preview]);
        }
        fContent.addEventListener('input', renderPreview);

        // Dummy save
        document.getElementById('save-question')?.addEventListener('click', (e) => {
            e.preventDefault();
            alert('Lưu (UI demo) thành công. Sẽ nối backend sau.');
            closeModal();
        });

        // Edit/Delete placeholders
        document.querySelectorAll('[data-edit]').forEach(btn => btn.addEventListener('click', () => openModal('edit')));
        document.querySelectorAll('[data-delete]').forEach(btn => btn.addEventListener('click', () => confirm('Xoá câu hỏi này?')));

        // Mark faulty toggle
        document.querySelectorAll('[data-flag]').forEach(btn => btn.addEventListener('click', (e) => {
            const row = e.target.closest('tr');
            const badge = row.querySelector('[data-flag-badge]');
            if (badge) {
                const shown = !badge.classList.contains('hidden');
                if (shown) { badge.classList.add('hidden'); btn.textContent = 'Đánh dấu lỗi'; }
                else { badge.classList.remove('hidden'); btn.textContent = 'Bỏ đánh dấu lỗi'; }
            }
        }));

        // History modal
        document.querySelectorAll('[data-history]').forEach(btn => btn.addEventListener('click', () => {
            historyModal.classList.remove('hidden');
            historyModal.classList.add('flex');
        }));
        closeHistory?.addEventListener('click', () => {
            historyModal.classList.add('hidden');
            historyModal.classList.remove('flex');
        });

        // Import/Export menus
        function toggleMenu(menu) { menu.classList.toggle('hidden'); }
        importBtn?.addEventListener('click', () => toggleMenu(importMenu));
        exportBtn?.addEventListener('click', () => toggleMenu(exportMenu));
        importMenu?.querySelectorAll('[data-import]').forEach(item => item.addEventListener('click', (e) => {
            const type = e.target.getAttribute('data-import');
            importFile.setAttribute('data-type', type);
            importFile.click();
            importMenu.classList.add('hidden');
        }));
        importFile?.addEventListener('change', () => {
            const type = importFile.getAttribute('data-type') || 'csv';
            if (importFile.files.length) {
                alert(`Import (${type}) file: ${importFile.files[0].name} (UI demo)`);
                importFile.value = '';
            }
        });
        exportMenu?.querySelectorAll('[data-export]').forEach(item => item.addEventListener('click', (e) => {
            const type = e.target.getAttribute('data-export');
            alert(`Export dạng ${type} (UI demo)`);
            exportMenu.classList.add('hidden');
        }));
    </script>
</x-app-layout>


