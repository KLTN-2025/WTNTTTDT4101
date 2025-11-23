<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Quản trị người dùng & phân quyền') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Toast Notification -->
            <div id="toast-success" class="fixed top-4 right-4 z-50 hidden">
                <div class="bg-green-50 border-l-4 border-green-500 rounded-r-md shadow-lg p-4 min-w-[300px]">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-medium text-green-800" id="toast-message"></p>
                        <button onclick="document.getElementById('toast-success').classList.add('hidden')" class="ml-4 text-green-500 hover:text-green-700">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Toolbar -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-4">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                    <div class="flex items-center gap-3">
                        <button 
                            id="open-create" 
                            hx-get="{{ route('admin.users.create') }}"
                            hx-target="#modal-container"
                            hx-swap="innerHTML"
                            onclick="document.getElementById('modal').classList.remove('hidden'); document.getElementById('modal').classList.add('flex');"
                            class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">
                            Tạo người dùng
                        </button>
                        <button 
                            id="bulk-disable" 
                            hx-post="{{ route('admin.users.bulk-disable') }}"
                            hx-target="#rows"
                            hx-swap="innerHTML"
                            hx-headers='{"HX-Request": "true"}'
                            hx-include="[name='user_ids[]']"
                            hx-confirm="Bạn có chắc muốn vô hiệu hóa các người dùng đã chọn?"
                            class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">
                            Vô hiệu hoá
                        </button>
                    </div>
                    <div>
                        <input 
                            id="search" 
                            name="search"
                            type="text" 
                            placeholder="Tìm tên/email..." 
                            value="{{ request('search') }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
                            hx-get="{{ route('admin.users') }}"
                            hx-target="#rows"
                            hx-swap="innerHTML"
                            hx-trigger="keyup changed delay:500ms, search"
                            hx-include="[name='role'], [name='status'], [name='sort']"
                            hx-headers='{"HX-Request": "true"}'>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <select 
                            id="role" 
                            name="role"
                            class="border border-gray-300 rounded-md px-3 py-2 text-sm"
                            hx-get="{{ route('admin.users') }}"
                            hx-target="#rows"
                            hx-swap="innerHTML"
                            hx-trigger="change"
                            hx-include="[name='search'], [name='status'], [name='sort']"
                            hx-headers='{"HX-Request": "true"}'>
                            <option value="">Tất cả vai trò</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="guest" {{ request('role') === 'guest' ? 'selected' : '' }}>Guest</option>
                        </select>
                        <select 
                            id="status" 
                            name="status"
                            class="border border-gray-300 rounded-md px-3 py-2 text-sm"
                            hx-get="{{ route('admin.users') }}"
                            hx-target="#rows"
                            hx-swap="innerHTML"
                            hx-trigger="change"
                            hx-include="[name='search'], [name='role'], [name='sort']"
                            hx-headers='{"HX-Request": "true"}'>
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="disabled" {{ request('status') === 'disabled' ? 'selected' : '' }}>Disabled</option>
                        </select>
                        <select 
                            id="sort" 
                            name="sort"
                            class="border border-gray-300 rounded-md px-3 py-2 text-sm"
                            hx-get="{{ route('admin.users') }}"
                            hx-target="#rows"
                            hx-swap="innerHTML"
                            hx-trigger="change"
                            hx-include="[name='search'], [name='role'], [name='status']"
                            hx-headers='{"HX-Request": "true"}'>
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Tên</option>
                            <option value="role" {{ request('sort') === 'role' ? 'selected' : '' }}>Vai trò</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Users table -->
            <div class="bg-white border border-gray-200 sm:rounded-xl overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2">
                                <input type="checkbox" id="pick-all">
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tên</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Vai trò</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="rows" class="bg-white divide-y divide-gray-100">
                        @include('admin.users.partials.table-rows', ['users' => $users])
                    </tbody>
                </table>
            </div>

            <!-- Create/Edit modal -->
            <div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4 z-50">
                <div class="bg-white w-full max-w-xl border border-gray-200 sm:rounded-xl p-0 overflow-hidden shadow-xl">
                    <div class="px-5 py-3 border-b border-gray-200 flex items-center justify-between">
                        <h3 id="modal-title" class="text-base font-semibold text-gray-900">Tạo người dùng</h3>
                        <button 
                            id="close-modal" 
                            onclick="document.getElementById('modal').classList.add('hidden'); document.getElementById('modal').classList.remove('flex');"
                            class="text-sm text-gray-600 hover:text-black">
                            Đóng
                        </button>
                    </div>
                    <div id="modal-container">
                        @include('admin.users.partials.user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('pick-all')?.addEventListener('change', function(e) {
            document.querySelectorAll('.pick').forEach(c => c.checked = e.target.checked);
        });
    </script>
</x-app-layout>
