<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Lớp: ') . $class->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $class->name }}</h3>
                        <p class="text-sm text-gray-600">Mã: {{ $class->code }} | Môn: {{ $class->subject ?? '—' }}</p>
                    </div>
                    <a href="{{ route('teacher.classes.index') }}" class="text-sm underline text-gray-700 hover:text-black">
                        Quay lại
                    </a>
                </div>

                <div class="mt-6 space-y-6">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-base font-semibold text-gray-900">Import học sinh</h4>
                            <a href="#" class="text-xs underline text-gray-700 hover:text-black">Tải mẫu CSV</a>
                        </div>
                        <form 
                            hx-post="{{ route('teacher.classes.import-students', $class) }}"
                            hx-encoding="multipart/form-data"
                            hx-target="#import-result"
                            class="flex flex-col md:flex-row md:items-center gap-3">
                            @csrf
                            <input type="file" 
                                   name="file" 
                                   accept=".csv,.txt"
                                   required
                                   class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">
                                Import
                            </button>
                        </form>
                        <p class="text-xs text-gray-600 mt-2">Định dạng CSV: email,student_code</p>
                        <div id="import-result"></div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-base font-semibold text-gray-900">Danh sách học sinh</h4>
                            <button 
                                id="open-add-student"
                                hx-get="{{ route('teacher.classes.show', $class) }}?modal=add-student"
                                hx-target="#modal-container"
                                hx-swap="innerHTML"
                                onclick="document.getElementById('modal').classList.remove('hidden'); document.getElementById('modal').classList.add('flex');"
                                class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">
                                Thêm học sinh
                            </button>
                        </div>
                        <div id="student-list">
                            @include('teacher.classes.partials.student-list', ['class' => $class])
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Student Modal -->
            <div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4 z-50">
                <div class="bg-white w-full max-w-xl border border-gray-200 sm:rounded-xl p-0 overflow-hidden shadow-xl">
                    <div class="px-5 py-3 border-b border-gray-200 flex items-center justify-between">
                        <h3 id="modal-title" class="text-base font-semibold text-gray-900">Thêm học sinh vào lớp</h3>
                        <button 
                            id="close-modal" 
                            onclick="document.getElementById('modal').classList.add('hidden'); document.getElementById('modal').classList.remove('flex');"
                            class="text-sm text-gray-600 hover:text-black">
                            Đóng
                        </button>
                    </div>
                    <div id="modal-container">
                        @include('teacher.classes.partials.add-student-form', ['class' => $class, 'students' => $students])
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

