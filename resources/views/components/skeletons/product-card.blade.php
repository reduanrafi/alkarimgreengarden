@props(['count' => 8])

@for($i = 0; $i < $count; $i++)
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden animate-pulse">
        <div class="aspect-square bg-gray-100"></div>
        <div class="p-4 space-y-2.5">
            <div class="flex gap-2">
                <div class="h-3 w-16 bg-gray-100 rounded"></div>
                <div class="h-3 w-12 bg-gray-100 rounded"></div>
            </div>
            <div class="h-4 w-3/4 bg-gray-100 rounded"></div>
            <div class="h-3 w-1/2 bg-gray-100 rounded"></div>
            <div class="flex items-center justify-between pt-1">
                <div class="h-5 w-20 bg-gray-100 rounded"></div>
                <div class="h-4 w-16 bg-gray-100 rounded-full"></div>
            </div>
            <div class="h-9 w-full bg-gray-100 rounded-lg mt-2"></div>
        </div>
    </div>
@endfor
