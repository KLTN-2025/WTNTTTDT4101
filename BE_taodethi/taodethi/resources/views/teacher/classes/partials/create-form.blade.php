<form 
    id="class-form"
    hx-post="{{ route('teacher.classes.store') }}"
    hx-target="#modal-container"
    hx-swap="innerHTML"
    hx-headers='{"HX-Request": "true"}'
    @csrf

    <div class="p-5 space-y-4">
        <div>
            <label class="block text-xs text-gray-600 mb-1">Tên lớp</label>
            <input 
                name="name" 
                type="text" 
                value="{{ old('name') }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                required>
            @error('name')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Mã lớp</label>
            <input 
                name="code" 
                type="text" 
                value="{{ old('code') }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                placeholder="Để trống để tự động tạo">
            @error('code')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Môn học</label>
            <input 
                name="subject" 
                type="text" 
                value="{{ old('subject') }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                placeholder="Ví dụ: Toán, Lý, Hóa...">
            @error('subject')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Năm học</label>
            <input 
                name="academic_year" 
                type="text" 
                value="{{ old('academic_year') }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                placeholder="Ví dụ: 2024-2025">
            @error('academic_year')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Mô tả</label>
            <textarea 
                name="description" 
                rows="3"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                placeholder="Mô tả về lớp học...">{{ old('description') }}</textarea>
            @error('description')
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
            Tạo lớp
        </button>
    </div>
</form>

