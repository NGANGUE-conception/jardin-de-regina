{{--
    Message d'erreur affiché sous un champ de formulaire, après validation par le serveur.
    - champ : nom du champ (attribut name), ex. "p1_email" ou "documents" (les erreurs "documents.0", "documents.1"... sont aussi prises en compte)
    - id    : identifiant du champ ; le message reçoit l'id "{id}-erreur", relié au champ par aria-describedby (lecteurs d'écran)
--}}
@props(['champ', 'id'])

@php($message = $errors->first($champ) ?: $errors->first($champ.'.*'))

@if ($message)
    <p id="{{ $id }}-erreur" {{ $attributes->merge(['class' => 'flex items-start gap-1.5 font-body text-sm font-semibold text-coral']) }}>
        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 8v4.5M12 16h.01"/></svg>
        <span>{{ $message }}</span>
    </p>
@endif
