<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Chấm thi') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white border border-gray-200 sm:rounded-xl p-4">
                <form hx-get="{{ route('teacher.grading.index') }}" 
                      hx-target="#exam-list" 
                      hx-swap="innerHTML"
                      class="flex flex-col md:flex-row md:items-center gap-3">
                    <div class="flex-1">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Tìm đề thi..." 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div class="flex items-center gap-2">
                        <select name="status" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="">Tất cả trạng thái</option>
                            <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Sắp tới</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Đang diễn ra</option>
                            <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Đã nộp</option>
                            <option value="graded" {{ request('status') === 'graded' ? 'selected' : '' }}>Đã chấm</option>
                        </select>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">
                            Tìm kiếm
                        </button>
                    </div>
                </form>
            </div>

            <div id="exam-list" class="bg-white border border-gray-200 sm:rounded-xl overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Đề thi</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Môn</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Số bài nộp</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($exams as $exam)
                            <tr>
                                <td class="px-3 py-2 text-gray-900">{{ $exam->title }}</td>
                                <td class="px-3 py-2 text-gray-700">{{ $exam->subject }}</td>
                                <td class="px-3 py-2">
                                    <span class="px-2 py-0.5 text-xs border border-black rounded">
                                        {{ $exam->status === 'submitted' ? 'Đã nộp' : ($exam->status === 'graded' ? 'Đã chấm' : 'Chưa nộp') }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-gray-700">
                                    {{ $exam->users()->wherePivot('status', 'submitted')->count() }} / {{ $exam->users->count() }}
                                </td>
                                <td class="px-3 py-2 text-right">
                                    <a href="{{ route('teacher.grading.show', $exam) }}" 
                                       class="text-xs underline text-gray-700 hover:text-black">
                                        Xem chi tiết
                                    </a>
                                </td>
                        </tr>
                        @empty
                        <tr>
                                <td colspan="5" class="px-3 py-4 text-center text-gray-500">Chưa có đề thi nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="px-3 py-2 border-t border-gray-200">
                    {{ $exams->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
