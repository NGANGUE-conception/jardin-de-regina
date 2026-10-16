@extends('errors.gabarit')

@section('code', __('Erreur 404'))
@section('titre', __('Oups, cette page s\'est cachée dans le jardin !'))

@section('message')
    <p>{{ __('La page que vous cherchez n\'existe pas ou a été déplacée. Le lien est peut-être incomplet, ou l\'adresse contient une petite faute de frappe.') }}</p>
@endsection

@section('actions')
    <a href="{{ route_langue('accueil') }}" class="bouton principal">{{ __('Retour à l\'accueil') }}</a>
    <a href="{{ route_langue('pre-inscription') }}" class="bouton secondaire">{{ __('Pré-inscription') }}</a>
    <a href="{{ route_langue('contact') }}" class="bouton secondaire">{{ __('Nous contacter') }}</a>
@endsection
