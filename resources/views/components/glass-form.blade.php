<div class="max-w-3xl mx-auto">
    <form {{ $attributes->merge(['class' => 
        'backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-6'
    ]) }}>
        {{ $slot }}
    </form>
</div>
