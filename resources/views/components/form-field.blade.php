@props(['name', 'label', 'type' => 'text', 'required' => false, 'help' => null, 'value' => null])

<div {{ $attributes->merge(['class' => 'mb-4']) }}>
    <x-input-label :for="$name" :value="$label" :required="$required" />

    @if($type === 'textarea')
    <textarea id="{{ $name }}" name="{{ $name }}" rows="4" {{ $required ? 'required' : '' }}
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old($name, $value) }}</textarea>
    @elseif($type === 'select')
    <select id="{{ $name }}" name="{{ $name }}" {{ $required ? 'required' : '' }}
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        {{ $slot }}
    </select>
    @elseif($type === 'file')
    <input type="file" id="{{ $name }}" name="{{ $name }}" {{ $required ? 'required' : '' }}
        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
    @else
    <x-text-input :id="$name" :name="$name" :type="$type" :value="old($name, $value)" :required="$required"
        class="mt-1 block w-full" />
    @endif

    @if($help)
    <p class="mt-1 text-sm text-gray-500">{{ $help }}</p>
    @endif

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>

