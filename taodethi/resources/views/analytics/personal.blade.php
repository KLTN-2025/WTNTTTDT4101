<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Báo cáo cá nhân') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Điểm theo kỹ năng -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Điểm theo kỹ năng</h3>
                    <div class="text-sm text-gray-700">Cập nhật: <span class="text-gray-900">gần đây</span></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <canvas id="skillsRadar" height="200"></canvas>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <canvas id="skillsBar" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ tiến bộ theo thời gian -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tiến bộ theo thời gian</h3>
                <div class="border border-gray-200 rounded-lg p-4">
                    <canvas id="progressLine" height="120"></canvas>
                </div>
            </div>

            <!-- Đề xuất ôn tập -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Đề xuất ôn tập</h3>
                    <button id="refreshSuggest" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Làm mới</button>
                </div>
                <ul id="suggestList" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <li class="border border-gray-200 rounded-lg p-4 text-sm text-gray-800">Ôn từ vựng Unit 6 (20 từ) • 15 phút</li>
                    <li class="border border-gray-200 rounded-lg p-4 text-sm text-gray-800">Luyện 5 bài toán hình khối • 25 phút</li>
                    <li class="border border-gray-200 rounded-lg p-4 text-sm text-gray-800">Đọc 1 bài mẫu Văn nghị luận • 10 phút</li>
                    <li class="border border-gray-200 rounded-lg p-4 text-sm text-gray-800">Luyện nghe 10 phút/ngày • 7 ngày</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const monoTicks = { color: '#111', font: { size: 11 } };
        const monoGrid = { color: '#e5e7eb' };
        const monoBorder = '#111';
        // Radar skills
        new Chart(document.getElementById('skillsRadar'), {
            type: 'radar',
            data: {
                labels: ['Reading','Writing','Listening','Speaking','Algebra','Geometry'],
                datasets: [{
                    label: 'Điểm',
                    data: [78, 65, 84, 72, 88, 60],
                    borderColor: monoBorder,
                    backgroundColor: 'rgba(0,0,0,0.05)',
                    pointBackgroundColor: monoBorder,
                }]
            },
            options: { scales: { r: { angleLines: { color: '#e5e7eb' }, grid: { color: '#e5e7eb' }, pointLabels: { color: '#111' }, ticks: { display:false } } } }
        });
        // Bar skills
        new Chart(document.getElementById('skillsBar'), {
            type: 'bar',
            data: {
                labels: ['Reading','Writing','Listening','Speaking','Algebra','Geometry'],
                datasets: [{
                    label: 'Điểm',
                    data: [78, 65, 84, 72, 88, 60],
                    borderColor: monoBorder,
                    backgroundColor: 'rgba(0,0,0,0.1)'
                }]
            },
            options: { scales: { x: { ticks: monoTicks, grid: monoGrid }, y: { beginAtZero: true, ticks: monoTicks, grid: monoGrid } }, plugins: { legend: { labels: { color: '#111' } } } }
        });
        // Progress line
        new Chart(document.getElementById('progressLine'), {
            type: 'line',
            data: {
                labels: ['T1','T2','T3','T4','T5','T6'],
                datasets: [{
                    label: 'Điểm trung bình',
                    data: [65, 67, 70, 74, 78, 82],
                    borderColor: monoBorder,
                    backgroundColor: 'rgba(0,0,0,0.05)',
                    tension: 0.2
                }]
            },
            options: { scales: { x: { ticks: monoTicks, grid: monoGrid }, y: { beginAtZero: true, ticks: monoTicks, grid: monoGrid } }, plugins: { legend: { labels: { color: '#111' } } } }
        });
        document.getElementById('refreshSuggest')?.addEventListener('click', () => alert('Làm mới đề xuất (UI demo)'));
    </script>
</x-app-layout>


