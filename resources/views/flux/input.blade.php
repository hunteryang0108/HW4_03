@props([
    'type' => 'text',
    'label' => null,
    'value' => '',
])

<div {{ $attributes->where('class', '')->merge(['class' => 'mb-4']) }}>
    @if($label)
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
            {{ $label }}
        </label>
    @endif
    
    <input 
        type="{{ $type }}" 
        value="{{ $value }}"
        {{ $attributes->except('class') }}
        class="w-full px-3 py-2 border border-zinc-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:border-zinc-500 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white {{ $attributes->get('class') }}"
    >
</div>