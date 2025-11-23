<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Chấm điểm tự động (Tự luận)') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Rubric builder -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Rubric chấm điểm</h3>
                    <button id="add-criterion" class="text-xs underline text-gray-700 hover:text-black">Thêm tiêu chí</button>
                </div>
                <div class="overflow-hidden border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tiêu chí</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Trọng số</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Mô tả</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Xoá</th>
                            </tr>
                        </thead>
                        <tbody id="rubric" class="bg-white divide-y divide-gray-100">
                            <!-- rows js -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Essay scoring -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Chấm điểm tự động + similarity</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Bài làm học sinh</label>
                        <textarea id="essay" rows="10" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Dán bài tự luận..."></textarea>
                        <div class="mt-3 flex items-center gap-3">
                            <button id="score-btn" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">Tính điểm</button>
                            <button id="sim-btn" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Similarity check</button>
                        </div>
                    </div>
                    <div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <p class="text-sm text-gray-900 font-semibold">Kết quả</p>
                            <p class="text-sm text-gray-800 mt-1">Điểm dự kiến: <span id="score" class="font-semibold text-gray-900">—/10</span></p>
                            <p class="text-sm text-gray-800 mt-1">Similarity: <span id="sim" class="font-semibold text-gray-900">—%</span></p>
                            <div class="mt-3">
                                <p class="text-sm text-gray-900 font-semibold">Gợi ý chấm</p>
                                <ul id="hints" class="mt-1 list-disc ml-5 text-sm text-gray-800 space-y-1">
                                    <li>Kiểm tra luận điểm rõ ràng</li>
                                    <li>Ví dụ dẫn chứng phù hợp</li>
                                    <li>Ngôn ngữ mạch lạc</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const rubric = document.getElementById('rubric');
        function addRow(name='Luận điểm', weight=0.4, desc='Rõ ràng, logic'){
            const tr=document.createElement('tr');
            tr.innerHTML = `
                <td class="px-3 py-2"><input type="text" value="${name}" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></td>
                <td class="px-3 py-2"><input type="number" min="0" max="1" step="0.1" value="${weight}" class="w-28 border border-gray-300 rounded-md px-3 py-2 text-sm"></td>
                <td class="px-3 py-2"><input type="text" value="${desc}" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></td>
                <td class="px-3 py-2 text-right"><button class="text-xs underline text-gray-700 hover:text-black" data-del>Xoá</button></td>`;
            tr.querySelector('[data-del]').addEventListener('click', ()=>tr.remove());
            rubric.appendChild(tr);
        }
        document.getElementById('add-criterion')?.addEventListener('click', ()=>addRow('Dẫn chứng',0.3,'Ví dụ phù hợp'));
        // default rows
        addRow('Luận điểm',0.4,'Rõ ràng, logic');
        addRow('Dẫn chứng',0.3,'Ví dụ phù hợp');
        addRow('Ngôn ngữ',0.3,'Mạch lạc, chính tả');

        // Mock scoring
        document.getElementById('score-btn')?.addEventListener('click', ()=>{
            // demo: tính điểm dựa vào độ dài và trọng số
            const text = (document.getElementById('essay').value || '').trim();
            const len = text.split(/\s+/).length;
            let base = Math.min(10, (len/150)*10);
            document.getElementById('score').textContent = `${base.toFixed(1)}/10`;
        });
        // Mock similarity
        document.getElementById('sim-btn')?.addEventListener('click', ()=>{
            const percent = Math.floor(Math.random()*40)+10; // 10-50%
            document.getElementById('sim').textContent = percent + '%';
        });
    </script>
</x-app-layout>


