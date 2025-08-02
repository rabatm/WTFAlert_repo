@include('header', ['title' => 'Accueil'])

<main>
    {{-- Bloc principal avec contenu + visuel --}}
    <section class="hero">
        <div class="contenu">
            <h1>
                <strong>Communiquez rapidement et efficacement avec vos concitoyens via des alertes SMS avec </strong>
                <strong>Alerte Administrés</strong>.
            </h1>
            <p>
                Cet outil permet aux dirigeants locaux de diffuser des informations essentielles pour
                <strong>avertir instantanément la population de la commune</strong> et les maintenir informés en cas de situations graves imminentes.
            </p>
            <p>
                Que ce soit pour des alertes météorologiques, des inondations, des vagues de chaleur, des pannes d’eau/électricité, des cambriolages, des travaux, des accidents, des routes fermées, Alerte Administrés met à votre disposition un
                <strong>ensemble de fonctionnalités pour renforcer la sécurité et la communication communale</strong>.
            </p>
            <p>
                Bénéficiez de fonctionnalités comprises telles que la <strong>diffusion de newsletters</strong> aux personnes intéressées ou encore l’<strong>envoi d’un lien vers l’itinéraire GPS par SMS</strong> d’une propriété aux forces de l’ordre, pompiers, livreurs ou autre.
            </p>

            @if(session('error'))
                <p class="error">{{ session('error') }}</p>
            @endif
        </div>

        <div class="illustration">
            <img src="{{ asset('media/web/illustration-mairie.webp') }}" alt="Illustration mairie">
        </div>
    </section>

    {{-- Bloc de connexion séparé mais même largeur --}}
    <section class="login-wrapper">
        <form action="{{ route('login') }}" method="POST" class="form-login">
            @csrf
            <div class="login-fields">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
            </div>
            <div class="login-button">
                <button type="submit">Connexion</button>
            </div>
        </form>
    </section>
</main>

@include('footer')


