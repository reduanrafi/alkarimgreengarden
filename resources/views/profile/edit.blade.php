<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#66D9F1] to-[#4CC9F0] flex items-center justify-center text-white font-bold text-lg shadow-sm">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight font-serif">{{ __('My Profile') }}</h2>
                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 card-hover">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 card-hover">
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6 sm:p-8 card-hover">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
