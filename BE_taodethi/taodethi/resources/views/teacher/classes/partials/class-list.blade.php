<table class="min-w-full divide-y divide-gray-200 text-sm">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tên lớp</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Mã lớp</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Môn</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Số học sinh</th>
            <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Thao tác</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-100">
        @forelse($classes as $class)
            <tr>
                <td class="px-3 py-2 text-gray-900">{{ $class->name }}</td>
                <td class="px-3 py-2 text-gray-700">{{ $class->code }}</td>
                <td class="px-3 py-2 text-gray-700">{{ $class->subject ?? '—' }}</td>
                <td class="px-3 py-2 text-gray-700">{{ $class->students->count() }}</td>
                <td class="px-3 py-2 text-right">
                    <a href="{{ route('teacher.classes.show', $class) }}" 
                       class="text-xs underline text-gray-700 hover:text-black mr-3">
                        Xem chi tiết
                    </a>
                    <button 
                        hx-delete="{{ route('teacher.classes.destroy', $class) }}"
                        hx-confirm="Bạn có chắc muốn xóa lớp này?"
                        hx-target="closest tr"
                        hx-swap="outerHTML"
                        hx-headers='{"HX-Request": "true"}'
                        class="text-xs underline text-red-600 hover:text-red-800">
                        Xóa
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-3 py-4 text-center text-gray-500">Chưa có lớp học nào</td>
            </tr>
        @endforelse
    </tbody>
</table>

@if($classes->hasPages())
    <div class="px-3 py-2 border-t border-gray-200">
        {{ $classes->links() }}
    </div>
@endif

