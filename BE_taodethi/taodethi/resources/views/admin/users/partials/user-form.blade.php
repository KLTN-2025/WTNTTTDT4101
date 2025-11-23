<form 
    id="user-form"
    @if(isset($user))
        hx-put="{{ route('admin.users.update', $user) }}"
    @else
        hx-post="{{ route('admin.users.store') }}"
    @endif
    hx-target="#modal-container"
    hx-swap="innerHTML"
    hx-headers='{"HX-Request": "true"}'
    @csrf
    @if(isset($user))
        @method('PUT')
    @endif

    <div class="p-5 space-y-4">
        <div>
            <label class="block text-xs text-gray-600 mb-1">Tên</label>
            <input 
                name="name" 
                type="text" 
                value="{{ old('name', $user->name ?? '') }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                required>
            @error('name')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Email</label>
            <input 
                name="email" 
                type="email" 
                value="{{ old('email', $user->email ?? '') }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                required>
            @error('email')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="block text-xs text-gray-600 mb-1">Vai trò</label>
                <select name="role" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
                    <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="teacher" {{ old('role', $user->role ?? '') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="student" {{ old('role', $user->role ?? '') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="guest" {{ old('role', $user->role ?? '') === 'guest' ? 'selected' : '' }}>Guest</option>
                </select>
                @error('role')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Trạng thái</label>
                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
                    <option value="active" {{ old('status', $user->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="disabled" {{ old('status', $user->status ?? '') === 'disabled' ? 'selected' : '' }}>Disabled</option>
                </select>
                @error('status')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Mật khẩu (tạm)</label>
            <input 
                name="password" 
                type="text" 
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" 
                placeholder="Tuỳ chọn - sẽ gửi link đặt lại">
            @error('password')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="px-5 py-3 border-t border-gray-200 flex items-center justify-end gap-3">
        <button 
            type="button"
            onclick="document.getElementById('modal').classList.add('hidden'); document.getElementById('modal').classList.remove('flex');"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:bg-gray-50">
            Hủy
        </button>
        <button 
            type="submit"
            class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">
            Lưu
        </button>
    </div>
</form>

