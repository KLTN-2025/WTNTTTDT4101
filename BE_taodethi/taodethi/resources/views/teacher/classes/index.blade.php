<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Quản lý lớp & học viên') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Danh sách lớp học</h3>
                <button 
                    id="open-create"
                    hx-get="{{ route('teacher.classes.index') }}?modal=create"
                    hx-target="#modal-container"
                    hx-swap="innerHTML"
                    onclick="document.getElementById('modal').classList.remove('hidden'); document.getElementById('modal').classList.add('flex');"
                    class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">
                    Tạo lớp mới
                </button>
            </div>

            <div id="class-list" class="bg-white border border-gray-200 sm:rounded-xl overflow-hidden">
                @include('teacher.classes.partials.class-list', ['classes' => $classes])
            </div>

            <!-- Create/Edit modal -->
            <div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4 z-50">
                <div class="bg-white w-full max-w-xl border border-gray-200 sm:rounded-xl p-0 overflow-hidden shadow-xl">
                    <div class="px-5 py-3 border-b border-gray-200 flex items-center justify-between">
                        <h3 id="modal-title" class="text-base font-semibold text-gray-900">Tạo lớp mới</h3>
                        <button 
                            id="close-modal" 
                            onclick="document.getElementById('modal').classList.add('hidden'); document.getElementById('modal').classList.remove('flex');"
                            class="text-sm text-gray-600 hover:text-black">
                            Đóng
                        </button>
                    </div>
                    <div id="modal-container">
                        @include('teacher.classes.partials.create-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
