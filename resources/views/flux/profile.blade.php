@props([
    'name' => null,
    'initials' => null,
    'size' => 'md',
])

@php
    $sizeClasses = [
        'sm' => 'h-8 w-8 text-xs',
        'md' => 'h-10 w-10 text-sm',
        'lg' => 'h-12 w-12 text-base',
    ][$size] ?? 'h-10 w-10 text-sm';
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center']) }}>
    <span class="relative flex {{ $sizeClasses }} shrink-0 overflow-hidden rounded-full">
        <span class="flex h-full w-full items-center justify-center rounded-full bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
            {{ $initials }}
        </span>
    </span>
    
    @if ($name)
        <span class="ml-2 text-sm font-medium">{{ $name }}</span>
    @endif
</div>