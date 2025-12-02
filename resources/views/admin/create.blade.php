@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ajouter un administrateur</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admins.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom') }}">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" class="form-control">
        </div>

        <div class="mb-3">
            <label>Téléphone</label>
            <input type="text" name="telephone" class="form-control" value="{{ old('telephone') }}">
        </div>

        <button class="btn btn-primary">Créer</button>
        <a href="{{ route('admins.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
