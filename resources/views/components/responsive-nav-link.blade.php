@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-500 text-start text-base font-medium
               text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700
               dark:text-indigo-200 dark:bg-slate-800 dark:border-indigo-400 dark:focus:text-white dark:focus:bg-slate-700'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium
               text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none
               dark:text-gray-300 dark:hover:text-white dark:hover:bg-slate-800 dark:hover:border-slate-600';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
