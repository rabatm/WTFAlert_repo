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

    <!-- Bloc de tri par secteur -->
    <section class="bloc-tri-secteur">
        <span>Secteurs :</span>
        <span id="secteurs-checkboxes">
            @foreach($secteurs as $secteur)
                <label style="margin-right:10px;"><input type="checkbox" class="secteur-filter" value="{{ $secteur->nom }}"> {{ $secteur->nom }}</label>
            @endforeach
            <label style="margin-left:20px;"><input type="checkbox" id="secteurs-all"> Tout / Aucun</label>
        </span>
    </section>

    <!-- Bloc filtres avancés -->
    <section class="bloc-filtres">
        <button id="toggle-filtres" type="button">Filtres avancés</button>
        <div id="filtres-avances" style="display:none;">
            <label><input type="checkbox" id="filtre-animaux"> Avec animaux</label>
            <!-- Autres filtres à ajouter ici -->
        </div>
    </section>

    <!-- Zone d'actions (mail/SMS) -->
    <section id="zone-actions"></section>

    <!-- Liste des foyers -->
    <section id="liste-foyers">
        <!-- Foyers affichés ici par JS -->
    </section>

    <!-- Popup fiche foyer -->
    <div id="popup-foyer" style="display:none;"></div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let foyersData = @json($foyersData);

function renderFoyers(foyers) {
    let html = '';
    foyers.forEach(function(item, idx) {
        const f = item.foyer;
        html += `<div class=\"carte-foyer\" data-id=\"${f.id}\">\n` +
            `<input type=\"checkbox\" class=\"select-foyer\" data-id=\"${f.id}\" id=\"foyer-check-${f.id}\"> ` +
            `<label for=\"foyer-check-${f.id}\"><strong>${f.nom}</strong></label><br>` +
            `${f.adresse ? f.adresse + ', ' : ''}${f.code_postal} ${f.ville}<br>` +
            `<span class=\"secteur\">Secteurs : ${(f.secteurs && f.secteurs.length) ? f.secteurs.join(', ') : '-'}</span>` +
            `</div>`;
    });
    $('#liste-foyers').html(html);
}

$(function() {
    // Ajout case à cocher tout sélectionner
    $('#liste-foyers').before('<div><input type="checkbox" id="select-all-foyers"> <label for="select-all-foyers">Tout sélectionner / désélectionner</label></div>');

    // Affichage initial
    renderFoyers(foyersData);

    // Filtrage par secteurs (cases à cocher)
    $(document).on('change', '.secteur-filter', function() {
        const checked = $('.secteur-filter:checked').map(function(){return this.value;}).get();
        let filtered = foyersData;
        if (checked.length > 0) {
            filtered = foyersData.filter(item => item.foyer.secteurs && item.foyer.secteurs.some(s => checked.includes(s)));
        }
        renderFoyers(filtered);
        $('#select-all-foyers').prop('checked', false);
        // Mettre à jour la case "Tout/Aucun" selon l'état
        $('#secteurs-all').prop('checked', $('.secteur-filter:checked').length === $('.secteur-filter').length);
    });

    // Tout/Aucun secteurs
    $(document).on('change', '#secteurs-all', function() {
        $('.secteur-filter').prop('checked', this.checked).trigger('change');
    });

    // Tout sélectionner/désélectionner foyers
    $(document).on('change', '#select-all-foyers', function() {
        $('.select-foyer').prop('checked', this.checked);
    });

    // Si on change un checkbox individuel, décocher "tout sélectionner" si besoin
    $(document).on('change', '.select-foyer', function() {
        if (!this.checked) {
            $('#select-all-foyers').prop('checked', false);
        } else if ($('.select-foyer:checked').length === $('.select-foyer').length) {
            $('#select-all-foyers').prop('checked', true);
        }
    });

    // Toggle filtres avancés
    $('#toggle-filtres').on('click', function() {
        $('#filtres-avances').toggle();
    });
});
</script>

@include('footer')


