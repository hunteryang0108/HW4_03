@props([
    'label' => null,
])

<div class="mb-4 {{ $attributes->get('class') }}">
    @if($label)
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
            {{ $label }}
        </label>
    @endif
    
    <textarea
        {{ $attributes->except('class') }}
        class="w-full px-3 py-2 border border-zinc-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:border-zinc-500 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white {{ $attributes->get('class') }}"
    >{{ $slot }}</textarea>
</div>