@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Bienvenue {{ Auth::user()->name }}</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Déconnexion</button>
    </form>
</div>
@endsection
