<div class="overflow-hidden border border-gray-200 rounded-lg">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Họ tên</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Mã học sinh</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Vai trò</th>
                <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100" id="student-list">
            @forelse($class->users as $user)
                <tr>
                    <td class="px-3 py-2 text-gray-900">{{ $user->name }}</td>
                    <td class="px-3 py-2 text-gray-700">{{ $user->email }}</td>
                    <td class="px-3 py-2 text-gray-700">{{ $user->pivot->student_code ?? '—' }}</td>
                    <td class="px-3 py-2 text-gray-700">
                        <span class="px-2 py-0.5 text-xs border border-black rounded">
                            {{ $user->pivot->role === 'assistant_teacher' ? 'Trợ giảng' : 'Học sinh' }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-right">
                        @if($user->pivot->role === 'student')
                            <button 
                                hx-put="{{ route('teacher.classes.update-student-role', [$class, $user]) }}"
                                hx-vals='{"role": "assistant_teacher"}'
                                hx-confirm="Chuyển học sinh này thành trợ giảng?"
                                hx-headers='{"HX-Request": "true"}'
                                class="text-xs underline text-gray-700 hover:text-black mr-3">
                                Chuyển trợ giảng
                            </button>
                        @endif
                        <button 
                            hx-delete="{{ route('teacher.classes.remove-student', [$class, $user]) }}"
                            hx-confirm="Xóa học sinh này khỏi lớp?"
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
                    <td colspan="5" class="px-3 py-4 text-center text-gray-500">Chưa có học sinh nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

