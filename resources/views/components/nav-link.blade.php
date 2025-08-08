@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium
               text-gray-900 dark:text-gray-100 dark:border-indigo-400'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium
               text-gray-500 hover:text-gray-700 hover:border-gray-300
               dark:text-gray-300 dark:hover:text-gray-100 dark:hover:border-gray-600';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
