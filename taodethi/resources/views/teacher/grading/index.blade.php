<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Chấm thi') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Toolbar -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-4">
                <div class="flex flex-col md:flex-row md:items-center gap-3">
                    <div class="flex-1">
                        <input type="text" id="search" placeholder="Tìm học sinh/đề thi..." class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="auto-grade" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">Tự chấm trắc nghiệm</button>
                        <button id="refresh" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Làm mới</button>
                    </div>
                </div>
            </div>

            <!-- Submissions list -->
            <div class="bg-white border border-gray-200 sm:rounded-xl overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Học sinh</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Đề</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Trắc nghiệm</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tự luận</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="rows" class="bg-white divide-y divide-gray-100">
                        <tr>
                            <td class="px-3 py-2 text-gray-900">Nguyễn A</td>
                            <td class="px-3 py-2 text-gray-700">Toán GK 10</td>
                            <td class="px-3 py-2 text-gray-700">8/10</td>
                            <td class="px-3 py-2 text-gray-700"><span class="px-2 py-0.5 text-xs border border-black rounded">Chưa chấm</span></td>
                            <td class="px-3 py-2 text-right"><button class="text-xs underline text-gray-700 hover:text-black" data-review>Mở review</button></td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 text-gray-900">Trần B</td>
                            <td class="px-3 py-2 text-gray-700">Toán GK 10</td>
                            <td class="px-3 py-2 text-gray-700">9/10</td>
                            <td class="px-3 py-2 text-gray-700">8.0/10</td>
                            <td class="px-3 py-2 text-right"><button class="text-xs underline text-gray-700 hover:text-black" data-review>Mở review</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Review panel -->
            <div id="review-panel" class="hidden bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Review bài: <span id="rv-student" class="font-normal text-gray-800">—</span></h3>
                    <button id="close-review" class="text-sm text-gray-600 hover:text-black">Đóng</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-4">
                        <!-- Essay item -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <p class="text-sm text-gray-900 font-semibold mb-2">Câu tự luận 1</p>
                            <p class="text-xs text-gray-600 mb-2">Gợi ý đáp án:</p>
                            <div class="text-sm text-gray-800 border border-gray-100 rounded p-3 mb-3">Liệt kê bước giải, nêu ví dụ, kết luận rõ ràng.</div>
                            <p class="text-xs text-gray-600 mb-1">Bài làm học sinh:</p>
                            <div class="text-sm text-gray-800 border border-gray-100 rounded p-3 mb-3 max-h-40 overflow-y-auto">[Nội dung tự luận mẫu...]</div>
                            <div class="flex items-center gap-3">
                                <label class="text-sm text-gray-800">Điểm:</label>
                                <input id="rv-score" type="number" min="0" max="10" step="0.5" value="8" class="w-24 border border-gray-300 rounded-md px-3 py-2 text-sm">
                                <button class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white" id="save-essay">Lưu</button>
                            </div>
                        </div>
                        <!-- Comments -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <p class="text-sm text-gray-900 font-semibold mb-2">Ghi chú cho bài thi</p>
                            <textarea rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Nhận xét cho học sinh..."></textarea>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <p class="text-sm text-gray-900 font-semibold mb-2">Tổng quan</p>
                            <p class="text-sm text-gray-800">Trắc nghiệm: <span class="font-semibold">8/10</span></p>
                            <p class="text-sm text-gray-800">Tự luận: <span class="font-semibold" id="essay-pt">8.0/10</span></p>
                            <p class="text-sm text-gray-900 mt-1">Tổng: <span class="font-semibold" id="sum-pt">16.0/20</span></p>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <p class="text-sm text-gray-900 font-semibold mb-2">Hành động</p>
                            <button class="w-full inline-flex items-center justify-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">Hoàn tất chấm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('auto-grade')?.addEventListener('click', () => alert('Đã tự chấm phần trắc nghiệm (UI demo).'));
        const panel = document.getElementById('review-panel');
        const essayPt = document.getElementById('essay-pt');
        const sumPt = document.getElementById('sum-pt');
        document.querySelectorAll('[data-review]').forEach(b => b.addEventListener('click', (e) => {
            const row = e.target.closest('tr');
            document.getElementById('rv-student').textContent = row.children[0].textContent.trim();
            panel.classList.remove('hidden');
        }));
        document.getElementById('close-review')?.addEventListener('click', () => panel.classList.add('hidden'));
        document.getElementById('save-essay')?.addEventListener('click', () => {
            const v = Number(document.getElementById('rv-score').value || 0);
            essayPt.textContent = `${v.toFixed(1)}/10`;
            const mcq = 8; // demo
            sumPt.textContent = `${(mcq + v).toFixed(1)}/20`;
            alert('Đã lưu điểm tự luận (UI demo).');
        });
    </script>
</x-app-layout>


