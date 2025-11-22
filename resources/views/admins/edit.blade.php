@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier l'administrateur #{{ $admin->id }}</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admins.update', $admin->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control" value="{{ $admin->nom }}">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $admin->email }}">
        </div>

        <div class="mb-3">
            <label>Mot de passe (laisser vide si inchangé)</label>
            <input type="password" name="mot_de_passe" class="form-control">
        </div>

        <div class="mb-3">
            <label>Téléphone</label>
            <input type="text" name="telephone" class="form-control" value="{{ $admin->telephone }}">
        </div>

        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admins.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
