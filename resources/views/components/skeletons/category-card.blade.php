@props(['count' => 6])

@for($i = 0; $i < $count; $i++)
    <div class="bg-[#FFF7E6] rounded-[18px] shadow-sm overflow-hidden animate-pulse">
        <div class="h-[180px] sm:h-[200px] flex items-center justify-center p-5 sm:p-6">
            <div class="w-20 h-20 rounded-full bg-orange-100"></div>
        </div>
        <div class="pb-4 sm:pb-5 px-3 text-center">
            <div class="h-4 w-24 bg-orange-100 rounded mx-auto"></div>
        </div>
    </div>
@endfor
