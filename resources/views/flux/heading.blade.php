@props([
    'size' => 'lg',
    'level' => 2,
])

@php
    $sizeClasses = [
        'xs' => 'text-xs font-semibold',
        'sm' => 'text-sm font-semibold',
        'base' => 'text-base font-semibold',
        'lg' => 'text-lg font-semibold',
        'xl' => 'text-xl font-bold',
        '2xl' => 'text-2xl font-bold',
    ][$size] ?? 'text-lg font-semibold';
    
    // 確保level值在1-6之間
    $level = max(1, min(6, $level));
@endphp

<div {{ $attributes->merge(['class' => "text-zinc-900 dark:text-white $sizeClasses"]) }}>
    @switch($level)
        @case(1)
            <h1>{{ $slot }}</h1>
            @break
        @case(2)
            <h2>{{ $slot }}</h2>
            @break
        @case(3)
            <h3>{{ $slot }}</h3>
            @break
        @case(4)
            <h4>{{ $slot }}</h4>
            @break
        @case(5)
            <h5>{{ $slot }}</h5>
            @break
        @case(6)
            <h6>{{ $slot }}</h6>
            @break
    @endswitch
</div>