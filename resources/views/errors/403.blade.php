@extends('errors.gabarit')

@section('code', 'Erreur 403')
@section('titre', 'Accès réservé')

@section('message')
    <p>Cette page n'est pas accessible avec votre compte. Si vous pensez qu'il s'agit d'une erreur, contactez-nous.</p>
@endsection

@section('actions')
    <a href="{{ url('/') }}" class="bouton principal">Retour à l'accueil</a>
@endsection
