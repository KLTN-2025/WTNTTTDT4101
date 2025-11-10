<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Sinh câu hỏi tự động từ tài liệu') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Input sources -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Nguồn tài liệu</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Tải file (PDF, PPTX, DOCX)</label>
                        <input id="doc-file" type="file" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" accept=".pdf,.ppt,.pptx,.doc,.docx,.txt">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Dán nội dung (tuỳ chọn)</label>
                        <textarea id="doc-text" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Dán bài giảng/ghi chú..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Generation options -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tuỳ chọn sinh câu hỏi</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Số câu</label>
                        <input id="q-count" type="number" min="1" value="10" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Loại câu hỏi</label>
                        <select id="q-type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="mcq">Multiple-choice</option>
                            <option value="tf">Đúng/Sai</option>
                            <option value="stem">Stem (đặt đề)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Độ khó mục tiêu</label>
                        <select id="q-diff" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="mix">Trộn</option>
                            <option value="easy">Dễ</option>
                            <option value="medium">Trung bình</option>
                            <option value="hard">Khó</option>
                        </select>
                    </div>
                    <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-center gap-2 text-sm text-gray-800"><input id="opt-distractors" type="checkbox" class="border-gray-400" checked> Sinh distractors hợp lý</label>
                        <label class="flex items-center gap-2 text-sm text-gray-800"><input id="opt-cite" type="checkbox" class="border-gray-400"> Đính kèm trích dẫn đoạn nguồn</label>
                        <label class="flex items-center gap-2 text-sm text-gray-800"><input id="opt-personalize" type="checkbox" class="border-gray-400"> Cá nhân hoá theo mức của HS</label>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-3">
                    <button id="generate" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-white hover:text-black">Sinh câu hỏi</button>
                    <button id="clear" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Xoá kết quả</button>
                </div>
            </div>

            <!-- Preview & selection -->
            <div class="bg-white border border-gray-200 sm:rounded-xl p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900">Kết quả sinh</h3>
                    <div class="text-sm text-gray-700">Đã chọn: <span id="sel-count" class="text-gray-900">0</span></div>
                </div>
                <ul id="gen-list" class="space-y-4">
                    <!-- injected by JS -->
                </ul>
                <div class="mt-4 flex items-center justify-end gap-3">
                    <button id="import-qb" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white">Thêm vào Ngân hàng câu hỏi</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function mockGenerate(n, type){
            const arr = [];
            for(let i=1;i<=n;i++){
                if(type==='tf') arr.push({id:i,type, stem:`Phát biểu #${i} đúng hay sai?`, options:['Đúng','Sai'], correct:'Đúng', cite:'Trang 12'});
                else if(type==='stem') arr.push({id:i,type, stem:`Viết đề bài #${i} từ chủ điểm X.`, options:[], correct:null, cite:'Slide 5'});
                else arr.push({id:i,type:'mcq', stem:`Câu MCQ #${i}: chọn đáp án đúng`, options:['A','B','C','D'], correct:'B', cite:'PDF p.7'});
            }
            return arr;
        }
        const list = document.getElementById('gen-list');
        function render(items){
            list.innerHTML='';
            items.forEach(it=>{
                const li=document.createElement('li');
                li.className='border border-gray-200 rounded-lg p-4';
                li.innerHTML = `<div class="flex items-start justify-between">
                    <div>
                        <label class="flex items-center gap-2 text-sm text-gray-900"><input type="checkbox" class="pick"> <span class="font-semibold">[${it.type.toUpperCase()}]</span> ${it.stem}</label>
                        ${it.options?.length? `<ul class='mt-2 ml-6 list-disc text-sm text-gray-800'>${it.options.map(o=>`<li>${o}</li>`).join('')}</ul>`:''}
                        ${it.cite? `<p class='mt-2 text-xs text-gray-600'>Nguồn: ${it.cite}</p>`:''}
                    </div>
                    <button class="text-xs underline text-gray-700 hover:text-black">Chỉnh</button>
                </div>`;
                list.appendChild(li);
            });
            updateSel();
            list.querySelectorAll('.pick').forEach(c=>c.addEventListener('change', updateSel));
        }
        function updateSel(){
            const cnt = list.querySelectorAll('.pick:checked').length;
            document.getElementById('sel-count').textContent = cnt;
        }
        document.getElementById('generate')?.addEventListener('click', ()=>{
            const n = Number(document.getElementById('q-count').value||10);
            const t = document.getElementById('q-type').value;
            render(mockGenerate(n,t));
        });
        document.getElementById('clear')?.addEventListener('click', ()=>{ list.innerHTML=''; updateSel(); });
        document.getElementById('import-qb')?.addEventListener('click', ()=>{
            const selected = list.querySelectorAll('.pick:checked').length;
            if(!selected) { alert('Chọn ít nhất 1 câu hỏi.'); return; }
            alert(`Đã thêm ${selected} câu vào Ngân hàng (UI demo).`);
        });
    </script>
</x-app-layout>


