<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shkyplace') - Marchés Locaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/shkyplace.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-shky">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-shop-window me-1"></i>Shky<span>place</span>
            </a>
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <i class="bi bi-list fs-3"></i>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav me-auto ms-lg-3">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('markets.*') ? 'active' : '' }}" href="{{ route('markets.index') }}">Marchés</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Produits</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('tontines.*') ? 'active' : '' }}" href="{{ route('tontines.index') }}">Tontines</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('map.*') ? 'active' : '' }}" href="{{ route('map.index') }}">Carte</a></li>
                </ul>
                <ul class="navbar-nav align-items-lg-center gap-1">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                                <i class="bi bi-cart3 fs-5"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->first_name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Mon compte</a></li>
                                @if(auth()->user()->isVendeur())
                                    <li><a class="dropdown-item" href="{{ route('vendor.dashboard') }}"><i class="bi bi-shop me-2"></i>Espace vendeur</a></li>
                                @endif
                                @if(auth()->user()->isLivreur())
                                    <li><a class="dropdown-item" href="{{ route('delivery.dashboard') }}"><i class="bi bi-truck me-2"></i>Espace livreur</a></li>
                                @endif
                                @if(auth()->user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-check me-2"></i>Administration</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-bag-check me-2"></i>Mes commandes</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-light btn-sm fw-semibold px-3" href="{{ route('register') }}">S'inscrire</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @if(session('success') || session('error') || session('warning'))
        <div class="container mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-shky alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-shky alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning alert-shky alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="footer-shky">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="footer-brand mb-3"><i class="bi bi-shop-window me-1"></i>Shkyplace</div>
                    <p>Plateforme de marchés locaux connectant vendeurs, clients et livreurs au Cameroun.</p>
                </div>
                <div class="col-md-2">
                    <h5>Navigation</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('markets.index') }}">Marchés</a></li>
                        <li><a href="{{ route('products.index') }}">Produits</a></li>
                        <li><a href="{{ route('tontines.index') }}">Tontines</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Services</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('delivery.apply') }}">Devenir livreur</a></li>
                        <li><a href="{{ route('register') }}">Devenir vendeur</a></li>
                        <li><a href="{{ route('map.index') }}">Géolocalisation</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Paiements</h5>
                    <p class="small">MTN Money · Orange Money · Blue Money</p>
                    <p class="small">USSD & SMS disponibles sans internet</p>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <p class="text-center small mb-0">&copy; {{ date('Y') }} Shkyplace. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/shkyplace.js') }}"></script>
    @stack('scripts')
</body>
</html>
