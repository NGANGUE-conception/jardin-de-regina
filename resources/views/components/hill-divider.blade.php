@props(['color' => 'mint'])

@php
$colorClass = match ($color) {
    'mint' => 'text-mint',
    'peach' => 'text-peach',
    'cream' => 'text-cream',
    'white' => 'text-white',
    default => 'text-mint',
};
@endphp

<div class="w-full overflow-hidden leading-none -mb-1">
    <svg class="block w-full h-14 md:h-20 {{ $colorClass }} fill-current" preserveAspectRatio="none" viewBox="0 0 1440 120">
        <path d="M0,40 C320,110 580,10 880,70 C1140,120 1340,30 1440,50 L1440,120 L0,120 Z"></path>
    </svg>
</div>
