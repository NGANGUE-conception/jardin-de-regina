@extends('errors.gabarit')

@section('code', 'Erreur 500')
@section('titre', 'Un petit souci technique de notre côté')

@section('message')
    <p>Désolés, une erreur inattendue s'est produite. Ce n'est pas de votre faute, et nous allons la corriger.</p>
    <p>Si vous étiez en train d'envoyer un formulaire, réessayez dans quelques minutes, ou contactez-nous directement.</p>
@endsection

@section('actions')
    <a href="{{ url('/') }}" class="bouton principal">Retour à l'accueil</a>
    <button type="button" onclick="location.reload()" class="bouton secondaire">Réessayer</button>
@endsection
