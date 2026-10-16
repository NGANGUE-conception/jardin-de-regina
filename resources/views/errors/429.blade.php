@extends('errors.gabarit')

@section('code', __('Trop de tentatives'))
@section('titre', __('Merci de patienter un instant'))

@section('message')
    <p>{{ __('Nous avons reçu beaucoup de demandes depuis votre connexion en peu de temps. Par sécurité, patientez quelques minutes avant de réessayer.') }}</p>
@endsection

@section('actions')
    <button type="button" onclick="history.back()" class="bouton principal">{{ __('Revenir en arrière') }}</button>
    <a href="{{ route_langue('accueil') }}" class="bouton secondaire">{{ __('Retour à l\'accueil') }}</a>
@endsection
