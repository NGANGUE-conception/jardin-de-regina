@extends('errors.gabarit')

@section('code', __('Erreur 500'))
@section('titre', __('Un petit souci technique de notre côté'))

@section('message')
    <p>{{ __('Désolés, une erreur inattendue s\'est produite. Ce n\'est pas de votre faute, et nous allons la corriger.') }}</p>
    <p>{{ __('Si vous étiez en train d\'envoyer un formulaire, réessayez dans quelques minutes, ou contactez-nous directement.') }}</p>
@endsection

@section('actions')
    <a href="{{ url('/') }}" class="bouton principal">{{ __('Retour à l\'accueil') }}</a>
    <button type="button" onclick="location.reload()" class="bouton secondaire">{{ __('Réessayer') }}</button>
@endsection
