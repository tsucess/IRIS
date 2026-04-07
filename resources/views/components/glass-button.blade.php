<button {{ $attributes->merge(['class' => 
    'px-4 py-2 rounded-md text-white font-semibold 
    bg-gradient-to-r from-blue-500 to-purple-600 shadow-lg hover:shadow-xl 
    hover:scale-105 transition-transform duration-300'
]) }}>
    {{ $slot }}
</button>
