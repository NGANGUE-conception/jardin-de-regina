{{--
    Règles du mot de passe, cochées en direct pendant la saisie (voir App\Support\MotDePasse).
    À placer dans un élément Alpine qui contient les variables "mdp" (nouveau mot de passe) et "confirmation".
--}}
<ul {{ $attributes->merge(['class' => 'grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1.5 font-body text-xs']) }}>
    @foreach ([
        ['test' => 'mdp.length >= 12', 'texte' => 'Au moins 12 caractères'],
        ['test' => '/[A-Z]/.test(mdp) && /[a-z]/.test(mdp)', 'texte' => 'Une majuscule et une minuscule'],
        ['test' => '/[0-9]/.test(mdp)', 'texte' => 'Au moins un chiffre'],
        ['test' => '/[^A-Za-z0-9]/.test(mdp)', 'texte' => 'Au moins un symbole (! ? @ # % …)'],
        ['test' => 'mdp.length > 0 && mdp === confirmation', 'texte' => 'Confirmation identique'],
    ] as $regle)
        <li class="flex items-center gap-1.5 transition-colors" :class="{{ $regle['test'] }} ? 'text-primary font-bold' : 'text-primary-deep/50'">
            <svg x-show="{{ $regle['test'] }}" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <svg x-show="!({{ $regle['test'] }})" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="8"/></svg>
            {{ $regle['texte'] }}
        </li>
    @endforeach
</ul>
