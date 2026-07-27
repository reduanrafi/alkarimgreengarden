@props(['status' => 'pending'])

@php
    $colors = [
        'pending' => 'bg-amber-50 text-amber-700 border border-amber-200',
        'confirmed' => 'bg-blue-50 text-blue-700 border border-blue-200',
        'processing' => 'bg-indigo-50 text-indigo-700 border border-indigo-200',
        'shipped' => 'bg-purple-50 text-purple-700 border border-purple-200',
        'delivered' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'cancelled' => 'bg-red-50 text-red-700 border border-red-200',
        'returned' => 'bg-gray-50 text-gray-700 border border-gray-200',
    ];
    $color = $colors[$status] ?? 'bg-gray-50 text-gray-600 border border-gray-200';
@endphp

<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ $color }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $status === 'pending' ? 'bg-amber-500' : ($status === 'confirmed' ? 'bg-blue-500' : ($status === 'processing' ? 'bg-indigo-500' : ($status === 'shipped' ? 'bg-purple-500' : ($status === 'delivered' ? 'bg-emerald-500' : ($status === 'cancelled' ? 'bg-red-500' : 'bg-gray-500'))))) }}"></span>
    {{ ucfirst($status) }}
</span>