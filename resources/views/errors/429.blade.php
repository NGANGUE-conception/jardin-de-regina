@extends('errors.gabarit')

@section('code', 'Trop de tentatives')
@section('titre', 'Merci de patienter un instant')

@section('message')
    <p>Nous avons reçu beaucoup de demandes depuis votre connexion en peu de temps. Par sécurité, patientez quelques minutes avant de réessayer.</p>
@endsection

@section('actions')
    <button type="button" onclick="history.back()" class="bouton principal">Revenir en arrière</button>
    <a href="{{ url('/') }}" class="bouton secondaire">Retour à l'accueil</a>
@endsection
