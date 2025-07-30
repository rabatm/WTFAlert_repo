@include('header', ['title' => 'Accueil'])

<main>
    {{-- Bloc principal avec contenu d--}}
    <section class="hero">
        <div class="contenu">
            <h1><strong>Administrés</strong>.</h1>

            @if(session('error'))
                <p class="error">{{ session('error') }}</p>
            @endif
        </div>

    </section>



@include('footer')


