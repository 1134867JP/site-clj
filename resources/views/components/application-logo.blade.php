@props(['class' => 'h-9 w-auto'])

@php
    // Mesmo logo usado no PDF
    $pdfLogo = 'storage/images/clj_logo_left.png';
@endphp

@if (file_exists(public_path($pdfLogo)))
    <img src="{{ asset($pdfLogo) }}"
         alt="CLJ"
         {{ $attributes->merge(['class' => $class]) }}>
@else
    {{-- Fallback: se o arquivo não existir, mostra o SVG padrão do Breeze --}}
    <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 48 48" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M11.395 44.428…"/> {{-- (pode manter o SVG padrão do Breeze aqui) --}}
    </svg>
@endif
