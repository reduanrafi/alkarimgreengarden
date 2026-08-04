@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-xl border border-[#e6e9e2] px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#3f8a5c]/20 focus:border-[#3f8a5c] outline-none transition']) }}>
