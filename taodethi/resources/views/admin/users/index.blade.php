<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Quản trị người dùng & phân quyền') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Toolbar -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-4">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                    <div class="flex items-center gap-3">
                        <button id="open-create" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">Tạo người dùng</button>
                        <button id="bulk-disable" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Vô hiệu hoá</button>
                    </div>
                    <div>
                        <input id="search" type="text" placeholder="Tìm tên/email..." class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <select id="role" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="">Tất cả vai trò</option>
                            <option value="admin">Admin</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
                            <option value="guest">Guest</option>
                        </select>
                        <select id="status" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active">Active</option>
                            <option value="disabled">Disabled</option>
                        </select>
                        <select id="sort" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="newest">Mới nhất</option>
                            <option value="name">Tên</option>
                            <option value="role">Vai trò</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Users table -->
            <div class="bg-white border border-gray-200 sm:rounded-xl overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2"><input type="checkbox" id="pick-all"></th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tên</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Vai trò</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="rows" class="bg-white divide-y divide-gray-100">
                        <tr>
                            <td class="px-3 py-2"><input type="checkbox" class="pick"></td>
                            <td class="px-3 py-2 text-gray-900">Admin One</td>
                            <td class="px-3 py-2 text-gray-700">admin@example.com</td>
                            <td class="px-3 py-2 text-gray-700"><span class="px-2 py-0.5 text-xs border border-black rounded">Admin</span></td>
                            <td class="px-3 py-2 text-gray-700"><span class="px-2 py-0.5 text-xs border border-black rounded">Active</span></td>
                            <td class="px-3 py-2 text-right">
                                <button class="text-xs underline text-gray-700 hover:text-black mr-3" data-edit>Chỉnh sửa</button>
                                <button class="text-xs underline text-gray-700 hover:text-black" data-disable>Vô hiệu</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2"><input type="checkbox" class="pick"></td>
                            <td class="px-3 py-2 text-gray-900">Teacher Demo</td>
                            <td class="px-3 py-2 text-gray-700">teacher@example.com</td>
                            <td class="px-3 py-2 text-gray-700"><span class="px-2 py-0.5 text-xs border border-black rounded">Teacher</span></td>
                            <td class="px-3 py-2 text-gray-700"><span class="px-2 py-0.5 text-xs border border-black rounded">Active</span></td>
                            <td class="px-3 py-2 text-right">
                                <button class="text-xs underline text-gray-700 hover:text-black mr-3" data-edit>Chỉnh sửa</button>
                                <button class="text-xs underline text-gray-700 hover:text-black" data-disable>Vô hiệu</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2"><input type="checkbox" class="pick"></td>
                            <td class="px-3 py-2 text-gray-900">Student Demo</td>
                            <td class="px-3 py-2 text-gray-700">student@example.com</td>
                            <td class="px-3 py-2 text-gray-700"><span class="px-2 py-0.5 text-xs border border-black rounded">Student</span></td>
                            <td class="px-3 py-2 text-gray-700"><span class="px-2 py-0.5 text-xs border border-black rounded">Active</span></td>
                            <td class="px-3 py-2 text-right">
                                <button class="text-xs underline text-gray-700 hover:text-black mr-3" data-edit>Chỉnh sửa</button>
                                <button class="text-xs underline text-gray-700 hover:text-black" data-disable>Vô hiệu</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="px-3 py-2 text-sm text-gray-700">Trang 1/1</div>
            </div>

            <!-- Create/Edit modal -->
            <div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4">
                <div class="bg-white w-full max-w-xl border border-gray-200 sm:rounded-xl p-0 overflow-hidden shadow-xl">
                    <div class="px-5 py-3 border-b border-gray-200 flex items-center justify-between">
                        <h3 id="modal-title" class="text-base font-semibold text-gray-900">Tạo người dùng</h3>
                        <button id="close-modal" class="text-sm text-gray-600 hover:text-black">Đóng</button>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Tên</label>
                            <input id="f-name" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Email</label>
                            <input id="f-email" type="email" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Vai trò</label>
                                <select id="f-role" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                    <option value="admin">Admin</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="student">Student</option>
                                    <option value="guest">Guest</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Trạng thái</label>
                                <select id="f-status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                    <option value="active">Active</option>
                                    <option value="disabled">Disabled</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Mật khẩu (tạm)</label>
                            <input id="f-pass" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Tuỳ chọn - sẽ gửi link đặt lại">
                        </div>
                    </div>
                    <div class="px-5 py-3 border-t border-gray-200 flex items-center justify-end gap-3">
                        <button id="save-user" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">Lưu</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modal');
        const openBtn = document.getElementById('open-create');
        const closeBtn = document.getElementById('close-modal');
        const modalTitle = document.getElementById('modal-title');
        openBtn?.addEventListener('click', ()=>{ modalTitle.textContent='Tạo người dùng'; modal.classList.remove('hidden'); modal.classList.add('flex'); });
        closeBtn?.addEventListener('click', ()=>{ modal.classList.add('hidden'); modal.classList.remove('flex'); });
        document.querySelectorAll('[data-edit]').forEach(b=>b.addEventListener('click', ()=>{ modalTitle.textContent='Chỉnh sửa người dùng'; modal.classList.remove('hidden'); modal.classList.add('flex'); }));
        document.getElementById('save-user')?.addEventListener('click', ()=>{ alert('Lưu người dùng (UI demo)'); modal.classList.add('hidden'); modal.classList.remove('flex'); });
        document.getElementById('bulk-disable')?.addEventListener('click', ()=> alert('Đã vô hiệu (UI demo)'));
        document.getElementById('pick-all')?.addEventListener('change', (e)=>{ document.querySelectorAll('.pick').forEach(c=> c.checked = e.target.checked); });
    </script>
</x-app-layout>


