<div {{ $attributes->merge(['class' => 
    'backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl 
     p-6 hover:shadow-2xl transition-transform transform hover:-translate-y-1'
]) }}>
    {{ $slot }}
</div>
