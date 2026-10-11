@props(['shape' => 'rond', 'class' => ''])

@php
$shapes = [
    'etoile' => '<svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.2 6.8H21l-5.6 4.1 2.2 6.8L12 15.6l-5.6 4.1 2.2-6.8L3 8.8h6.8z"/></svg>',
    'fleur' => '<svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2c-2 2-3 4-3 6a3 3 0 006 0c0-2-1-4-3-6z"/><circle cx="12" cy="12" r="2.4"/></svg>',
    'coeur' => '<svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21s-7-4.35-9.5-8.5C.5 8.5 2.5 5 6 5c2 0 3.5 1.5 4 2.5.5-1 2-2.5 4-2.5 3.5 0 5.5 3.5 3.5 7.5C19 16.65 12 21 12 21z"/></svg>',
    'squiggle' => '<svg class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M2 12c2.5-6 5-6 7.5 0s5 6 7.5 0 5-6 5 0"/></svg>',
];
@endphp

@if ($shape === 'rond')
<div {{ $attributes->merge(['class' => 'hidden md:block absolute rounded-full pointer-events-none ' . $class]) }}></div>
@else
<div {{ $attributes->merge(['class' => 'hidden md:block absolute pointer-events-none ' . $class]) }}>{!! $shapes[$shape] ?? '' !!}</div>
@endif
