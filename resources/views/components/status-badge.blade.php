@props(['status'])

@php
    $class = match($status) {
        'Completed' => 'badge bg-success',
        'Ongoing'   => 'badge bg-info',
        default     => 'badge bg-warning',
    };
@endphp

<span {{ $attributes->merge(['class' => $class]) }}>
    {{ $status }}
</span>
