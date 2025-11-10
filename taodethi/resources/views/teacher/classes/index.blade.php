<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Quản lý lớp & học viên') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Import students -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Import học sinh</h3>
                    <a href="#" class="text-xs underline text-gray-700 hover:text-black">Tải mẫu CSV</a>
                </div>
                <div class="flex flex-col md:flex-row md:items-center gap-3">
                    <input id="student-file" type="file" class="border border-gray-300 rounded-md px-3 py-2 text-sm" accept=".csv,.xlsx">
                    <button id="import-students" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">Import</button>
                </div>
                <p class="text-xs text-gray-600 mt-2">Cột tối thiểu: name, email. Tuỳ chọn: class, group.</p>
            </div>

            <!-- Class list and grouping -->
            <div class="bg-white border border-gray-200 sm:rounded-xl overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Họ tên</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Lớp</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Nhóm</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="students" class="bg-white divide-y divide-gray-100">
                        <tr>
                            <td class="px-3 py-2 text-gray-900">Nguyễn A</td>
                            <td class="px-3 py-2 text-gray-700">a@student.edu</td>
                            <td class="px-3 py-2 text-gray-700">10A1</td>
                            <td class="px-3 py-2 text-gray-700">Nhóm 1</td>
                            <td class="px-3 py-2 text-right">
                                <button class="text-xs underline text-gray-700 hover:text-black" data-assign>Đổi nhóm</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 text-gray-900">Trần B</td>
                            <td class="px-3 py-2 text-gray-700">b@student.edu</td>
                            <td class="px-3 py-2 text-gray-700">10A1</td>
                            <td class="px-3 py-2 text-gray-700">Nhóm 2</td>
                            <td class="px-3 py-2 text-right">
                                <button class="text-xs underline text-gray-700 hover:text-black" data-assign>Đổi nhóm</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Assistant teachers -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Phân quyền giáo viên trợ giúp</h3>
                    <button id="add-assistant" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Thêm</button>
                </div>
                <div class="overflow-hidden border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Họ tên</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="assistants" class="bg-white divide-y divide-gray-100">
                            <tr>
                                <td class="px-3 py-2 text-gray-900">Cô C</td>
                                <td class="px-3 py-2 text-gray-700">c@teacher.edu</td>
                                <td class="px-3 py-2 text-right"><button class="text-xs underline text-gray-700 hover:text-black" data-remove>Gỡ</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('import-students')?.addEventListener('click', () => {
            const f = document.getElementById('student-file');
            if (!f.files.length) { alert('Chọn file trước.'); return; }
            alert('Import học sinh (UI demo): ' + f.files[0].name);
            f.value='';
        });
        document.querySelectorAll('[data-assign]').forEach(b => b.addEventListener('click', () => alert('Đổi nhóm (UI demo)')));
        document.getElementById('add-assistant')?.addEventListener('click', () => alert('Thêm giáo viên trợ giúp (UI demo)'));
        document.querySelectorAll('[data-remove]').forEach(b => b.addEventListener('click', () => confirm('Gỡ quyền trợ giúp?')));
    </script>
</x-app-layout>


