<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Lập lịch thi / mở-đóng đề') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white border border-gray-200 sm:rounded-xl overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Mẫu đề</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Thời gian</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="exam-rows" class="bg-white divide-y divide-gray-100">
                        <tr>
                            <td class="px-3 py-2 text-gray-900">Toán GK 10</td>
                            <td class="px-3 py-2 text-gray-700">31/10/2025 15:00 → 31/10/2025 16:00</td>
                            <td class="px-3 py-2 text-gray-700"><span class="px-2 py-0.5 text-xs border border-black rounded" data-status>Đóng</span></td>
                            <td class="px-3 py-2 text-right">
                                <button class="text-xs underline text-gray-700 hover:text-black mr-3" data-edit>Chỉnh sửa</button>
                                <button class="text-xs underline text-gray-700 hover:text-black" data-toggle>Mở đề</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 text-gray-900">Anh HK 10</td>
                            <td class="px-3 py-2 text-gray-700">02/11/2025 08:00 → 02/11/2025 09:00</td>
                            <td class="px-3 py-2 text-gray-700"><span class="px-2 py-0.5 text-xs border border-black rounded" data-status>Đang mở</span></td>
                            <td class="px-3 py-2 text-right">
                                <button class="text-xs underline text-gray-700 hover:text-black mr-3" data-edit>Chỉnh sửa</button>
                                <button class="text-xs underline text-gray-700 hover:text-black" data-toggle>Đóng đề</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Edit schedule modal -->
            <div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4">
                <div class="bg-white w-full max-w-xl border border-gray-200 sm:rounded-xl p-0 overflow-hidden shadow-xl">
                    <div class="px-5 py-3 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-900">Chỉnh lịch</h3>
                        <button id="close-modal" class="text-sm text-gray-600 hover:text-black">Đóng</button>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bắt đầu</label>
                            <input type="datetime-local" id="start" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Kết thúc</label>
                            <input type="datetime-local" id="end" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Thời lượng (phút)</label>
                            <input type="number" id="duration" min="1" value="60" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Số lần làm tối đa</label>
                            <input type="number" id="attempts" min="1" value="1" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div class="px-5 py-3 border-t border-gray-200 flex items-center justify-end gap-3">
                        <button id="save" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">Lưu</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modal');
        const closeBtn = document.getElementById('close-modal');
        document.querySelectorAll('[data-edit]').forEach(b => b.addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }));
        closeBtn?.addEventListener('click', () => { modal.classList.add('hidden'); modal.classList.remove('flex'); });
        document.getElementById('save')?.addEventListener('click', () => { alert('Đã lưu lịch (UI demo)'); modal.classList.add('hidden'); modal.classList.remove('flex'); });

        document.querySelectorAll('[data-toggle]').forEach(b => b.addEventListener('click', (e) => {
            const row = e.target.closest('tr');
            const badge = row.querySelector('[data-status]');
            const open = badge.textContent.includes('Đang mở');
            if (open) { badge.textContent = 'Đóng'; e.target.textContent = 'Mở đề'; }
            else { badge.textContent = 'Đang mở'; e.target.textContent = 'Đóng đề'; }
        }));
    </script>
</x-app-layout>


