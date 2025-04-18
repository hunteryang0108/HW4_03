@props([
    'icon' => null,
    'href' => '#',
    'label' => null,
    'current' => false,
])

@php
    $classes = 'group inline-flex h-10 w-max items-center justify-center px-4 py-2 text-sm font-medium transition-colors hover:text-zinc-900 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-950 disabled:pointer-events-none disabled:opacity-50 dark:hover:text-zinc-50 dark:focus-visible:ring-zinc-300';

    $classes .= $current
        ? ' text-zinc-900 dark:text-zinc-50'
        : ' text-zinc-500 dark:text-zinc-400';

    if ($current) {
        $classes .= ' after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-zinc-900 dark:after:bg-zinc-50';
    }
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => "relative $classes"]) }}>
    <div class="flex items-center gap-2">
        @if($icon)
            <flux:icon.{{ $icon }} class="h-5 w-5" />
        @endif
        
        @if($label)
            <span>{{ $label }}</span>
        @else
            <span>{{ $slot }}</span>
        @endif
    </div>
</a>