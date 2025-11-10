<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Bảng điều khiển học sinh') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Trạng thái bài kiểm tra -->
                <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Trạng thái bài kiểm tra</h3>
                        <a href="#" class="text-xs underline text-gray-600 hover:text-black">Xem tất cả</a>
                    </div>
                    <ul class="divide-y divide-gray-100">
                        <li class="py-3 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Toán - Chương 1</p>
                                <p class="text-xs text-gray-600">Hạn: 02/11/2025</p>
                            </div>
                            <span class="text-xs px-2 py-1 border border-black rounded-md text-black">Sắp diễn ra</span>
                        </li>
                        <li class="py-3 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Văn - Bài luận số 1</p>
                                <p class="text-xs text-gray-600">Nộp: 30/10/2025</p>
                            </div>
                            <span class="text-xs px-2 py-1 border border-gray-400 text-gray-700 rounded-md">Đã nộp</span>
                        </li>
                        <li class="py-3 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Anh - Từ vựng tuần 5</p>
                                <p class="text-xs text-gray-600">Hạn: 31/10/2025</p>
                            </div>
                            <span class="text-xs px-2 py-1 border border-gray-400 text-gray-700 rounded-md">Đang làm</span>
                        </li>
                    </ul>
                </div>

                <!-- Tiến trình học -->
                <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Tiến trình học</h3>
                        <a href="#" class="text-xs underline text-gray-600 hover:text-black">Chi tiết</a>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-sm text-gray-800">Toán</p>
                                <span class="text-xs text-gray-600">72%</span>
                            </div>
                            <div class="h-2 bg-gray-100 rounded">
                                <div class="h-2 bg-black rounded" style="width:72%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-sm text-gray-800">Văn</p>
                                <span class="text-xs text-gray-600">40%</span>
                            </div>
                            <div class="h-2 bg-gray-100 rounded">
                                <div class="h-2 bg-black rounded" style="width:40%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-sm text-gray-800">Tiếng Anh</p>
                                <span class="text-xs text-gray-600">86%</span>
                            </div>
                            <div class="h-2 bg-gray-100 rounded">
                                <div class="h-2 bg-black rounded" style="width:86%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Điểm gần nhất -->
                <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Điểm gần nhất</h3>
                        <a href="#" class="text-xs underline text-gray-600 hover:text-black">Bảng điểm</a>
                    </div>
                    <div class="overflow-hidden border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Môn</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Bài</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Điểm</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">Toán</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">Trắc nghiệm hàm số</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 text-right font-semibold">9.0</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">Văn</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">Nghị luận xã hội</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 text-right font-semibold">7.5</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">Tiếng Anh</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">Listening Unit 5</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 text-right font-semibold">8.8</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Gợi ý học -->
            <div class="mt-6 bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Gợi ý học</h3>
                    <a href="#" class="text-xs underline text-gray-600 hover:text-black">Làm mới</a>
                </div>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <li class="border border-gray-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-900">Ôn tập 20 từ mới Tiếng Anh (Unit 5)</p>
                        <p class="text-xs text-gray-600 mt-1">Gợi ý dựa trên tiến trình và điểm gần đây</p>
                    </li>
                    <li class="border border-gray-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-900">Làm lại 5 bài Toán sai ở Chương 1</p>
                        <p class="text-xs text-gray-600 mt-1">Tập trung vào dạng hàm số và giới hạn</p>
                    </li>
                    <li class="border border-gray-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-900">Đọc thêm 1 bài mẫu Văn nghị luận</p>
                        <p class="text-xs text-gray-600 mt-1">Cải thiện bố cục và dẫn chứng</p>
                    </li>
                    <li class="border border-gray-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-900">Luyện nghe 15 phút/ngày</p>
                        <p class="text-xs text-gray-600 mt-1">Tập trung phát âm và tốc độ</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
