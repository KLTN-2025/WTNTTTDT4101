<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Bảo mật & chống gian lận') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Security toggles -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Thiết lập an toàn</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-800">
                    <label class="flex items-center gap-2"><input type="checkbox" id="t-camera" class="border-gray-400"> Bắt buộc bật camera khi thi</label>
                    <label class="flex items-center gap-2"><input type="checkbox" id="t-tabs" class="border-gray-400"> Chặn chuyển tab (cảnh báo/khoá)</label>
                    <label class="flex items-center gap-2"><input type="checkbox" id="t-fullscreen" class="border-gray-400"> Yêu cầu fullscreen</label>
                    <label class="flex items-center gap-2"><input type="checkbox" id="t-ip-bind" class="border-gray-400"> Khoá IP theo lịch thi</label>
                </div>
                <div class="mt-4">
                    <button id="save-settings" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">Lưu thiết lập</button>
                </div>
            </div>

            <!-- IP logging / whitelist -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">IP logging & Whitelist</h3>
                    <div class="flex items-center gap-2">
                        <input id="ip-input" type="text" placeholder="Thêm IP (vd: 203.0.113.10)" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <button id="add-ip" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Thêm</button>
                    </div>
                </div>
                <ul id="ip-list" class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm text-gray-800">
                    <li class="border border-gray-200 rounded-lg p-3 flex items-center justify-between"><span>127.0.0.1</span><button class="text-xs underline" data-remove>Gỡ</button></li>
                </ul>
            </div>

            <!-- JWT tokens management (UI) -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Token-based authentication (JWT)</h3>
                    <button id="new-token" class="inline-flex items-center px-3 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">Tạo token</button>
                </div>
                <div class="overflow-hidden border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tên</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Scope</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tạo lúc</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="token-rows" class="bg-white divide-y divide-gray-100">
                            <tr>
                                <td class="px-3 py-2 text-gray-900">API Crawler</td>
                                <td class="px-3 py-2 text-gray-700">read:comments</td>
                                <td class="px-3 py-2 text-gray-700">31/10/2025 14:00</td>
                                <td class="px-3 py-2 text-right"><button class="text-xs underline text-gray-700 hover:text-black" data-revoke>Revoke</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Audit log -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Ghi log & hoạt động</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        <input type="text" id="log-user" placeholder="User/email" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <input type="text" id="log-ip" placeholder="IP" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <input type="date" id="log-from" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <input type="date" id="log-to" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    </div>
                </div>
                <div class="overflow-hidden border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Thời gian</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">User</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">IP</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Hành động</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Mô tả</th>
                            </tr>
                        </thead>
                        <tbody id="log-rows" class="bg-white divide-y divide-gray-100">
                            <tr>
                                <td class="px-3 py-2 text-gray-700">31/10/2025 14:20</td>
                                <td class="px-3 py-2 text-gray-900">admin@example.com</td>
                                <td class="px-3 py-2 text-gray-700">127.0.0.1</td>
                                <td class="px-3 py-2 text-gray-700">LOGIN</td>
                                <td class="px-3 py-2 text-gray-700">Đăng nhập thành công</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-700">31/10/2025 14:25</td>
                                <td class="px-3 py-2 text-gray-900">teacher@example.com</td>
                                <td class="px-3 py-2 text-gray-700">127.0.0.1</td>
                                <td class="px-3 py-2 text-gray-700">TOKEN_CREATE</td>
                                <td class="px-3 py-2 text-gray-700">Tạo token API</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('save-settings')?.addEventListener('click', ()=> alert('Đã lưu thiết lập (UI demo)'));
        document.getElementById('add-ip')?.addEventListener('click', ()=>{
            const ip = (document.getElementById('ip-input').value||'').trim();
            if(!ip) return; const li=document.createElement('li'); li.className='border border-gray-200 rounded-lg p-3 flex items-center justify-between'; li.innerHTML=`<span>${ip}</span><button class='text-xs underline' data-remove>Gỡ</button>`; document.getElementById('ip-list').appendChild(li); document.getElementById('ip-input').value='';
        });
        document.getElementById('ip-list')?.addEventListener('click', (e)=>{ if(e.target.matches('[data-remove]')) e.target.closest('li').remove(); });
        document.getElementById('new-token')?.addEventListener('click', ()=> alert('Tạo token (UI demo)'));
        document.getElementById('token-rows')?.addEventListener('click', (e)=>{ if(e.target.matches('[data-revoke]')) alert('Đã revoke (UI demo)') });
    </script>
</x-app-layout>


