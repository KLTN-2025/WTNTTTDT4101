<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Lập lịch thi / mở-đóng đề') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Danh sách lịch thi</h3>
                <button 
                    hx-get="{{ route('teacher.schedules.index') }}?modal=create"
                    hx-target="#modal-container"
                    hx-swap="innerHTML"
                    class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">
                    Tạo lịch mới
                </button>
            </div>

            <div id="schedule-list" class="bg-white border border-gray-200 sm:rounded-xl overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tên lịch</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Thời gian</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Thời lượng</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($schedules as $schedule)
                        <tr>
                                <td class="px-3 py-2 text-gray-900">{{ $schedule->name }}</td>
                                <td class="px-3 py-2 text-gray-700">
                                    {{ $schedule->start_time->format('d/m/Y H:i') }} → {{ $schedule->end_time->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-3 py-2 text-gray-700">{{ $schedule->duration_minutes }} phút</td>
                                <td class="px-3 py-2">
                                    <span class="px-2 py-0.5 text-xs border border-black rounded {{ $schedule->is_active ? 'bg-green-50' : '' }}">
                                        {{ $schedule->is_active ? 'Đang mở' : 'Đóng' }}
                                    </span>
                                </td>
                            <td class="px-3 py-2 text-right">
                                    <button 
                                        hx-get="{{ route('teacher.schedules.index') }}?modal=edit&id={{ $schedule->id }}"
                                        hx-target="#modal-container"
                                        hx-swap="innerHTML"
                                        class="text-xs underline text-gray-700 hover:text-black mr-3">
                                        Chỉnh sửa
                                    </button>
                                    <button 
                                        hx-post="{{ route('teacher.schedules.toggle-active', $schedule) }}"
                                        hx-target="closest tr"
                                        hx-swap="outerHTML"
                                        class="text-xs underline text-gray-700 hover:text-black">
                                        {{ $schedule->is_active ? 'Đóng đề' : 'Mở đề' }}
                                    </button>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-4 text-center text-gray-500">Chưa có lịch thi nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="px-3 py-2 border-t border-gray-200">
                    {{ $schedules->links() }}
                </div>
            </div>

            <div id="modal-container"></div>
        </div>
    </div>
</x-app-layout>
