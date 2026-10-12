@props(['recherchePlaceholder' => 'Rechercher...', 'placesPourvues' => null])

@php
$placesPourvues ??= \App\Models\PreRegistration::where('status', 'acceptee')->count();
@endphp

<header class="fixed top-0 left-[240px] right-0 h-16 bg-cream/90 backdrop-blur-xl z-40 px-8 flex items-center justify-between shadow-sm">
    <div class="relative w-full max-w-md flex items-center">
        <svg class="w-5 h-5 absolute left-4 text-primary-deep/40 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-4-4"/></svg>
        @if (isset($recherche))
            <input type="search" x-model="{{ $recherche }}" placeholder="{{ $recherchePlaceholder }}" class="w-full h-10 pl-11 pr-4 bg-white text-primary-deep placeholder:text-primary-deep/40 rounded-full font-body text-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all">
        @else
            <input type="search" placeholder="{{ $recherchePlaceholder }}" disabled class="w-full h-10 pl-11 pr-4 bg-white/60 text-primary-deep/40 placeholder:text-primary-deep/30 rounded-full font-body text-sm cursor-not-allowed">
        @endif
    </div>
    <div class="flex items-center gap-4">
        <span class="hidden md:flex items-center gap-2 bg-white px-4 py-1.5 rounded-full font-body text-sm font-semibold text-primary-deep whitespace-nowrap">
            <svg class="w-4 h-4 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
            {{ $placesPourvues }}/14 places pourvues · ouverture août 2026
        </span>
        @php($totalATraiter = \App\Support\CompteursAdmin::total())
        <a href="{{ route('admin') }}" title="{{ $totalATraiter ? $totalATraiter.' élément(s) à traiter' : 'Rien de nouveau à traiter' }}" class="relative p-2 rounded-full text-primary-deep/75 hover:bg-white transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.66V5a2 2 0 10-4 0v.34A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            @if ($totalATraiter > 0)
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-coral rounded-full ring-2 ring-cream"></span>
            @endif
        </a>
    </div>
</header>
