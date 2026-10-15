{{--
    Protection anti-spam (voir App\Http\Middleware\ProtectionAntiSpam), à placer dans chaque formulaire public.
    - Champ piège : déplacé hors de l'écran (pas "display:none", que certains robots savent repérer),
      ignoré au clavier et par les lecteurs d'écran. Un humain ne le remplit jamais.
    - Heure d'affichage du formulaire, chiffrée pour ne pas pouvoir être falsifiée.
--}}
@php($idPiege = 'jr-piege-'.\Illuminate\Support\Str::random(6))
<div aria-hidden="true" style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;">
    <label for="{{ $idPiege }}">{{ __('Laissez ce champ vide') }}</label>
    <input type="text" id="{{ $idPiege }}" name="{{ \App\Http\Middleware\ProtectionAntiSpam::CHAMP_PIEGE }}" value="" tabindex="-1" autocomplete="off" data-lpignore="true" data-1p-ignore data-bwignore>
</div>
<input type="hidden" name="{{ \App\Http\Middleware\ProtectionAntiSpam::CHAMP_HEURE }}" value="{{ encrypt(now()->getTimestamp()) }}">
