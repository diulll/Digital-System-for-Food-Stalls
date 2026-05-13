@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-ink text-start text-base font-medium text-ink bg-surface-soft focus:outline-none focus:text-ink focus:bg-surface-soft focus:border-ink transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-muted hover:text-ink hover:bg-surface-soft hover:border-border-strong focus:outline-none focus:text-ink focus:bg-surface-soft focus:border-border-strong transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
