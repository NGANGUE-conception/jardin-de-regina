@props(['actif' => 'rendez-vous'])

@php
    $onglets = [
        'rendez-vous' => [
            'label' => 'Rendez-vous',
            'href' => route('admin.journal-visites'),
            'icone' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/>',
        ],
        'site' => [
            'label' => 'Visites du site',
            'href' => route('admin.visites-site'),
            'icone' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 010 18M12 3a14 14 0 000 18"/>',
        ],
    ];
@endphp

<nav class="inline-flex items-center gap-1 bg-white rounded-full p-1 shadow-sm mb-6" aria-label="Journal des visites">
    @foreach ($onglets as $id => $onglet)
        <a href="{{ $onglet['href'] }}"
           @if ($id === $actif) aria-current="page" @endif
           class="flex items-center gap-2 px-5 py-2 rounded-full font-body text-sm font-bold transition-all {{ $id === $actif ? 'bg-primary text-white shadow-sm' : 'text-primary-deep/80 hover:bg-mint/40 hover:text-primary-deep' }}">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">{!! $onglet['icone'] !!}</svg>
            {{ $onglet['label'] }}
        </a>
    @endforeach
</nav>
