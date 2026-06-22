@php
    $a = $allocation;
    $types    = ['funds', 'materials', 'manpower', 'equipment', 'other'];
    $statuses = ['planned', 'approved', 'in_use', 'depleted', 'cancelled'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="name" :value="__('Resource Name')" class="text-white" />
        <x-text-input id="name" name="name" type="text" required
            class="mt-1 block w-full text-gray-900 rounded-md shadow-sm"
            :value="old('name', $a->name ?? '')" />
        <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-300" />
    </div>

    <div>
        <x-input-label for="resource_type" :value="__('Resource Type')" class="text-white" />
        <select id="resource_type" name="resource_type"
            class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
            @foreach ($types as $t)
                <option value="{{ $t }}" @selected(old('resource_type', $a->resource_type ?? 'funds') === $t)>
                    {{ ucfirst($t) }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('resource_type')" class="mt-2 text-red-300" />
    </div>

    <div>
        <x-input-label for="allocated_amount" :value="__('Allocated Amount')" class="text-white" />
        <x-text-input id="allocated_amount" name="allocated_amount" type="number" step="0.01" min="0" required
            class="mt-1 block w-full text-gray-900 rounded-md shadow-sm"
            :value="old('allocated_amount', $a->allocated_amount ?? '0')" />
        <x-input-error :messages="$errors->get('allocated_amount')" class="mt-2 text-red-300" />
    </div>

    <div>
        <x-input-label for="used_amount" :value="__('Used Amount')" class="text-white" />
        <x-text-input id="used_amount" name="used_amount" type="number" step="0.01" min="0"
            class="mt-1 block w-full text-gray-900 rounded-md shadow-sm"
            :value="old('used_amount', $a->used_amount ?? '0')" />
        <x-input-error :messages="$errors->get('used_amount')" class="mt-2 text-red-300" />
    </div>

    <div>
        <x-input-label for="unit" :value="__('Unit (e.g. NGN, bags, hrs)')" class="text-white" />
        <x-text-input id="unit" name="unit" type="text"
            class="mt-1 block w-full text-gray-900 rounded-md shadow-sm"
            :value="old('unit', $a->unit ?? '')" />
        <x-input-error :messages="$errors->get('unit')" class="mt-2 text-red-300" />
    </div>

    <div>
        <x-input-label for="status" :value="__('Status')" class="text-white" />
        <select id="status" name="status"
            class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
            @foreach ($statuses as $s)
                <option value="{{ $s }}" @selected(old('status', $a->status ?? 'planned') === $s)>
                    {{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2 text-red-300" />
    </div>

    <div>
        <x-input-label for="allocated_at" :value="__('Allocated At')" class="text-white" />
        <x-text-input id="allocated_at" name="allocated_at" type="date"
            class="mt-1 block w-full text-gray-900 rounded-md shadow-sm"
            :value="old('allocated_at', optional($a->allocated_at ?? null)->format('Y-m-d'))" />
        <x-input-error :messages="$errors->get('allocated_at')" class="mt-2 text-red-300" />
    </div>
</div>

<div class="mt-4">
    <x-input-label for="notes" :value="__('Notes')" class="text-white" />
    <textarea id="notes" name="notes" rows="3"
        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 text-gray-900">{{ old('notes', $a->notes ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('notes')" class="mt-2 text-red-300" />
</div>
