@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Connexion</h1>
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">
        <input type="password" name="password" placeholder="Mot de passe">
        <button type="submit">Se connecter</button>
    </form>
</div>
@endsection
