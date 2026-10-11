@props(['actif' => 'tableau-de-bord'])

<aside class="fixed left-0 top-0 h-full w-[240px] bg-white z-50 flex flex-col justify-between p-4 shadow-sm">
    <div class="flex flex-col gap-6">
        <div class="flex items-center gap-2 px-1 pt-1">
            <img src="{{ asset('images/logo.png') }}" alt="Le Jardin de Régina" class="h-9 w-auto object-contain">
        </div>

        <div class="bg-mint/50 rounded-2xl px-4 py-3 flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-primary shrink-0"></div>
            <div class="flex flex-col min-w-0">
                <span class="font-body text-sm font-semibold text-primary-deep truncate">Établissement actif</span>
                <span class="font-body text-xs text-primary truncate">Alfortville · 12 + 2 places</span>
            </div>
        </div>

        <nav class="flex flex-col gap-1">
            @foreach ([
                ['id' => 'tableau-de-bord', 'label' => 'Tableau de bord', 'icon' => 'grid', 'href' => route('admin'), 'badge' => null],
                ['id' => 'pre-inscriptions', 'label' => 'Pré-inscriptions', 'icon' => 'child', 'href' => route('admin.pre-inscriptions'), 'badge' => '2'],
                ['id' => 'journal', 'label' => 'Journal des visites', 'icon' => 'calendar', 'href' => route('admin.journal-visites'), 'badge' => null],
                ['id' => 'entreprises', 'label' => 'Demandes entreprises', 'icon' => 'building', 'href' => route('admin.entreprises'), 'badge' => '2'],
                ['id' => 'candidatures', 'label' => 'Candidatures', 'icon' => 'badge', 'href' => route('admin.candidatures'), 'badge' => null],
                ['id' => 'messages', 'label' => 'Messages', 'icon' => 'chat', 'href' => route('admin.messages'), 'badge' => 'dot'],
                ['id' => 'parametres', 'label' => 'Paramètres', 'icon' => 'gear', 'href' => route('admin.parametres'), 'badge' => null],
            ] as $item)
            <a href="{{ $item['href'] }}" class="flex items-center justify-between px-4 py-2.5 rounded-2xl transition-all {{ $item['id'] === $actif ? 'bg-primary text-white shadow-sm' : 'text-primary-deep/70 hover:bg-mint/40 hover:text-primary-deep' }}">
                <span class="flex items-center gap-2.5">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        @if ($item['icon'] === 'grid')
                            <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                        @elseif ($item['icon'] === 'child')
                            <circle cx="12" cy="6" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 21v-4a6 6 0 0112 0v4"/>
                        @elseif ($item['icon'] === 'calendar')
                            <rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/>
                        @elseif ($item['icon'] === 'building')
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 9h1m-1 4h1m4-4h1m-1 4h1M9 21v-4h6v4"/>
                        @elseif ($item['icon'] === 'badge')
                            <rect x="4" y="7" width="16" height="14" rx="2"/><path d="M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2"/><circle cx="12" cy="13" r="2"/>
                        @elseif ($item['icon'] === 'chat')
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.5a8.38 8.38 0 01-4.8 7.6 8.5 8.5 0 01-9.3-1.8L3 20l1.9-5.7a8.5 8.5 0 018.4-11 8.38 8.38 0 017.7 6.7z"/>
                        @else
                            <circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06A1.65 1.65 0 004.6 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06A1.65 1.65 0 009 4.6a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                        @endif
                    </svg>
                    <span class="font-body text-sm">{{ $item['label'] }}</span>
                </span>
                @if ($item['badge'] === 'dot')
                    <span class="w-2 h-2 rounded-full bg-coral"></span>
                @elseif ($item['badge'])
                    <span class="px-2 py-0.5 rounded-full bg-coral text-white font-body text-xs font-bold">{{ $item['badge'] }}</span>
                @endif
            </a>
            @endforeach
        </nav>
    </div>

    <div class="bg-cream rounded-2xl p-3 flex items-center gap-2.5">
        <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/></svg>
        </div>
        <div class="flex flex-col min-w-0 flex-1">
            <span class="font-body text-sm font-bold text-primary-deep truncate">{{ auth()->user()?->name ?? '[Direction]' }}</span>
            <span class="font-body text-xs text-primary-deep/50 truncate">Compte administrateur</span>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" title="Déconnexion" class="p-1 text-primary-deep/40 hover:text-coral transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 5v1a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </button>
        </form>
    </div>
</aside>
