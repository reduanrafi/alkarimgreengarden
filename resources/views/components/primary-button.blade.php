<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-[#173d2b] to-[#3f8a5c] border border-transparent rounded-xl font-semibold text-sm text-white shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200']) }}>
    {{ $slot }}
</button>
