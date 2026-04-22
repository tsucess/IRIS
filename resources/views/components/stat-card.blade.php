@props(['title', 'value'])

{{-- <div class="bg-white p-4 rounded shadow text-center">
    <h4 class="text-gray-500 text-sm mb-1">{{ $title }}</h4>
    <div class="text-2xl font-bold text-gray-800">{{ $value }}</div>
</div> --}}

<div
    class="backdrop-blur-md bg-white/20 rounded-xl shadow-xl border border-white/30 p-4 sm:p-6 transform transition duration-300 hover:scale-105 hover:shadow-2xl">
    <div class="flex items-center gap-3 sm:gap-4">
        <div class="shrink-0 w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-full bg-white/30 text-white text-xl sm:text-2xl">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="min-w-0">
            <h3 class="text-sm sm:text-lg font-semibold text-white truncate">{{ $title }}</h3>
            <p class="text-2xl sm:text-4xl font-bold text-white break-all">{{ $value }}</p>
        </div>
    </div>
</div>
