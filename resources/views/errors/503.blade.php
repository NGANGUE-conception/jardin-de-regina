@extends('errors.gabarit')

@section('code', 'Maintenance')
@section('titre', 'Le site fait une petite sieste')

@section('message')
    <p>Nous effectuons une mise à jour pour améliorer le site. Il sera de nouveau disponible dans quelques minutes.</p>
@endsection

@section('actions')
    <button type="button" onclick="location.reload()" class="bouton principal">Réessayer</button>
@endsection
