@if(session('success'))
    <tr>
        <td colspan="6" class="px-3 py-3">
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-md">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </td>
    </tr>
@endif

@forelse($users as $user)
    <tr>
        <td class="px-3 py-2">
            <input type="checkbox" class="pick" name="user_ids[]" value="{{ $user->id }}">
        </td>
        <td class="px-3 py-2 text-gray-900">{{ $user->name }}</td>
        <td class="px-3 py-2 text-gray-700">{{ $user->email }}</td>
        <td class="px-3 py-2 text-gray-700">
            <span class="px-2 py-0.5 text-xs border border-black rounded">
                @if($user->role === 'admin')
                    Admin
                @elseif($user->role === 'teacher')
                    Teacher
                @elseif($user->role === 'student')
                    Student
                @else
                    Guest
                @endif
            </span>
        </td>
        <td class="px-3 py-2 text-gray-700">
            <span class="px-2 py-0.5 text-xs border border-black rounded {{ $user->status === 'active' ? 'text-green-700 border-green-700' : 'text-red-700 border-red-700' }}">
                {{ $user->status === 'active' ? 'Active' : 'Disabled' }}
            </span>
        </td>
        <td class="px-3 py-2 text-right">
            <button 
                class="text-xs underline text-gray-700 hover:text-black mr-3" 
                hx-get="{{ route('admin.users.edit', $user) }}"
                hx-target="#modal-container"
                hx-swap="innerHTML"
                onclick="document.getElementById('modal').classList.remove('hidden'); document.getElementById('modal').classList.add('flex');">
                Chỉnh sửa
            </button>
            <button 
                class="text-xs underline text-gray-700 hover:text-black" 
                hx-post="{{ route('admin.users.toggle-status', $user) }}"
                hx-target="#rows"
                hx-swap="innerHTML"
                hx-headers='{"HX-Request": "true"}'
                hx-include="[name='search'], [name='role'], [name='status'], [name='sort']">
                {{ $user->status === 'active' ? 'Vô hiệu' : 'Kích hoạt' }}
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-3 py-4 text-center text-gray-500">Không có người dùng nào</td>
    </tr>
@endforelse

@if($users->hasPages())
    <tr>
        <td colspan="6" class="px-3 py-2">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Trang {{ $users->currentPage() }}/{{ $users->lastPage() }}
                </div>
                <div class="flex gap-2">
                    @if($users->onFirstPage())
                        <span class="px-3 py-1 text-sm text-gray-400 cursor-not-allowed">Trước</span>
                    @else
                        <button 
                            hx-get="{{ $users->previousPageUrl() }}"
                            hx-target="#rows"
                            hx-swap="innerHTML"
                            hx-headers='{"HX-Request": "true"}'
                            class="px-3 py-1 text-sm text-gray-700 hover:text-black underline">
                            Trước
                        </button>
                    @endif
                    @if($users->hasMorePages())
                        <button 
                            hx-get="{{ $users->nextPageUrl() }}"
                            hx-target="#rows"
                            hx-swap="innerHTML"
                            hx-headers='{"HX-Request": "true"}'
                            class="px-3 py-1 text-sm text-gray-700 hover:text-black underline">
                            Sau
                        </button>
                    @else
                        <span class="px-3 py-1 text-sm text-gray-400 cursor-not-allowed">Sau</span>
                    @endif
                </div>
            </div>
        </td>
    </tr>
@endif

