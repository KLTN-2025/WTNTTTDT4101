@forelse($studySuggestions as $suggestion)
    <li class="border border-gray-200 rounded-lg p-4">
        <p class="text-sm font-medium text-gray-900">{{ $suggestion->title }}</p>
        <p class="text-xs text-gray-600 mt-1">{{ $suggestion->description }}</p>
    </li>
@empty
    <li class="col-span-2 text-sm text-gray-500 text-center py-4">Chưa có gợi ý học tập nào</li>
@endforelse

