@extends('errors.gabarit')

@section('code', 'Session expirée')
@section('titre', 'La page est restée ouverte un peu trop longtemps')

@section('message')
    <p>Pour protéger vos informations, chaque formulaire n'est valable qu'un certain temps. <strong>Votre demande n'a pas été envoyée.</strong></p>
    <p>Revenez à la page précédente, actualisez-la, puis envoyez à nouveau le formulaire.</p>
@endsection

@section('actions')
    <button type="button" onclick="history.back()" class="bouton principal">Revenir au formulaire</button>
    <a href="{{ url('/') }}" class="bouton secondaire">Retour à l'accueil</a>
@endsection
