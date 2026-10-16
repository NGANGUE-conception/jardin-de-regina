@extends('errors.gabarit')

@section('code', __('Session expirée'))
@section('titre', __('La page est restée ouverte un peu trop longtemps'))

@section('message')
    <p>{{ __('Pour protéger vos informations, chaque formulaire n\'est valable qu\'un certain temps.') }} <strong>{{ __('Votre demande n\'a pas été envoyée.') }}</strong></p>
    <p>{{ __('Revenez à la page précédente, actualisez-la, puis envoyez à nouveau le formulaire.') }}</p>
@endsection

@section('actions')
    <button type="button" onclick="history.back()" class="bouton principal">{{ __('Revenir au formulaire') }}</button>
    <a href="{{ route_langue('accueil') }}" class="bouton secondaire">{{ __('Retour à l\'accueil') }}</a>
@endsection
