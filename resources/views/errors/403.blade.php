@extends('errors.gabarit')

@section('code', __('Erreur 403'))
@section('titre', __('Accès réservé'))

@section('message')
    <p>{{ __('Cette page n\'est pas accessible avec votre compte. Si vous pensez qu\'il s\'agit d\'une erreur, contactez-nous.') }}</p>
@endsection

@section('actions')
    <a href="{{ url('/') }}" class="bouton principal">{{ __('Retour à l\'accueil') }}</a>
@endsection
