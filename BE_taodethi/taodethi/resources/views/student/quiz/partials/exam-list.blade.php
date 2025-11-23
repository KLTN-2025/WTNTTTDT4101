<div class="bg-white border border-gray-200 sm:rounded-xl p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiêu đề</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Môn học</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hạn nộp</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($exams as $exam)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $exam->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $exam->subject }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $exam->due_date ? $exam->due_date->format('d/m/Y H:i') : 'Không có' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $userExam = $exam->users->where('id', auth()->id())->first();
                                $status = $userExam->pivot->status ?? 'not_started';
                            @endphp
                            @if($status === 'submitted')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Đã nộp</span>
                            @elseif($status === 'in_progress')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Đang làm</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Chưa làm</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('student.quiz.show', $exam) }}" class="text-black hover:text-gray-700">Xem chi tiết</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Không có bài thi nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

