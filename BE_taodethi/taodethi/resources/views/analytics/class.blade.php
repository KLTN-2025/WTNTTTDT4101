<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Báo cáo lớp / trường') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Filters -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-4">
                <form 
                    hx-get="{{ route('analytics.class') }}"
                    hx-target="#analytics-content"
                    hx-swap="innerHTML"
                    class="flex flex-col md:flex-row md:items-center gap-3">
                    <div class="flex-1">
                        <select name="class_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="">Tất cả lớp</option>
                            @foreach($classes ?? [] as $class)
                                <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <select name="exam_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="">Tất cả đề thi</option>
                            @foreach($exams ?? [] as $exam)
                                <option value="{{ $exam->id }}" {{ $examId == $exam->id ? 'selected' : '' }}>
                                    {{ $exam->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">
                        Xem báo cáo
                    </button>
                </form>
            </div>

            <div id="analytics-content">
            <!-- Phân bố điểm -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Phân bố điểm</h3>
                        @if($classId && isset($classes))
                            <div class="text-sm text-gray-700">
                                Lớp: <span class="text-gray-900">{{ $classes->find($classId)?->name ?? '—' }}</span>
                            </div>
                        @endif
                </div>
                    @if(empty($scoreDistribution['histogram']))
                        <p class="text-sm text-gray-500">Chưa có dữ liệu phân bố điểm</p>
                    @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <canvas id="histogram" height="180"></canvas>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <canvas id="boxplot" height="180"></canvas>
                    </div>
                </div>
                        <div class="mt-4 text-sm text-gray-700">
                            <p>Trung bình: <span class="font-semibold">{{ $scoreDistribution['statistics']['mean'] }}</span></p>
                            <p>Trung vị: <span class="font-semibold">{{ $scoreDistribution['statistics']['median'] }}</span></p>
                            <p>Min: <span class="font-semibold">{{ $scoreDistribution['statistics']['min'] }}</span> | Max: <span class="font-semibold">{{ $scoreDistribution['statistics']['max'] }}</span></p>
                        </div>
                    @endif
            </div>

                <!-- Item analysis -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Item analysis</h3>
                    @if(empty($itemAnalysis))
                        <p class="text-sm text-gray-500">Chưa có dữ liệu phân tích câu hỏi. Vui lòng chọn một đề thi cụ thể.</p>
                    @else
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
                                    @foreach($itemAnalysis as $item)
                            <tr>
                                            <td class="px-3 py-2 text-gray-900">{{ $item['question_title'] }}</td>
                                            <td class="px-3 py-2 text-gray-700">{{ $item['correct_rate'] }}%</td>
                                            <td class="px-3 py-2 text-gray-700">{{ $item['difficulty'] }}</td>
                                            <td class="px-3 py-2 text-gray-700">{{ $item['discrimination'] }}</td>
                                            <td class="px-3 py-2 text-gray-700 text-xs">{{ $item['suggestion'] }}</td>
                            </tr>
                                    @endforeach
                        </tbody>
                    </table>
                </div>
                    @endif
            </div>

            <!-- Top câu khó / chưa hiểu -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Câu khó/chưa hiểu nhiều</h3>
                        <button 
                            onclick="exportDifficultQuestions()"
                            class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">
                            Export danh sách
                        </button>
                    </div>
                    @if(empty($difficultQuestions))
                        <p class="text-sm text-gray-500">Chưa có câu hỏi khó cần xử lý</p>
                    @else
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-800">
                            @foreach($difficultQuestions as $item)
                                <li class="border border-gray-200 rounded-lg p-4">
                                    <div class="font-semibold mb-1">{{ $item['question_title'] }}</div>
                                    <div class="text-xs text-gray-600">
                                        p={{ $item['difficulty'] }} • r={{ $item['discrimination'] }}
                                    </div>
                                    <div class="text-xs text-gray-700 mt-1">{{ $item['suggestion'] }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const monoTicks = { color: '#111', font: { size: 11 } };
        const monoGrid = { color: '#e5e7eb' };
        const monoBorder = '#111';

        @if(!empty($scoreDistribution['histogram']))
        const histogramData = @json($scoreDistribution['histogram']);
        const histogramLabels = Object.keys(histogramData);
        const histogramValues = Object.values(histogramData);

        if (document.getElementById('histogram')) {
        new Chart(document.getElementById('histogram'), {
            type: 'bar',
            data: {
                    labels: histogramLabels,
                datasets: [{
                        label: 'Số học sinh',
                        data: histogramValues,
                    borderColor: monoBorder,
                    backgroundColor: 'rgba(0,0,0,0.1)'
                }]
            },
                options: { 
                    scales: { 
                        x: { ticks: monoTicks, grid: monoGrid }, 
                        y: { beginAtZero: true, ticks: monoTicks, grid: monoGrid } 
                    }, 
                    plugins: { legend: { labels: { color: '#111' } } } 
                }
        });
        }

        const stats = @json($scoreDistribution['statistics']);
        if (document.getElementById('boxplot')) {
        const ctx = document.getElementById('boxplot').getContext('2d');
        new Chart(ctx, {
            type: 'line',
                data: { 
                    labels: ['Phân bố điểm'], 
                    datasets: [{ 
                        label: 'Trung vị', 
                        data: [stats.median], 
                        borderColor: monoBorder, 
                        pointRadius: 4, 
                        backgroundColor: 'rgba(0,0,0,0.05)' 
                    }] 
                },
                options: { 
                    scales: { 
                        x: { ticks: monoTicks, grid: monoGrid }, 
                        y: { 
                            beginAtZero: true, 
                            ticks: monoTicks, 
                            grid: monoGrid, 
                            suggestedMax: Math.max(10, stats.max + 1) 
                        } 
                    }, 
                    plugins: { legend: { labels: { color: '#111' } } },
                    plugins: [{ 
                        id:'boxHelper', 
                        afterDraw(chart, args, opts){ 
                            const {ctx, chartArea:{left,right,top,bottom}, scales } = chart; 
                            const x = scales.x.getPixelForValue(0); 
                            const q1 = stats.q1, q3 = stats.q3, min = stats.min, max = stats.max, med = stats.median; 
                            ctx.save(); 
                            ctx.strokeStyle = '#111'; 
                            ctx.fillStyle='rgba(0,0,0,0.05)'; 
                            const yq1 = scales.y.getPixelForValue(q1); 
                            const yq3 = scales.y.getPixelForValue(q3); 
                            const ymed = scales.y.getPixelForValue(med); 
                            const ymin = scales.y.getPixelForValue(min); 
                            const ymax = scales.y.getPixelForValue(max); 
                            const bw = 60; 
                            ctx.fillRect(x - bw/2, yq3, bw, yq1 - yq3); 
                            ctx.strokeRect(x - bw/2, yq3, bw, yq1 - yq3); 
                            ctx.beginPath(); 
                            ctx.moveTo(x - bw/2, ymed); 
                            ctx.lineTo(x + bw/2, ymed); 
                            ctx.stroke(); 
                            ctx.beginPath(); 
                            ctx.moveTo(x, yq3); 
                            ctx.lineTo(x, ymax); 
                            ctx.moveTo(x, yq1); 
                            ctx.lineTo(x, ymin); 
                            ctx.stroke(); 
                            ctx.restore(); 
                        } 
                    }] 
                }
        });
        }
        @endif

        function exportDifficultQuestions() {
            const data = @json($difficultQuestions ?? []);
            const csv = 'Câu hỏi,Tỉ lệ đúng,Độ khó,Phân biệt,Gợi ý\n' + 
                data.map(item => 
                    `"${item.question_title}",${item.correct_rate}%,${item.difficulty},${item.discrimination},"${item.suggestion}"`
                ).join('\n');
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'cau-hoi-kho.csv';
            a.click();
        }
    </script>
</x-app-layout>
