<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Báo cáo lớp / trường') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Phân bố điểm -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Phân bố điểm</h3>
                    <div class="text-sm text-gray-700">Lớp: <span class="text-gray-900">10A1</span></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <canvas id="histogram" height="180"></canvas>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <canvas id="boxplot" height="180"></canvas>
                    </div>
                </div>
            </div>

            <!-- Item analysis: câu khó/chưa hiểu, difficulty, discrimination -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Item analysis</h3>
                <div class="overflow-hidden border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Câu</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tỉ lệ đúng</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Khó (p)</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Phân biệt (r)</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Gợi ý xử lý</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <tr>
                                <td class="px-3 py-2 text-gray-900">Q3</td>
                                <td class="px-3 py-2 text-gray-700">32%</td>
                                <td class="px-3 py-2 text-gray-700">0.32</td>
                                <td class="px-3 py-2 text-gray-700">0.12</td>
                                <td class="px-3 py-2 text-gray-700">Xem lại câu hỏi/đáp án; tăng gợi ý</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-900">Q7</td>
                                <td class="px-3 py-2 text-gray-700">45%</td>
                                <td class="px-3 py-2 text-gray-700">0.45</td>
                                <td class="px-3 py-2 text-gray-700">0.05</td>
                                <td class="px-3 py-2 text-gray-700">Giảm nhiễu; điều chỉnh độ khó</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-900">Q10</td>
                                <td class="px-3 py-2 text-gray-700">88%</td>
                                <td class="px-3 py-2 text-gray-700">0.88</td>
                                <td class="px-3 py-2 text-gray-700">0.40</td>
                                <td class="px-3 py-2 text-gray-700">Câu dễ, phân biệt tốt</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top câu khó / chưa hiểu -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Câu khó/chưa hiểu nhiều</h3>
                    <button id="exportIssues" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Export danh sách</button>
                </div>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-800">
                    <li class="border border-gray-200 rounded-lg p-4">Q3 • p=0.32 • r=0.12 • Đề xuất: chỉnh đáp án nhiễu</li>
                    <li class="border border-gray-200 rounded-lg p-4">Q7 • p=0.45 • r=0.05 • Đề xuất: thêm ví dụ</li>
                    <li class="border border-gray-200 rounded-lg p-4">Q12 • p=0.40 • r=0.08 • Đề xuất: chia nhỏ ý</li>
                    <li class="border border-gray-200 rounded-lg p-4">Q15 • p=0.50 • r=0.10 • Đề xuất: làm rõ đề</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const monoTicks = { color: '#111', font: { size: 11 } };
        const monoGrid = { color: '#e5e7eb' };
        const monoBorder = '#111';
        // Histogram (approx via bar chart buckets)
        new Chart(document.getElementById('histogram'), {
            type: 'bar',
            data: {
                labels: ['0-2','2-4','4-6','6-8','8-10'],
                datasets: [{
                    label: 'Số HS',
                    data: [1, 6, 12, 18, 5],
                    borderColor: monoBorder,
                    backgroundColor: 'rgba(0,0,0,0.1)'
                }]
            },
            options: { scales: { x: { ticks: monoTicks, grid: monoGrid }, y: { beginAtZero: true, ticks: monoTicks, grid: monoGrid } }, plugins: { legend: { labels: { color: '#111' } } } }
        });
        // Boxplot (approx with min/qt/median/qt/max lines using custom draw)
        const ctx = document.getElementById('boxplot').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: { labels: ['Lớp 10A1'], datasets: [{ label: 'Median', data: [6.8], borderColor: monoBorder, pointRadius: 4, backgroundColor: 'rgba(0,0,0,0.05)' }] },
            options: { scales: { x: { ticks: monoTicks, grid: monoGrid }, y: { beginAtZero: true, ticks: monoTicks, grid: monoGrid, suggestedMax: 10 } }, plugins: { legend: { labels: { color: '#111' } } },
                plugins: [{ id:'boxHelper', afterDraw(chart, args, opts){ const {ctx, chartArea:{left,right,top,bottom}, scales } = chart; const x = scales.x.getPixelForValue(0); const q1=5.5, q3=8.2, min=3.2, max=9.6; const med=6.8; ctx.save(); ctx.strokeStyle = '#111'; ctx.fillStyle='rgba(0,0,0,0.05)'; const yq1 = scales.y.getPixelForValue(q1); const yq3 = scales.y.getPixelForValue(q3); const ymed = scales.y.getPixelForValue(med); const ymin = scales.y.getPixelForValue(min); const ymax = scales.y.getPixelForValue(max); const bw = 60; ctx.fillRect(x - bw/2, yq3, bw, yq1 - yq3); ctx.strokeRect(x - bw/2, yq3, bw, yq1 - yq3); ctx.beginPath(); ctx.moveTo(x - bw/2, ymed); ctx.lineTo(x + bw/2, ymed); ctx.stroke(); ctx.beginPath(); ctx.moveTo(x, yq3); ctx.lineTo(x, ymax); ctx.moveTo(x, yq1); ctx.lineTo(x, ymin); ctx.stroke(); ctx.restore(); } }] }
        });
        document.getElementById('exportIssues')?.addEventListener('click', () => alert('Export danh sách câu khó (UI demo)'));
    </script>
</x-app-layout>


