@props([
    'href' => '#',
])

<a 
    href="{{ $href }}" 
    {{ $attributes->merge(['class' => 'text-zinc-900 hover:text-zinc-700 underline dark:text-zinc-300 dark:hover:text-white']) }}
>
    {{ $slot }}
</a>