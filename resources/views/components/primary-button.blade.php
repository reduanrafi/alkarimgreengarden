<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-[#66D9F1] to-[#4CC9F0] border border-transparent rounded-xl font-semibold text-sm text-white shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200']) }}>
    {{ $slot }}
</button>
