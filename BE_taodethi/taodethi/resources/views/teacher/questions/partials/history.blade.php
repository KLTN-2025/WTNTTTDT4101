<ul class="divide-y divide-gray-100">
    @forelse($versions as $version)
        <li class="py-3">
            <p class="text-sm text-gray-900">v{{ $version->version_number }} • {{ $version->created_at->format('d/m/Y H:i') }}</p>
            <p class="text-xs text-gray-600">{{ $version->change_note }}</p>
            <p class="text-xs text-gray-500 mt-1">Bởi: {{ $version->creator->name ?? 'N/A' }}</p>
        </li>
    @empty
        <li class="py-3 text-sm text-gray-500">Chưa có lịch sử phiên bản</li>
    @endforelse
</ul>

