@props(['status' => 'pending'])

@php
    $colors = [
        'pending' => 'bg-[#fef3c7] text-[#b45309]',
        'confirmed' => 'bg-[#e0f2fe] text-[#0369a1]',
        'processing' => 'bg-[#ede9fe] text-[#6d28d9]',
        'shipped' => 'bg-[#fae8ff] text-[#a21caf]',
        'delivered' => 'bg-[#e4efe4] text-[#1f5c3f]',
        'cancelled' => 'bg-[#fef2f2] text-[#b91c1c]',
        'returned' => 'bg-[#f3f4f6] text-[#4b5563]',
    ];
    $dots = [
        'pending' => 'bg-amber-500',
        'confirmed' => 'bg-sky-500',
        'processing' => 'bg-violet-500',
        'shipped' => 'bg-fuchsia-500',
        'delivered' => 'bg-green-600',
        'cancelled' => 'bg-red-500',
        'returned' => 'bg-gray-400',
    ];
    $color = $colors[$status] ?? 'bg-[#f3f4f6] text-[#4b5563]';
    $dot = $dots[$status] ?? 'bg-gray-400';
@endphp

<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold {{ $color }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
    {{ ucfirst($status) }}
</span>
