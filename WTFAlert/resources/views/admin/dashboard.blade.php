<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - WTFAlert Admin</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/simple-styles.css') }}">
</head>

<body>
    <div class="wrap_all">
        <header id="site-header" class="header-footer-group">
            <div class="header-inner section-inner">
                <div class="header-titles-wrapper">
                    <div class="header-titles">
                        <div class="site-logo faux-heading">
                            <a href="/" class="custom-logo-link" rel="home" aria-current="page">
                                <img width="225" height="124" src="{{ asset('images/cropped-logo.jpg') }}" class="custom-logo"
                                    alt="Alerte Administrés" decoding="async">
                            </a>
                            <span class="screen-reader-text">Alerte Administrés</span>
                        </div>
                    </div>
                    <div class="texte_header">Informez les habitants<br>immédiatement et simplement</div>
                    <div class="pictos_header">
                        <a href="/details">
                            <img class="size-full wp-image-3477" src="{{ asset('images/pictos.jpg') }}" alt="" width="134"
                                height="62">
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <div class="container_front_page">
            <div id="principal_accueil">
                <div id="texte_principal_accueil" class="bloc_blanc_arrondi">
                    <h1><strong>Communiquez rapidement et efficacement avec vos concitoyens via des alertes SMS avec
                        </strong><strong>Alerte Administrés</strong>.</h1>
                    <p>Cet outil permet aux dirigeants locaux de diffuser des informations essentielles pour
                        <strong>avertir instantanément la population de la commune</strong> et les maintenir informés en cas
                        de situations graves imminentes.</p>
                    <p>Que ce soit pour des alertes météorologiques, des inondations, des vagues de chaleur, des pannes
                        d’eau/électricité, des cambriolages, des travaux, des accidents, des routes fermées, Alerte
                        Administrés met à votre disposition un <strong>ensemble de fonctionnalités pour renforcer la
                            sécurité et la communication communale</strong>.</p>
                    <p>Bénéficiez de fonctionnalités comprises telles que la <strong>diffusion de newsletters</strong> aux
                        personnes intéressées ou encore l’<strong>envoi d’un lien vers l’itinéraire GPS par SMS</strong>
                        d’une propriété aux forces de l’ordre, pompiers, livreurs ou autre.</p>
                </div>
                <div id="image_principal_accueil">
                    <img width="295" height="214" src="{{ asset('images/alerts-sms.png') }}"
                        class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async"
                        fetchpriority="high">
                </div>
            </div>
            <div class="tribloc">
                <div class="untier bloc_blanc_arrondi">
                    <h2>Infolettre</h2>
                    Recevez les dernières infos de notre outil et nos utilisateurs
                </div>
                <div class="untier bloc_blanc_arrondi">
                    <h2>Actualités</h2>
                    <p>
                        <img decoding="async" class="alignnone size-medium wp-image-3472"
                            src="{{ asset('images/Mas_du_clos-300x134.webp') }}" alt="" width="300" height="134">
                    </p>
                    <p>Nouveauté, envoyez l’itinéraire GPS en un clic sur le téléphone des soldats du feu ou même d’un
                        livreur si besoin</p>
                </div>
                <div class="untier bloc_blanc_arrondi">
                    <h2>Mon espace</h2>
                    <form name="form_custom_alert" id="form_custom_alert" action="{{ route('login') }}" method="post">
                        @csrf
                        <p class="login-username">
                            <label for="user_login">Utilisateur</label>
                            <input type="text" name="log" id="user_login" autocomplete="username" class="input" value=""
                                size="20">
                        </p>
                        <p class="login-password">
                            <label for="user_pass">Mot de passe</label>
                            <input type="password" name="pwd" id="user_pass" autocomplete="current-password" spellcheck="false"
                                class="input" value="" size="20">
                        </p>
                        <p class="login-submit">
                            <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="Valider">
                            <input type="hidden" name="redirect_to" value="/dashboard">
                        </p>
                    </form>
                </div>
            </div>
            <div id="footer_accueil" class="bloc_blanc_arrondi">
                <div id="footer1">
                    <img src="{{ asset('images/alertes-urgence-sms.webp') }}" alt="Prévention sms alertes pompiers incendies">
                </div>
                <div id="footer2">Mentions légales - Contact - Conception</div>
                <div id="footer3">
                    <img src="{{ asset('images/logos-partenaires.jpg') }}" alt="Partenaires">
                </div>
            </div>
        </div>
    </div>
</body>

</html>
