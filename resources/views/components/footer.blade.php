
  <footer class="w-full bg-primary-deep text-cream relative overflow-hidden mt-24">
      <div class="w-full overflow-hidden leading-none">
          <svg class="block w-full h-12 md:h-16 text-cream fill-current" preserveAspectRatio="none" viewBox="0 0 1200 120">
              <path d="M0,0 C150,90 350,-40 500,60 C650,160 900,10 1200,40 L1200,0 L0,0 Z"></path>
          </svg>
      </div>

      <div class="max-w-[1240px] mx-auto px-6 pt-10 pb-16">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

              <div class="flex flex-col gap-4">
                  <span class="font-display text-2xl">{{ __('Le Jardin de Régina') }}</span>
                  <p class="font-body text-sm text-cream/90">
                      {{ __('Micro-crèche écoresponsable à taille humaine. Un cocon de nature et de bienveillance au cœur d\'Alfortville.') }}
                  </p>
                  <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 w-fit">
                      <span class="font-body text-xs uppercase tracking-widest text-teal-clair">{{ __('Micro-crèche écoresponsable · Réseau :gestionnaire', ['gestionnaire' => $etab->gestionnaire()]) }}</span>
                  </div>
              </div>

              <div class="flex flex-col gap-4">
                  <span class="font-display text-lg">{{ __('Coordonnées & horaires') }}</span>
                  <div class="flex flex-col gap-2 text-sm text-cream/90 font-body">
                      <span>{{ $etab->adresse() }}</span>
                      <a href="tel:{{ $etab->telephoneLien() }}" class="hover:text-teal-clair transition-colors">{{ $etab->telephone() }}</a>
                      <a href="mailto:{{ $etab->email() }}" class="hover:text-teal-clair transition-colors
  break-all">{{ $etab->email() }}</a>
                      <span>{{ __('Lundi – vendredi, :horaires', ['horaires' => $etab->horaires()]) }}</span>
                  </div>
              </div>

              <div class="flex flex-col gap-4">
                  <span class="font-display text-lg">{{ __('Liens rapides') }}</span>
                  <div class="flex flex-col gap-2 text-sm font-body text-cream/90">
                      <a href="/notre-projet" class="hover:text-cream transition-colors">{{ __('Notre projet pédagogique') }}</a>
                      <a href="/fonctionnement" class="hover:text-cream transition-colors">{{ __('Fonctionnement & rythme') }}</a>
                      <a href="/tarifs" class="hover:text-cream transition-colors">{{ __('Grille tarifaire & aides CAF') }}</a>
                      <a href="/berceaux-entreprise" class="hover:text-cream transition-colors">{{ __('Berceaux entreprise') }}</a>
                      <a href="/recrutement" class="hover:text-cream transition-colors">{{ __('Espace recrutement') }}</a>
                      <a href="/pre-inscription" class="font-bold text-coral-clair hover:text-cream transition-colors">{{ __('Demande de pré-inscription') }}</a>
                  </div>
              </div>

              <div class="flex flex-col gap-4">
                  <span class="font-display text-lg">{{ __('Informations légales') }}</span>
                  <div class="flex flex-col gap-2 text-sm font-body text-cream/90">
                      <a href="/mentions-legales" class="hover:text-cream transition-colors">{{ __('Mentions légales') }}</a>
                      <a href="/politique-de-confidentialite" class="hover:text-cream transition-colors">{{ __('Politique de confidentialité') }}</a>
                      <a href="/gestion-des-cookies" class="hover:text-cream transition-colors">{{ __('Gestion des cookies') }}</a>
                      <a href="/plan-du-site" class="hover:text-cream transition-colors">{{ __('Plan du site') }}</a>
                  </div>
                  <div class="text-xs text-cream/75 mt-2">{{ __('Agrément PMI & soutien CAF Val-de-Marne') }}</div>
              </div>
          </div>

          <div class="mt-10 pt-6 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-cream/70
  font-body">
              <p>{{ __('© :y Le Jardin de Régina. Tous droits réservés.', ['y' => date('Y')]) }}</p>
              <span class="italic">{{ __('Conçu pour le bien-être et l\'éveil de vos tout-petits') }}</span>
          </div>
      </div>
  </footer>