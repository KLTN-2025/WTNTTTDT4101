<form 
    id="add-student-form"
    hx-post="{{ route('teacher.classes.add-student', $class) }}"
    hx-target="#modal-container"
    hx-swap="innerHTML"
    hx-headers='{"HX-Request": "true"}'
    @csrf

    <div class="p-5 space-y-4">
        <div>
            <label class="block text-xs text-gray-600 mb-1">Chọn học sinh</label>
            <select 
                name="user_id" 
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                required>
                <option value="">-- Chọn học sinh --</option>
                @foreach($students as $student)
                    @if(!$class->users->contains($student->id))
                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                    @endif
                @endforeach
            </select>
            @error('user_id')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Mã học sinh</label>
            <input 
                name="student_code" 
                type="text" 
                value="{{ old('student_code') }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                placeholder="Mã học sinh (tùy chọn)">
            @error('student_code')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Vai trò</label>
            <select 
                name="role" 
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                <option value="student" {{ old('role', 'student') === 'student' ? 'selected' : '' }}>Học sinh</option>
                <option value="assistant_teacher" {{ old('role') === 'assistant_teacher' ? 'selected' : '' }}>Trợ giảng</option>
            </select>
            @error('role')
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
            Thêm
        </button>
    </div>
</form>

