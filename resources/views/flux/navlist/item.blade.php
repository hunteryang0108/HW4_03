@props([
    'icon' => null,
    'href' => '#',
    'current' => false,
])

@php
    $classes = 'group flex items-center rounded-md px-3 py-2 text-sm font-medium w-full transition-colors';
    $classes .= $current
        ? ' bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white'
        : ' text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/50 dark:hover:text-white';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <span class="mr-3">
            <flux:icon.{{ $icon }} class="h-5 w-5" />
        </span>
    @endif
    <span>{{ $slot }}</span>
</a>