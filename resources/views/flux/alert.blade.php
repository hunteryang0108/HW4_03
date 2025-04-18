@props([
    'variant' => 'primary',
    'dismissable' => false,
])

@php
    $variantClasses = [
        'primary' => 'bg-blue-50 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200',
        'success' => 'bg-green-50 text-green-800 dark:bg-green-900/50 dark:text-green-200',
        'warning' => 'bg-yellow-50 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200',
        'danger' => 'bg-red-50 text-red-800 dark:bg-red-900/50 dark:text-red-200',
        'info' => 'bg-indigo-50 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-200',
    ][$variant] ?? 'bg-blue-50 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200';
@endphp

<div {{ $attributes->merge(['class' => "p-4 rounded-md $variantClasses"]) }} role="alert">
    {{ $slot }}
</div>