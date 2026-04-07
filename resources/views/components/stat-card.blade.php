@props(['title', 'value'])

{{-- <div class="bg-white p-4 rounded shadow text-center">
    <h4 class="text-gray-500 text-sm mb-1">{{ $title }}</h4>
    <div class="text-2xl font-bold text-gray-800">{{ $value }}</div>
</div> --}}

<div
    class="backdrop-blur-md bg-white/20 rounded-xl shadow-xl border border-white/30 p-6 transform transition duration-300 hover:scale-105 hover:shadow-2xl">
    <div class="flex items-center space-x-4">
        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-white/30 text-white text-2xl">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-white">{{ $title }}</h3>
            <p class="text-4xl font-bold text-white">{{ $value }}</p>
        </div>
    </div>
</div>
