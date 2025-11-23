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
                    <div class="text-sm text-gray-700">Cập nhật: <span class="text-gray-900">{{ now()->format('d/m/Y H:i') }}</span></div>
                </div>
                @if(empty($scoresBySkill))
                    <p class="text-sm text-gray-500">Chưa có dữ liệu điểm theo kỹ năng</p>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <canvas id="skillsRadar" height="200"></canvas>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <canvas id="skillsBar" height="200"></canvas>
                    </div>
                </div>
                @endif
            </div>

            <!-- Biểu đồ tiến bộ theo thời gian -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tiến bộ theo thời gian</h3>
                @if(empty($progressOverTime))
                    <p class="text-sm text-gray-500">Chưa có dữ liệu tiến bộ</p>
                @else
                <div class="border border-gray-200 rounded-lg p-4">
                    <canvas id="progressLine" height="120"></canvas>
                </div>
                @endif
            </div>

            <!-- Đề xuất ôn tập -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Đề xuất ôn tập</h3>
                    <form 
                        hx-get="{{ route('dashboard.refresh-suggestions') }}"
                        hx-target="#suggestList"
                        hx-swap="innerHTML">
                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">
                            Làm mới
                        </button>
                    </form>
                </div>
                <ul id="suggestList" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($suggestions as $suggestion)
                        <li class="border border-gray-200 rounded-lg p-4">
                            <div class="text-sm font-semibold text-gray-900 mb-1">{{ $suggestion->title }}</div>
                            <div class="text-xs text-gray-600">{{ $suggestion->description }}</div>
                        </li>
                    @empty
                        <li class="col-span-2 text-sm text-gray-500">Chưa có đề xuất ôn tập</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const monoTicks = { color: '#111', font: { size: 11 } };
        const monoGrid = { color: '#e5e7eb' };
        const monoBorder = '#111';

        @if(!empty($scoresBySkill))
        const skillData = @json($scoresBySkill);
        const skillLabels = Object.keys(skillData);
        const skillValues = Object.values(skillData);

        // Radar skills
        if (document.getElementById('skillsRadar')) {
        new Chart(document.getElementById('skillsRadar'), {
            type: 'radar',
            data: {
                    labels: skillLabels,
                datasets: [{
                        label: 'Điểm (%)',
                        data: skillValues,
                    borderColor: monoBorder,
                    backgroundColor: 'rgba(0,0,0,0.05)',
                    pointBackgroundColor: monoBorder,
                }]
            },
                options: { 
                    scales: { 
                        r: { 
                            beginAtZero: true,
                            max: 100,
                            angleLines: { color: '#e5e7eb' }, 
                            grid: { color: '#e5e7eb' }, 
                            pointLabels: { color: '#111' }, 
                            ticks: { display: false } 
                        } 
                    } 
                }
        });
        }

        // Bar skills
        if (document.getElementById('skillsBar')) {
        new Chart(document.getElementById('skillsBar'), {
            type: 'bar',
            data: {
                    labels: skillLabels,
                datasets: [{
                        label: 'Điểm (%)',
                        data: skillValues,
                    borderColor: monoBorder,
                    backgroundColor: 'rgba(0,0,0,0.1)'
                }]
            },
                options: { 
                    scales: { 
                        x: { ticks: monoTicks, grid: monoGrid }, 
                        y: { beginAtZero: true, max: 100, ticks: monoTicks, grid: monoGrid } 
                    }, 
                    plugins: { legend: { labels: { color: '#111' } } } 
                }
        });
        }
        @endif

        @if(!empty($progressOverTime))
        const progressData = @json($progressOverTime);
        const progressLabels = progressData.map(p => p.label);
        const progressAverages = progressData.map(p => p.average);

        // Progress line
        if (document.getElementById('progressLine')) {
        new Chart(document.getElementById('progressLine'), {
            type: 'line',
            data: {
                    labels: progressLabels,
                datasets: [{
                    label: 'Điểm trung bình',
                        data: progressAverages,
                    borderColor: monoBorder,
                    backgroundColor: 'rgba(0,0,0,0.05)',
                    tension: 0.2
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
        @endif
    </script>
</x-app-layout>
