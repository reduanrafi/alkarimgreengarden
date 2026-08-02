@props(['count' => 3])

@for($i = 0; $i < $count; $i++)
    <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 animate-pulse">
        <div class="flex items-center gap-4 sm:gap-6">
            <div class="shrink-0 w-[100px] h-[100px] sm:w-[110px] sm:h-[110px] bg-gray-100 rounded-xl"></div>
            <div class="flex-1 min-w-0 space-y-3">
                <div class="h-4 w-3/4 bg-gray-100 rounded"></div>
                <div class="h-3 w-1/2 bg-gray-100 rounded"></div>
                <div class="h-6 w-24 bg-gray-100 rounded"></div>
                <div class="flex items-center gap-4 pt-2">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg"></div>
                        <div class="w-12 h-8 bg-gray-100 rounded-lg"></div>
                        <div class="w-8 h-8 bg-gray-100 rounded-lg"></div>
                    </div>
                    <div class="h-5 w-20 bg-gray-100 rounded ml-auto"></div>
                </div>
            </div>
        </div>
    </div>
@endfor
