<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Alerte Administrés' }}</title>

    @php
        $timestamp = file_exists(public_path('css/les-styles.css'))
            ? filemtime(public_path('css/les-styles.css'))
            : time(); // fallback si le fichier n'existe pas encore
    @endphp

    {{-- CSS avec version auto --}}
    <link rel="stylesheet" href="{{ asset('css/les-styles.css') }}?v={{ $timestamp }}">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-..." crossorigin="anonymous"></script>

    @php
        $slug = request()->path();
        $bodyClassAuto = $slug === '/' ? 'page-accueil' : 'page-' . str_replace(['/', '.'], '-', $slug);
    @endphp
</head>


<body class="{{ $bodyClass ?? $bodyClassAuto }}">
    <header class="site-header">
        <div class="header-inner">
            <div class="logo">
                <img src="{{ asset('media/web/alerte-administres.webp') }}" alt="Logo Alerte Administrés" />
            </div>

            <button class="menu-toggle" aria-label="Menu">&#9776;</button>

            <nav class="nav-links">
                <div class="nav-inner">
                    <a href="/accueil" class="{{ request()->is('accueil') ? 'current' : '' }}">Accueil</a>
                    <a href="/services" class="{{ request()->is('services') ? 'current' : '' }}">Services</a>
                    <a href="/administres" class="{{ request()->is('administres') ? 'current' : '' }}">Administrés</a>
                    <a href="/infomail" class="{{ request()->is('infomail') ? 'current' : '' }}">Infomail</a>
                    <a href="/alerte" class="{{ request()->is('alerte') ? 'current' : '' }}">Alerte</a>
                    <a href="/contact" class="{{ request()->is('contact') ? 'current' : '' }}">Contact</a>

                    @auth
                        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'current' : '' }}">Tableau de bord</a>
                    @endauth
                </div>
            </nav>


        </div>
    </header>
