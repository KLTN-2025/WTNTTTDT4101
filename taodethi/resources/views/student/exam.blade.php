<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Làm bài trắc nghiệm') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Thanh công cụ: hẹn giờ + proctoring -->
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="border border-gray-200 rounded-lg px-4 py-2">
                        <p class="text-xs text-gray-600">Thời gian còn lại</p>
                        <div id="timer" class="text-lg font-semibold text-gray-900">--:--:--</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" id="start-timer" class="inline-flex items-center px-3 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">Bắt đầu</button>
                        <button type="button" id="pause-timer" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">Tạm dừng</button>
                        <button type="button" id="resume-timer" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">Tiếp tục</button>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 text-sm text-gray-800">
                        <input type="checkbox" id="lock-camera" class="border-gray-400"> Khóa camera khi làm bài
                    </label>
                    <div class="flex items-center gap-2">
                        <button type="button" id="toggle-camera" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">Bật camera</button>
                        <span id="camera-status" class="text-xs text-gray-600">Camera: Tắt</span>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <video id="camera-preview" class="w-48 h-36 bg-gray-100 border border-gray-200 rounded-lg" autoplay playsinline muted></video>
            </div>

            <form id="exam-form" class="space-y-6">
                <!-- Trắc nghiệm 4 đáp án (đơn chọn) -->
                <div class="bg-white border border-gray-200 sm:rounded-xl p-6 question" data-qkey="q1" data-type="single">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">1) Chọn đáp án đúng (đơn chọn)</h3>
                    <p class="text-sm text-gray-700 mb-4">2 + 2 = ?</p>
                    <div class="space-y-2 options" data-correct="B">
                        <label class="flex items-center gap-3 text-sm text-gray-800 option">
                            <input type="radio" name="q1" class="border-gray-400" value="A"> <span>A) 3</span>
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-800 option">
                            <input type="radio" name="q1" class="border-gray-400" value="B"> <span>B) 4</span>
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-800 option">
                            <input type="radio" name="q1" class="border-gray-400" value="C"> <span>C) 5</span>
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-800 option">
                            <input type="radio" name="q1" class="border-gray-400" value="D"> <span>D) 6</span>
                        </label>
                    </div>
                    <div class="mt-4">
                        <button type="button" class="text-xs underline text-gray-600 hover:text-black" data-feedback-toggle>Ghi chú / Báo lỗi</button>
                        <div class="mt-2 hidden" data-feedback-box>
                            <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" rows="2" placeholder="Mô tả vấn đề hoặc thắc mắc..."></textarea>
                            <div class="mt-2 flex justify-end">
                                <button type="button" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Gửi</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nhiều đáp án (multi-select) -->
                <div class="bg-white border border-gray-200 sm:rounded-xl p-6 question" data-qkey="q2" data-type="multi">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">2) Chọn tất cả đáp án đúng (nhiều đáp án)</h3>
                    <p class="text-sm text-gray-700 mb-4">Số nguyên tố nhỏ hơn 10?</p>
                    <div class="space-y-2 options" data-correct="2,3,5,7">
                        <label class="flex items-center gap-3 text-sm text-gray-800 option">
                            <input type="checkbox" name="q2[]" class="border-gray-400" value="2"> <span>2</span>
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-800 option">
                            <input type="checkbox" name="q2[]" class="border-gray-400" value="3"> <span>3</span>
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-800 option">
                            <input type="checkbox" name="q2[]" class="border-gray-400" value="4"> <span>4</span>
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-800 option">
                            <input type="checkbox" name="q2[]" class="border-gray-400" value="5"> <span>5</span>
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-800 option">
                            <input type="checkbox" name="q2[]" class="border-gray-400" value="7"> <span>7</span>
                        </label>
                    </div>
                    <div class="mt-4">
                        <button type="button" class="text-xs underline text-gray-600 hover:text-black" data-feedback-toggle>Ghi chú / Báo lỗi</button>
                        <div class="mt-2 hidden" data-feedback-box>
                            <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" rows="2" placeholder="Mô tả vấn đề hoặc thắc mắc..."></textarea>
                            <div class="mt-2 flex justify-end">
                                <button type="button" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Gửi</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Điền từ (fill in the blank) -->
                <div class="bg-white border border-gray-200 sm:rounded-xl p-6 question" data-qkey="q3" data-type="text">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">3) Điền vào chỗ trống</h3>
                    <p class="text-sm text-gray-700 mb-4">Thủ đô của Việt Nam là <span class="text-gray-500">(điền 1 từ)</span>.</p>
                    <input type="text" name="q3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black" placeholder="Nhập câu trả lời..." data-correct="Hà Nội|Ha Noi|Hanoi">
                    <div class="mt-4">
                        <button type="button" class="text-xs underline text-gray-600 hover:text-black" data-feedback-toggle>Ghi chú / Báo lỗi</button>
                        <div class="mt-2 hidden" data-feedback-box>
                            <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" rows="2" placeholder="Mô tả vấn đề hoặc thắc mắc..."></textarea>
                            <div class="mt-2 flex justify-end">
                                <button type="button" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Gửi</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Đúng / Sai -->
                <div class="bg-white border border-gray-200 sm:rounded-xl p-6 question" data-qkey="q4" data-type="boolean">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">4) Đúng / Sai</h3>
                    <p class="text-sm text-gray-700 mb-4">Nước sôi ở 100°C (ở mực nước biển).</p>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 text-sm text-gray-800">
                            <input type="radio" name="q4" value="true" class="border-gray-400"> Đúng
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-800">
                            <input type="radio" name="q4" value="false" class="border-gray-400"> Sai
                        </label>
                    </div>
                    <input type="hidden" data-correct="true">
                    <div class="mt-4">
                        <button type="button" class="text-xs underline text-gray-600 hover:text-black" data-feedback-toggle>Ghi chú / Báo lỗi</button>
                        <div class="mt-2 hidden" data-feedback-box>
                            <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" rows="2" placeholder="Mô tả vấn đề hoặc thắc mắc..."></textarea>
                            <div class="mt-2 flex justify-end">
                                <button type="button" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Gửi</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kéo - thả (drag & drop ordering) -->
                <div class="bg-white border border-gray-200 sm:rounded-xl p-6 question" data-qkey="q5" data-type="order">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">5) Sắp xếp theo thứ tự tăng dần (kéo - thả)</h3>
                    <ul id="order-list" class="space-y-2">
                        <li draggable="true" class="cursor-move px-3 py-2 border border-gray-300 rounded text-sm text-gray-800 bg-white">8</li>
                        <li draggable="true" class="cursor-move px-3 py-2 border border-gray-300 rounded text-sm text-gray-800 bg-white">3</li>
                        <li draggable="true" class="cursor-move px-3 py-2 border border-gray-300 rounded text-sm text-gray-800 bg-white">5</li>
                        <li draggable="true" class="cursor-move px-3 py-2 border border-gray-300 rounded text-sm text-gray-800 bg-white">1</li>
                    </ul>
                    <input type="hidden" id="order-correct" value="1,3,5,8">
                    <div class="mt-4">
                        <button type="button" class="text-xs underline text-gray-600 hover:text-black" data-feedback-toggle>Ghi chú / Báo lỗi</button>
                        <div class="mt-2 hidden" data-feedback-box>
                            <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" rows="2" placeholder="Mô tả vấn đề hoặc thắc mắc..."></textarea>
                            <div class="mt-2 flex justify-end">
                                <button type="button" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Gửi</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Match pairs (ghép cặp) -->
                <div class="bg-white border border-gray-200 sm:rounded-xl p-6 question" data-qkey="q6" data-type="match">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">6) Ghép cặp khái niệm</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <ul class="space-y-2" id="left-items">
                                <li data-key="HN" class="px-3 py-2 border border-gray-300 rounded text-sm text-gray-800">Hà Nội</li>
                                <li data-key="HCM" class="px-3 py-2 border border-gray-300 rounded text-sm text-gray-800">TP.HCM</li>
                                <li data-key="DN" class="px-3 py-2 border border-gray-300 rounded text-sm text-gray-800">Đà Nẵng</li>
                            </ul>
                        </div>
                        <div>
                            <ul class="space-y-2" id="right-items">
                                <li data-key="DN" class="px-3 py-2 border border-gray-300 rounded text-sm text-gray-800">Miền Trung</li>
                                <li data-key="HCM" class="px-3 py-2 border border-gray-300 rounded text-sm text-gray-800">Miền Nam</li>
                                <li data-key="HN" class="px-3 py-2 border border-gray-300 rounded text-sm text-gray-800">Miền Bắc</li>
                            </ul>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 mt-3">Nhấp chọn mỗi bên để ghép cặp, nhấn "Xóa ghép" để bỏ chọn.</p>
                    <div class="mt-3 flex items-center gap-3">
                        <button type="button" id="match-btn" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-white hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">Ghép</button>
                        <button type="button" id="unmatch-btn" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">Xóa ghép</button>
                    </div>
                    <div id="match-result" class="mt-3 text-sm text-gray-800"></div>
                    <input type="hidden" id="match-correct" value='{"HN":"Miền Bắc","HCM":"Miền Nam","DN":"Miền Trung"}'>
                    <div class="mt-4">
                        <button type="button" class="text-xs underline text-gray-600 hover:text-black" data-feedback-toggle>Ghi chú / Báo lỗi</button>
                        <div class="mt-2 hidden" data-feedback-box>
                            <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" rows="2" placeholder="Mô tả vấn đề hoặc thắc mắc..."></textarea>
                            <div class="mt-2 flex justify-end">
                                <button type="button" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Gửi</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tự luận -->
                <div class="bg-white border border-gray-200 sm:rounded-xl p-6 question" data-qkey="q7" data-type="essay">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">7) Câu hỏi tự luận</h3>
                    <p class="text-sm text-gray-700 mb-2">Trình bày cảm nhận của bạn về một thói quen học tập hiệu quả (8-10 câu).</p>
                    <textarea name="q7" rows="6" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black" placeholder="Nhập bài làm của bạn..."></textarea>
                    <div class="mt-4">
                        <button type="button" class="text-xs underline text-gray-600 hover:text-black" data-feedback-toggle>Ghi chú / Báo lỗi</button>
                        <div class="mt-2 hidden" data-feedback-box>
                            <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" rows="2" placeholder="Mô tả vấn đề hoặc thắc mắc..."></textarea>
                            <div class="mt-2 flex justify-end">
                                <button type="button" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Gửi</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media + Công thức -->
                <div class="bg-white border border-gray-200 sm:rounded-xl p-6 question" data-qkey="q8" data-type="media">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">8) Câu hỏi kèm media + công thức</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <img src="https://via.placeholder.com/300x180?text=Anh+minh+ho%E1%BA%A1" alt="minh hoạ" class="w-full h-40 object-cover border border-gray-200 rounded">
                        <audio controls class="w-full">
                            <source src="" type="audio/mpeg">
                        </audio>
                        <video controls class="w-full h-40 bg-black/5 border border-gray-200 rounded"></video>
                    </div>
                    <p class="text-sm text-gray-700 mb-2">Công thức: \( a^2 + b^2 = c^2 \)</p>
                    <p class="text-sm text-gray-700 mb-4">Công thức tích phân: \\int_0^1 x^2 \\, dx = \(\frac{1}{3}\)</p>
                    <div class="space-y-2 options" data-correct="C">
                        <label class="flex items-center gap-3 text-sm text-gray-800 option">
                            <input type="radio" name="q8" class="border-gray-400" value="A"> <span>A) Sai</span>
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-800 option">
                            <input type="radio" name="q8" class="border-gray-400" value="B"> <span>B) Không đủ dữ kiện</span>
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-800 option">
                            <input type="radio" name="q8" class="border-gray-400" value="C"> <span>C) Đúng</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <x-secondary-button type="button" id="save-draft" class="mr-3">Lưu nháp</x-secondary-button>
                    <x-button id="submit-exam">Nộp bài</x-button>
                </div>
            </form>

            <!-- Kết quả & Giải thích -->
            <div id="result-panel" class="hidden mt-8 bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Kết quả</h3>
                    <a href="#" id="review-toggle" class="text-xs underline text-gray-600 hover:text-black">Hiện/Ẩn giải thích</a>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-800">Điểm: <span id="score" class="font-semibold text-gray-900">0</span></p>
                </div>
                <div id="explanations" class="space-y-4 hidden">
                    <!-- sẽ render động theo câu hỏi -->
                </div>
            </div>
        </div>
    </div>

    <!-- MathJax for formulas -->
    <script defer src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    <script>
        // Drag & drop ordering (đơn giản)
        const list = document.getElementById('order-list');
        if (list) {
            let dragEl = null;
            list.querySelectorAll('li').forEach(li => {
                li.addEventListener('dragstart', e => { dragEl = li; li.classList.add('opacity-60'); });
                li.addEventListener('dragend', e => { dragEl = null; li.classList.remove('opacity-60'); });
                li.addEventListener('dragover', e => { e.preventDefault(); });
                li.addEventListener('drop', e => {
                    e.preventDefault();
                    if (!dragEl || dragEl === li) return;
                    const items = Array.from(list.children);
                    const dragIdx = items.indexOf(dragEl);
                    const dropIdx = items.indexOf(li);
                    if (dragIdx < dropIdx) list.insertBefore(dragEl, li.nextSibling);
                    else list.insertBefore(dragEl, li);
                });
            });
        }

        // Match pairs
        let leftSelected = null, rightSelected = null;
        const left = document.getElementById('left-items');
        const right = document.getElementById('right-items');
        const result = document.getElementById('match-result');
        const pairs = new Map(); // key -> key

        function renderPairs() {
            result.innerHTML = '';
            pairs.forEach((rv, lv) => {
                const row = document.createElement('div');
                row.className = 'text-sm text-gray-800';
                row.textContent = `${lv} ↔ ${rv}`;
                result.appendChild(row);
            });
        }

        function clearActive(list) {
            if (!list) return;
            list.querySelectorAll('li').forEach(li => li.classList.remove('ring-2', 'ring-black'));
        }

        function activate(li) { li.classList.add('ring-2', 'ring-black'); }

        if (left && right) {
            left.addEventListener('click', e => {
                const li = e.target.closest('li');
                if (!li) return;
                clearActive(left);
                leftSelected = li.getAttribute('data-key');
                activate(li);
            });
            right.addEventListener('click', e => {
                const li = e.target.closest('li');
                if (!li) return;
                clearActive(right);
                rightSelected = li.getAttribute('data-key');
                activate(li);
            });

            document.getElementById('match-btn').addEventListener('click', () => {
                if (!leftSelected || !rightSelected) return;
                pairs.set(leftSelected, rightSelected);
                clearActive(left); clearActive(right);
                leftSelected = rightSelected = null;
                renderPairs();
            });
            document.getElementById('unmatch-btn').addEventListener('click', () => {
                if (leftSelected) pairs.delete(leftSelected);
                clearActive(left); clearActive(right);
                leftSelected = rightSelected = null;
                renderPairs();
            });
        }

        // Random hóa: câu hỏi và phương án trong mỗi câu
        function shuffle(elements) {
            const arr = Array.from(elements);
            for (let i = arr.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [arr[i], arr[j]] = [arr[j], arr[i]];
            }
            return arr;
        }
        const formEl = document.getElementById('exam-form');
        if (formEl) {
            // shuffle options
            formEl.querySelectorAll('.options').forEach(opt => {
                const parent = opt;
                const shuffled = shuffle(parent.querySelectorAll('.option'));
                shuffled.forEach(el => parent.appendChild(el));
            });
            // shuffle questions (except toolbars)
            const qContainer = formEl;
            const qs = shuffle(qContainer.querySelectorAll('.question'));
            qs.forEach(el => qContainer.appendChild(el));
        }

        // Hẹn giờ: đơn giản (ví dụ 30 phút)
        let remaining = 30 * 60; // 30 phút
        let timerId = null;
        function renderTimer() {
            const h = Math.floor(remaining / 3600).toString().padStart(2, '0');
            const m = Math.floor((remaining % 3600) / 60).toString().padStart(2, '0');
            const s = Math.floor(remaining % 60).toString().padStart(2, '0');
            const t = document.getElementById('timer');
            if (t) t.textContent = `${h}:${m}:${s}`;
        }
        function tick() {
            remaining = Math.max(0, remaining - 1);
            renderTimer();
            if (remaining === 0) {
                clearInterval(timerId);
                timerId = null;
                submitExam();
            }
        }
        document.getElementById('start-timer')?.addEventListener('click', () => {
            if (timerId) return;
            timerId = setInterval(tick, 1000);
        });
        document.getElementById('pause-timer')?.addEventListener('click', () => {
            if (!timerId) return;
            clearInterval(timerId);
            timerId = null;
        });
        document.getElementById('resume-timer')?.addEventListener('click', () => {
            if (timerId) return;
            timerId = setInterval(tick, 1000);
        });
        renderTimer();

        // Lưu nháp / Resume bằng localStorage
        function serializeAnswers() {
            const data = {};
            // single & boolean
            formEl.querySelectorAll('[data-type="single"], [data-type="boolean"]').forEach(q => {
                const name = q.getAttribute('data-qkey');
                const checked = q.querySelector('input[type="radio"]:checked');
                data[name] = checked ? checked.value : null;
            });
            // multi
            formEl.querySelectorAll('[data-type="multi"]').forEach(q => {
                const name = q.getAttribute('data-qkey');
                const vals = [];
                q.querySelectorAll('input[type="checkbox"]:checked').forEach(c => vals.push(c.value));
                data[name] = vals;
            });
            // text
            formEl.querySelectorAll('[data-type="text"]').forEach(q => {
                const name = q.getAttribute('data-qkey');
                const input = q.querySelector('input[type="text"]');
                data[name] = input?.value || '';
            });
            // order
            const orderList = document.getElementById('order-list');
            if (orderList) {
                data['q5'] = Array.from(orderList.children).map(li => li.textContent.trim());
            }
            // match
            data['q6'] = Array.from(pairs.entries());
            // essay
            const essay = formEl.querySelector('[data-type="essay"] textarea');
            data['q7'] = essay?.value || '';
            // q8
            const q8 = formEl.querySelector('input[name="q8"]:checked');
            data['q8'] = q8 ? q8.value : null;
            // timer
            data['remaining'] = remaining;
            return data;
        }
        function restoreAnswers(data) {
            if (!data) return;
            // single & boolean
            ['q1','q4','q8'].forEach(k => {
                if (data[k]) {
                    const el = formEl.querySelector(`input[name="${k}"][value="${data[k]}"]`);
                    if (el) el.checked = true;
                }
            });
            // multi
            if (Array.isArray(data['q2'])) {
                data['q2'].forEach(v => {
                    const el = formEl.querySelector(`input[name="q2[]"][value="${v}"]`);
                    if (el) el.checked = true;
                });
            }
            // text
            if (typeof data['q3'] === 'string') {
                const input = formEl.querySelector('[data-type="text"] input[type="text"]');
                if (input) input.value = data['q3'];
            }
            // order
            if (Array.isArray(data['q5'])) {
                const orderList = document.getElementById('order-list');
                if (orderList) {
                    const map = {};
                    Array.from(orderList.children).forEach(li => map[li.textContent.trim()] = li);
                    data['q5'].forEach(v => { if (map[v]) orderList.appendChild(map[v]); });
                }
            }
            // match
            if (Array.isArray(data['q6'])) {
                pairs.clear();
                data['q6'].forEach(([k,v]) => pairs.set(k, v));
                renderPairs();
            }
            // essay
            if (typeof data['q7'] === 'string') {
                const essay = formEl.querySelector('[data-type="essay"] textarea');
                if (essay) essay.value = data['q7'];
            }
            // timer
            if (typeof data['remaining'] === 'number') {
                remaining = data['remaining'];
                renderTimer();
            }
        }
        const DRAFT_KEY = 'exam_draft_v1';
        document.getElementById('save-draft')?.addEventListener('click', () => {
            localStorage.setItem(DRAFT_KEY, JSON.stringify(serializeAnswers()));
            alert('Đã lưu nháp trên trình duyệt.');
        });
        restoreAnswers(JSON.parse(localStorage.getItem(DRAFT_KEY) || 'null'));

        // Proctoring: camera
        let stream = null;
        const preview = document.getElementById('camera-preview');
        const toggleBtn = document.getElementById('toggle-camera');
        const statusEl = document.getElementById('camera-status');
        const lockCam = document.getElementById('lock-camera');
        async function enableCam() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                preview.srcObject = stream;
                if (statusEl) statusEl.textContent = 'Camera: Bật';
                if (toggleBtn) toggleBtn.textContent = 'Tắt camera';
            } catch (e) {
                alert('Không thể bật camera.');
            }
        }
        function disableCam() {
            if (stream) stream.getTracks().forEach(t => t.stop());
            stream = null;
            if (preview) preview.srcObject = null;
            if (statusEl) statusEl.textContent = 'Camera: Tắt';
            if (toggleBtn) toggleBtn.textContent = 'Bật camera';
        }
        toggleBtn?.addEventListener('click', () => {
            if (stream) disableCam(); else enableCam();
        });

        // Nộp bài + chấm điểm cơ bản + hiển thị giải thích
        function gradeExam() {
            let score = 0, total = 0;
            const details = [];

            // q1 single
            total++;
            const q1 = formEl.querySelector('input[name="q1"]:checked')?.value;
            const q1c = formEl.querySelector('[data-qkey="q1"] .options')?.getAttribute('data-correct');
            const q1ok = q1 === q1c;
            if (q1ok) score++;
            details.push({ key: 'q1', correct: q1ok, explain: '2 + 2 = 4.' });

            // q2 multi
            total++;
            const picks = [];
            formEl.querySelectorAll('input[name="q2[]"]:checked').forEach(c => picks.push(c.value));
            const q2c = (formEl.querySelector('[data-qkey="q2"] .options')?.getAttribute('data-correct') || '').split(',');
            const q2ok = picks.sort().join(',') === q2c.sort().join(',');
            if (q2ok) score++;
            details.push({ key: 'q2', correct: q2ok, explain: 'Số nguyên tố < 10 là 2, 3, 5, 7.' });

            // q3 text
            total++;
            const q3 = formEl.querySelector('[data-qkey="q3"] input[type="text"]').value.trim();
            const q3alts = (formEl.querySelector('[data-qkey="q3"] input[type="text"]').getAttribute('data-correct') || '').split('|').map(s => s.trim().toLowerCase());
            const q3ok = q3alts.includes(q3.toLowerCase());
            if (q3ok) score++;
            details.push({ key: 'q3', correct: q3ok, explain: 'Thủ đô là Hà Nội.' });

            // q4 boolean
            total++;
            const q4 = formEl.querySelector('input[name="q4"]:checked')?.value;
            const q4c = formEl.querySelector('[data-qkey="q4"] [data-correct]')?.getAttribute('data-correct');
            const q4ok = q4 === q4c;
            if (q4ok) score++;
            details.push({ key: 'q4', correct: q4ok, explain: 'Ở mực nước biển, nước sôi khoảng 100°C.' });

            // q5 order
            total++;
            const order = Array.from(document.querySelectorAll('#order-list li')).map(li => li.textContent.trim());
            const orderC = document.getElementById('order-correct')?.value.split(',');
            const q5ok = order.join(',') === orderC.join(',');
            if (q5ok) score++;
            details.push({ key: 'q5', correct: q5ok, explain: 'Thứ tự tăng dần: 1, 3, 5, 8.' });

            // q6 match
            total++;
            const correctMap = JSON.parse(document.getElementById('match-correct').value);
            let allMatch = true;
            for (const [lk, rv] of pairs.entries()) {
                if (correctMap[lk] !== rv) { allMatch = false; break; }
            }
            const q6ok = allMatch && pairs.size === Object.keys(correctMap).length;
            if (q6ok) score++;
            details.push({ key: 'q6', correct: q6ok, explain: 'HN↔Miền Bắc, HCM↔Miền Nam, DN↔Miền Trung.' });

            // q7 essay (không tự chấm)
            total++;
            const q7ok = false;
            details.push({ key: 'q7', correct: q7ok, explain: 'Câu tự luận chấm thủ công/ bán tự động.' });

            // q8 media single
            total++;
            const q8 = formEl.querySelector('input[name="q8"]:checked')?.value;
            const q8c = formEl.querySelector('[data-qkey="q8"] .options')?.getAttribute('data-correct');
            const q8ok = q8 === q8c;
            if (q8ok) score++;
            details.push({ key: 'q8', correct: q8ok, explain: 'Công thức hiển thị bằng MathJax.' });

            return { score, total, details };
        }

        function buildExplanations(details) {
            const box = document.getElementById('explanations');
            box.innerHTML = '';
            details.forEach((d, idx) => {
                const row = document.createElement('div');
                row.className = 'border border-gray-200 rounded-lg p-4';
                row.innerHTML = `<p class="text-sm text-gray-900 font-semibold">Câu ${idx+1}: ${d.correct ? '<span class=\'text-black\'>Đúng</span>' : '<span class=\'text-gray-700\'>Sai</span>'}</p>
                                  <p class="text-sm text-gray-700 mt-1">${d.explain}</p>`;
                box.appendChild(row);
            });
        }

        function submitExam() {
            // proctoring gating
            if (document.getElementById('lock-camera')?.checked) {
                if (!stream) {
                    alert('Bạn cần bật camera để nộp bài.');
                    return;
                }
            }
            const { score, total, details } = gradeExam();
            document.getElementById('score').textContent = `${score}/${total}`;
            buildExplanations(details);
            document.getElementById('result-panel').classList.remove('hidden');
            document.getElementById('explanations').classList.add('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        document.getElementById('submit-exam')?.addEventListener('click', (e) => { e.preventDefault(); submitExam(); });
        document.getElementById('review-toggle')?.addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('explanations').classList.toggle('hidden');
        });

        // Feedback toggle buttons
        document.querySelectorAll('[data-feedback-toggle]').forEach(btn => {
            btn.addEventListener('click', () => {
                const box = btn.parentElement.querySelector('[data-feedback-box]');
                if (box) box.classList.toggle('hidden');
            });
        });
    </script>
</x-app-layout>


