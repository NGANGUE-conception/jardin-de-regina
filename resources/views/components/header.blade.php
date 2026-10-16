<header x-data="{ mobileOpen: false }" class="fixed top-0 left-0 right-0 w-full z-50 bg-cream/95 backdrop-blur-md shadow-sm">
    <div class="h-20 max-w-[1240px] mx-auto flex items-center gap-4 px-6">
        <a href="{{ route_langue('accueil') }}" class="flex items-center gap-2 -ml-6">
            <img src="{{ asset('images/logo.webp') }}" width="375" height="192" alt="{{ __('Le Jardin de Régina') }}" class="h-14 w-auto object-contain">
        </a>

        <nav class="hidden xl:flex items-center gap-2 ml-auto">
            <a href="{{ route_langue('accueil') }}" class="font-body font-bold text-sm px-4 py-2 rounded-full transition-all duration-200 whitespace-nowrap {{ request()->routeIs('accueil', 'en.accueil') ? 'bg-primary text-white shadow-md' : 'bg-mint/60 text-primary-deep hover:bg-mint hover:-translate-y-0.5 hover:shadow-md' }}">{{ __('Accueil') }}</a>
            <a href="{{ route_langue('notre-projet') }}" class="font-body font-bold text-sm px-4 py-2 rounded-full transition-all duration-200 whitespace-nowrap {{ request()->routeIs('notre-projet', 'en.notre-projet') ? 'bg-primary text-white shadow-md' : 'bg-mint/60 text-primary-deep hover:bg-mint hover:-translate-y-0.5 hover:shadow-md' }}">{{ __('Notre projet') }}</a>
            <a href="{{ route_langue('fonctionnement') }}" class="font-body font-bold text-sm px-4 py-2 rounded-full transition-all duration-200 whitespace-nowrap {{ request()->routeIs('fonctionnement', 'en.fonctionnement') ? 'bg-primary text-white shadow-md' : 'bg-mint/60 text-primary-deep hover:bg-mint hover:-translate-y-0.5 hover:shadow-md' }}">{{ __('Fonctionnement') }}</a>
            <a href="{{ route_langue('tarifs') }}" class="font-body font-bold text-sm px-4 py-2 rounded-full transition-all duration-200 whitespace-nowrap {{ request()->routeIs('tarifs', 'en.tarifs') ? 'bg-primary text-white shadow-md' : 'bg-mint/60 text-primary-deep hover:bg-mint hover:-translate-y-0.5 hover:shadow-md' }}">{{ __('Tarifs') }}</a>
            <a href="{{ route_langue('berceaux-entreprise') }}" class="font-body font-bold text-sm px-4 py-2 rounded-full transition-all duration-200 whitespace-nowrap {{ request()->routeIs('berceaux-entreprise', 'en.berceaux-entreprise') ? 'bg-primary text-white shadow-md' : 'bg-mint/60 text-primary-deep hover:bg-mint hover:-translate-y-0.5 hover:shadow-md' }}">{{ __('Berceaux entreprise') }}</a>
            <a href="{{ route_langue('recrutement') }}" class="font-body font-bold text-sm px-4 py-2 rounded-full transition-all duration-200 whitespace-nowrap {{ request()->routeIs('recrutement', 'en.recrutement') ? 'bg-primary text-white shadow-md' : 'bg-mint/60 text-primary-deep hover:bg-mint hover:-translate-y-0.5 hover:shadow-md' }}">{{ __('Recrutement') }}</a>
            <a href="{{ route_langue('contact') }}" class="font-body font-bold text-sm px-4 py-2 rounded-full transition-all duration-200 whitespace-nowrap {{ request()->routeIs('contact', 'en.contact') ? 'bg-primary text-white shadow-md' : 'bg-mint/60 text-primary-deep hover:bg-mint hover:-translate-y-0.5 hover:shadow-md' }}">{{ __('Contact') }}</a>
        </nav>

        <div class="flex items-center gap-3 sm:gap-4 ml-auto xl:ml-0">
            {{-- Choix de la langue : chaque langue est écrite dans sa propre langue (usage international) --}}
            <nav aria-label="{{ __('Langue du site') }}" class="flex items-center gap-0.5 rounded-full bg-mint/60 p-1 font-body text-xs font-extrabold">
                @foreach (['fr' => 'Français', 'en' => 'English'] as $code => $nomLangue)
                    <a href="{{ App\Support\LangueSite::equivalent($code) }}" hreflang="{{ $code }}" lang="{{ $code }}" rel="alternate" title="{{ $nomLangue }}"
                       @if (app()->getLocale() === $code) aria-current="true" @endif
                       class="min-w-9 h-9 px-2 rounded-full flex items-center justify-center transition-colors {{ app()->getLocale() === $code ? 'bg-primary text-white shadow-sm' : 'text-primary-deep hover:bg-mint' }}">
                        <span class="sr-only">{{ $nomLangue }}</span>
                        <span aria-hidden="true">{{ strtoupper($code) }}</span>
                    </a>
                @endforeach
            </nav>

            <a href="{{ route_langue('pre-inscription') }}" class="hidden sm:flex h-[52px] px-6 rounded-full bg-coral text-white font-body font-bold text-sm items-center justify-center shadow-lg hover:opacity-90 transition whitespace-nowrap">
                {{ __('Pré-inscription') }}
            </a>

            <button @click="mobileOpen = !mobileOpen" class="xl:hidden w-11 h-11 flex items-center justify-center rounded-full bg-mint text-primary-deep" aria-label="{{ __('Ouvrir le menu') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="mobileOpen" class="xl:hidden bg-cream border-t border-primary-deep/10 px-6 py-4 flex flex-col gap-2">
        <a href="{{ route_langue('accueil') }}" class="font-body font-bold py-2 text-primary-deep">{{ __('Accueil') }}</a>
        <a href="{{ route_langue('notre-projet') }}" class="font-body font-bold py-2 text-primary-deep">{{ __('Notre projet') }}</a>
        <a href="{{ route_langue('fonctionnement') }}" class="font-body font-bold py-2 text-primary-deep">{{ __('Fonctionnement') }}</a>
        <a href="{{ route_langue('tarifs') }}" class="font-body font-bold py-2 text-primary-deep">{{ __('Tarifs') }}</a>
        <a href="{{ route_langue('berceaux-entreprise') }}" class="font-body font-bold py-2 text-primary-deep">{{ __('Berceaux entreprise') }}</a>
        <a href="{{ route_langue('recrutement') }}" class="font-body font-bold py-2 text-primary-deep">{{ __('Recrutement') }}</a>
        <a href="{{ route_langue('contact') }}" class="font-body font-bold py-2 text-primary-deep">{{ __('Contact') }}</a>
        <a href="{{ route_langue('pre-inscription') }}" class="mt-2 h-[52px] px-6 rounded-full bg-coral text-white font-body font-bold text-sm flex items-center justify-center">{{ __('Pré-inscription') }}</a>
    </div>
</header>

<div class="h-20"></div>
