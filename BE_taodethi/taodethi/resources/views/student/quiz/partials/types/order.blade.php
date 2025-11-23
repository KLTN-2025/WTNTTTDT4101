@php
    $questionId = $question->id;
    $items = $options ?? [];
    $savedOrder = is_array($savedValue) ? $savedValue : [];
    
    if (empty($savedOrder) && is_array($items)) {
        $savedOrder = array_keys($items);
        shuffle($savedOrder);
    }
@endphp

<ul class="space-y-2 order-list" data-order-list>
    @if(is_array($items) && !empty($savedOrder))
        @foreach($savedOrder as $key)
            @if(isset($items[$key]))
                <li 
                    draggable="true" 
                    data-value="{{ $key }}"
                    class="cursor-move px-3 py-2 border border-gray-300 rounded text-sm text-gray-800 bg-white hover:bg-gray-50"
                >
                    {{ $items[$key] }}
                </li>
            @endif
        @endforeach
    @endif
</ul>

<script>
    (function() {
        const list = document.querySelector('[data-order-list]');
        if (!list) return;
        
        let dragEl = null;
        
        list.querySelectorAll('li').forEach(li => {
            li.addEventListener('dragstart', e => { 
                dragEl = li; 
                li.classList.add('opacity-60'); 
            });
            
            li.addEventListener('dragend', e => { 
                dragEl = null; 
                li.classList.remove('opacity-60'); 
            });
            
            li.addEventListener('dragover', e => { 
                e.preventDefault(); 
            });
            
            li.addEventListener('drop', e => {
                e.preventDefault();
                if (!dragEl || dragEl === li) return;
                const items = Array.from(list.children);
                const dragIdx = items.indexOf(dragEl);
                const dropIdx = items.indexOf(li);
                if (dragIdx < dropIdx) list.insertBefore(dragEl, li.nextSibling);
                else list.insertBefore(dragEl, li);
            });
        });
    })();
</script>

