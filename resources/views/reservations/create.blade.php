@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Créer une réservation</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('reservations.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Client</label>
            <select name="client_id" class="form-control">
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Chambre</label>
            <select name="chambre_id" class="form-control">
                @foreach($chambres as $chambre)
                    <option value="{{ $chambre->id }}">{{ $chambre->numero }} ({{ $chambre->type }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Date d'arrivée</label>
            <input type="date" name="date_arrivee" class="form-control" value="{{ old('date_arrivee') }}">
        </div>

        <div class="mb-3">
            <label>Date de départ</label>
            <input type="date" name="date_depart" class="form-control" value="{{ old('date_depart') }}">
        </div>

        <button class="btn btn-primary">Créer</button>
        <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection