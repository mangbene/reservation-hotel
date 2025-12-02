<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hôtel Azur - @yield('title', 'Réservation d\'hôtel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-building"></i> Hôtel Azur
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
<body class="d-flex flex-column min-vh-100"></body>

            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                    @if(Auth::user()->isAdmin())
                        <!-- Menu Admin -->
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.chambres.index') }}">
                                    <i class="bi bi-door-open"></i> Chambres
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.reservations.index') }}">
                                    <i class="bi bi-calendar-check"></i> Réservations
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-people"></i> Utilisateurs
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.clients.index') }}">
                                            <i class="bi bi-person"></i> Clients
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.admins.index') }}">
                                            <i class="bi bi-shield-check"></i> Administrateurs
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @else
                        <!-- Menu Client -->
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('client.dashboard') }}">
                                    <i class="bi bi-house-door"></i> Accueil
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('client.reserver') }}">
                                    <i class="bi bi-calendar-plus"></i> Réserver
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('client.reservations') }}">
                                    <i class="bi bi-list-check"></i> Mes réservations
                                </a>
                            </li>
                        </ul>
                    @endif

                    <!-- User menu -->
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <span class="dropdown-item-text">
                                        <small class="text-muted">
                                            {{ Auth::user()->email }}<br>
                                            <span class="badge bg-{{ Auth::user()->isAdmin() ? 'danger' : 'primary' }}">
                                                {{ Auth::user()->isAdmin() ? 'Admin' : 'Client' }}
                                            </span>
                                        </small>
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @else
                    <!-- Menu Non connecté -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Connexion
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Inscription
                            </a>
                        </li>
                    </ul>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Hôtel Azur - Projet scolaire</p>
            <small class="text-muted">Réalisé avec Laravel</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>