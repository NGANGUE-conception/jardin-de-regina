<header x-data="{ mobileOpen: false }" class="fixed top-0 left-0 right-0 w-full z-50 bg-cream/95 backdrop-blur-md shadow-sm">
    <div class="h-20 max-w-[1240px] mx-auto flex items-center gap-4 px-6">
        <a href="/" class="flex items-center gap-2 -ml-6">
            <img src="{{ asset('images/logo.png') }}" alt="Le Jardin de Régina" class="h-14 w-auto object-contain">
        </a>

        <nav class="hidden xl:flex items-center gap-2 ml-auto">
            <a href="/" class="font-body font-bold text-sm px-4 py-2 rounded-full transition-all duration-200 whitespace-nowrap {{ request()->is('/') ? 'bg-primary text-white shadow-md' : 'bg-mint/60 text-primary-deep hover:bg-mint hover:-translate-y-0.5 hover:shadow-md' }}">Accueil</a>
            <a href="/notre-projet" class="font-body font-bold text-sm px-4 py-2 rounded-full transition-all duration-200 whitespace-nowrap {{ request()->is('notre-projet') ? 'bg-primary text-white shadow-md' : 'bg-mint/60 text-primary-deep hover:bg-mint hover:-translate-y-0.5 hover:shadow-md' }}">Notre projet</a>
            <a href="/fonctionnement" class="font-body font-bold text-sm px-4 py-2 rounded-full transition-all duration-200 whitespace-nowrap {{ request()->is('fonctionnement') ? 'bg-primary text-white shadow-md' : 'bg-mint/60 text-primary-deep hover:bg-mint hover:-translate-y-0.5 hover:shadow-md' }}">Fonctionnement</a>
            <a href="/tarifs" class="font-body font-bold text-sm px-4 py-2 rounded-full transition-all duration-200 whitespace-nowrap {{ request()->is('tarifs') ? 'bg-primary text-white shadow-md' : 'bg-mint/60 text-primary-deep hover:bg-mint hover:-translate-y-0.5 hover:shadow-md' }}">Tarifs</a>
            <a href="/berceaux-entreprise" class="font-body font-bold text-sm px-4 py-2 rounded-full transition-all duration-200 whitespace-nowrap {{ request()->is('berceaux-entreprise') ? 'bg-primary text-white shadow-md' : 'bg-mint/60 text-primary-deep hover:bg-mint hover:-translate-y-0.5 hover:shadow-md' }}">Berceaux entreprise</a>
            <a href="/recrutement" class="font-body font-bold text-sm px-4 py-2 rounded-full transition-all duration-200 whitespace-nowrap {{ request()->is('recrutement') ? 'bg-primary text-white shadow-md' : 'bg-mint/60 text-primary-deep hover:bg-mint hover:-translate-y-0.5 hover:shadow-md' }}">Recrutement</a>
            <a href="/contact" class="font-body font-bold text-sm px-4 py-2 rounded-full transition-all duration-200 whitespace-nowrap {{ request()->is('contact') ? 'bg-primary text-white shadow-md' : 'bg-mint/60 text-primary-deep hover:bg-mint hover:-translate-y-0.5 hover:shadow-md' }}">Contact</a>
        </nav>

        <div class="flex items-center gap-4">
            <a href="/pre-inscription" class="hidden sm:flex h-[52px] px-6 rounded-full bg-coral text-white font-body font-bold text-sm items-center justify-center shadow-lg hover:opacity-90 transition whitespace-nowrap">
                Pré-inscription
            </a>

            <button @click="mobileOpen = !mobileOpen" class="xl:hidden w-11 h-11 flex items-center justify-center rounded-full bg-mint text-primary-deep" aria-label="Ouvrir le menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="mobileOpen" class="xl:hidden bg-cream border-t border-primary-deep/10 px-6 py-4 flex flex-col gap-2">
        <a href="/" class="font-body font-bold py-2 text-primary-deep">Accueil</a>
        <a href="/notre-projet" class="font-body font-bold py-2 text-primary-deep">Notre projet</a>
        <a href="/fonctionnement" class="font-body font-bold py-2 text-primary-deep">Fonctionnement</a>
        <a href="/tarifs" class="font-body font-bold py-2 text-primary-deep">Tarifs</a>
        <a href="/berceaux-entreprise" class="font-body font-bold py-2 text-primary-deep">Berceaux entreprise</a>
        <a href="/recrutement" class="font-body font-bold py-2 text-primary-deep">Recrutement</a>
        <a href="/contact" class="font-body font-bold py-2 text-primary-deep">Contact</a>
        <a href="/pre-inscription" class="mt-2 h-[52px] px-6 rounded-full bg-coral text-white font-body font-bold text-sm flex items-center justify-center">Pré-inscription</a>
    </div>
</header>

<div class="h-20"></div>
