{{--
    Résumé des erreurs en haut d'un formulaire, avec un lien vers chaque champ à corriger.
    Au chargement de la page, il s'affiche au centre de l'écran : après un envoi refusé, la personne voit tout de suite quoi corriger.

    - champs : [nom du champ => id de l'élément] pour les liens (les erreurs sans champ, ex. anti-spam, s'affichent sans lien)
    - alpine : true si le formulaire a une méthode Alpine allerAuChamp(id) (formulaire en plusieurs étapes)
    - fichiers : true si le formulaire contient des fichiers joints (le navigateur ne peut pas les conserver après un renvoi)
--}}
@props(['champs' => [], 'alpine' => false, 'fichiers' => false])

@if ($errors->any())
    @php
        // Une ligne par champ (les erreurs "documents.0", "care_days.1"... sont regroupées sous leur champ).
        $lignes = collect($errors->getMessages())
            ->mapToGroups(fn ($messages, $cle) => [\Illuminate\Support\Str::before($cle, '.') => $messages[0]])
            ->map(fn ($messages, $champ) => ['champ' => $champ, 'message' => $messages->first(), 'id' => $champs[$champ] ?? null])
            ->values();
        $nombreChamps = $lignes->whereNotNull('id')->count();
    @endphp

    <div {{ $attributes->merge(['class' => 'bg-coral/10 border border-coral/30 rounded-2xl p-4 mb-6 flex items-start gap-3 scroll-mt-28']) }}
         role="alert" tabindex="-1"
         x-data x-init="$nextTick(() => { $el.scrollIntoView({ behavior: 'smooth', block: 'center' }); $el.focus({ preventScroll: true }) })">
        <svg class="w-5 h-5 text-coral shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14A2 2 0 004 21h16a2 2 0 001.89-3.14l-8.18-14a2 2 0 00-3.42 0z"/></svg>
        <div class="min-w-0">
            <p class="font-body text-sm font-bold text-primary-deep mb-1">
                @if ($nombreChamps > 1)
                    Merci de corriger les {{ $nombreChamps }} points suivants :
                @elseif ($nombreChamps === 1)
                    Merci de corriger le point suivant :
                @else
                    Votre demande n'a pas pu être envoyée :
                @endif
            </p>
            <ul class="font-body text-sm text-coral flex flex-col gap-0.5">
                @foreach ($lignes as $ligne)
                    <li class="flex items-start gap-1.5">
                        <span aria-hidden="true">•</span>
                        @if ($ligne['id'])
                            <a href="#{{ $ligne['id'] }}"
                               @if ($alpine) @click.prevent="allerAuChamp('{{ $ligne['id'] }}')" @endif
                               class="underline decoration-coral/40 underline-offset-2 hover:decoration-coral">{{ $ligne['message'] }}</a>
                        @else
                            <span>{{ $ligne['message'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
            @if ($fichiers)
                <p class="font-body text-xs text-primary-deep/70 mt-2">Pour des raisons de sécurité, les fichiers ne peuvent pas être conservés : merci de joindre à nouveau vos documents.</p>
            @endif
        </div>
    </div>
@endif
