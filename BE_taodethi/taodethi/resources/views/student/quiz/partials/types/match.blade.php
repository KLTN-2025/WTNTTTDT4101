@php
    $questionId = $question->id;
    $leftItems = $options['left'] ?? [];
    $rightItems = $options['right'] ?? [];
    $pairs = is_array($savedValue) ? $savedValue : [];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <p class="text-xs text-gray-600 mb-2">Danh sách bên trái</p>
        <ul class="space-y-2 left-items" data-question-id="{{ $questionId }}">
            @if(is_array($leftItems))
                @foreach($leftItems as $key => $label)
                    <li 
                        data-key="{{ $key }}" 
                        class="px-3 py-2 border border-gray-300 rounded text-sm text-gray-800 cursor-pointer hover:bg-gray-50 hover:ring-2 hover:ring-black"
                    >
                        {{ $label }}
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
    <div>
        <p class="text-xs text-gray-600 mb-2">Danh sách bên phải</p>
        <ul class="space-y-2 right-items" data-question-id="{{ $questionId }}">
            @if(is_array($rightItems))
                @foreach($rightItems as $key => $label)
                    <li 
                        data-key="{{ $key }}" 
                        class="px-3 py-2 border border-gray-300 rounded text-sm text-gray-800 cursor-pointer hover:bg-gray-50 hover:ring-2 hover:ring-black"
                    >
                        {{ $label }}
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>

<p class="text-xs text-gray-600 mt-3">Nhấp chọn mỗi bên để ghép cặp, nhấn "Xóa ghép" để bỏ chọn.</p>

<div class="mt-3 flex items-center gap-3">
    <button type="button" class="match-btn inline-flex items-center px-4 py-2 bg-black border border-black rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-white hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition" data-question-id="{{ $questionId }}">Ghép</button>
    <button type="button" class="unmatch-btn inline-flex items-center px-4 py-2 bg-white border border-black rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition" data-question-id="{{ $questionId }}">Xóa ghép</button>
</div>

<div class="match-result mt-3 text-sm text-gray-800" data-question-id="{{ $questionId }}"></div>

<input type="hidden" name="q_{{ $questionId }}_pairs" id="pairs_{{ $questionId }}" value="{{ json_encode($pairs) }}">

<script>
    (function() {
        const qId = {{ $questionId }};
        let leftSelected = null, rightSelected = null;
        const left = document.querySelector(`.left-items[data-question-id="${qId}"]`);
        const right = document.querySelector(`.right-items[data-question-id="${qId}"]`);
        const result = document.querySelector(`.match-result[data-question-id="${qId}"]`);
        const pairs = new Map({{ json_encode($pairs) }});
        const pairsInput = document.getElementById(`pairs_${qId}`);

        function renderPairs() {
            if (!result) return;
            result.innerHTML = '';
            pairs.forEach((rv, lv) => {
                const row = document.createElement('div');
                row.className = 'text-sm text-gray-800 mb-1';
                row.textContent = `${lv} ↔ ${rv}`;
                result.appendChild(row);
            });
            if (pairsInput) {
                pairsInput.value = JSON.stringify(Object.fromEntries(pairs));
            }
        }

        function clearActive(list) {
            if (!list) return;
            list.querySelectorAll('li').forEach(li => li.classList.remove('ring-2', 'ring-black'));
        }

        function activate(li) { 
            if (li) li.classList.add('ring-2', 'ring-black'); 
        }

        if (left && right) {
            left.addEventListener('click', e => {
                const li = e.target.closest('li');
                if (!li) return;
                clearActive(left);
                leftSelected = li.getAttribute('data-key');
                activate(li);
            });
            
            right.addEventListener('click', e => {
                const li = e.target.closest('li');
                if (!li) return;
                clearActive(right);
                rightSelected = li.getAttribute('data-key');
                activate(li);
            });

            const matchBtn = document.querySelector(`.match-btn[data-question-id="${qId}"]`);
            const unmatchBtn = document.querySelector(`.unmatch-btn[data-question-id="${qId}"]`);

            if (matchBtn) {
                matchBtn.addEventListener('click', () => {
                    if (!leftSelected || !rightSelected) return;
                    pairs.set(leftSelected, rightSelected);
                    clearActive(left); 
                    clearActive(right);
                    leftSelected = rightSelected = null;
                    renderPairs();
                });
            }

            if (unmatchBtn) {
                unmatchBtn.addEventListener('click', () => {
                    if (leftSelected) pairs.delete(leftSelected);
                    clearActive(left); 
                    clearActive(right);
                    leftSelected = rightSelected = null;
                    renderPairs();
                });
            }
        }

        renderPairs();
    })();
</script>

