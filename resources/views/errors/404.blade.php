@extends('errors.gabarit')

@section('code', 'Erreur 404')
@section('titre', 'Oups, cette page s\'est cachée dans le jardin !')

@section('message')
    <p>La page que vous cherchez n'existe pas ou a été déplacée. Le lien est peut-être incomplet, ou l'adresse contient une petite faute de frappe.</p>
@endsection

@section('actions')
    <a href="{{ url('/') }}" class="bouton principal">Retour à l'accueil</a>
    <a href="{{ url('/pre-inscription') }}" class="bouton secondaire">Pré-inscription</a>
    <a href="{{ url('/contact') }}" class="bouton secondaire">Nous contacter</a>
@endsection
