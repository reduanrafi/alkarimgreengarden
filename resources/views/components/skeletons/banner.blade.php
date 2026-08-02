@props(['height' => '65vh'])

<div class="relative w-full overflow-hidden animate-pulse" style="min-height: {{ $height }}">
    <div class="absolute inset-0 bg-gray-200"></div>
    <div class="absolute inset-0 flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-xl space-y-4">
                <div class="h-4 w-32 bg-gray-300/70 rounded-full"></div>
                <div class="h-10 sm:h-14 w-2/3 bg-gray-300/70 rounded-lg"></div>
                <div class="h-4 w-1/2 bg-gray-300/70 rounded"></div>
                <div class="h-12 w-40 bg-gray-300/70 rounded-full"></div>
            </div>
        </div>
    </div>
</div>
