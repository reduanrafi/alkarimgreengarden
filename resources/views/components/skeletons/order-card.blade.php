@props(['count' => 3])

@for($i = 0; $i < $count; $i++)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 animate-pulse">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gray-100 rounded-xl"></div>
                <div class="space-y-2">
                    <div class="h-3 w-16 bg-gray-100 rounded"></div>
                    <div class="h-4 w-32 bg-gray-100 rounded"></div>
                    <div class="h-3 w-24 bg-gray-100 rounded"></div>
                </div>
            </div>
            <div class="flex items-center gap-4 sm:text-right">
                <div class="space-y-2">
                    <div class="h-4 w-20 bg-gray-100 rounded"></div>
                    <div class="h-3 w-16 bg-gray-100 rounded ml-auto sm:ml-0"></div>
                </div>
                <div class="h-7 w-24 bg-gray-100 rounded-full"></div>
            </div>
        </div>
    </div>
@endfor
