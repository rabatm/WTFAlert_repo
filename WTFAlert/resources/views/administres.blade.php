@include('header', ['title' => 'Accueil'])

<main>
    <!-- Bloc onglets filtres -->
    <section class="bloc-onglets">
        <div class="onglets-titres">
            <button class="onglet-btn" data-onglet="secteurs" type="button">Secteurs</button>
            <button class="onglet-btn" data-onglet="filtres" type="button">Filtres avancés</button>
            <button class="onglet-btn" data-onglet="colonnes" type="button">Colonnes à afficher</button>
            <button class="onglet-btn" data-onglet="actions" type="button">Actions</button>
        </div>
        <div class="onglets-contenus">
            <div class="onglet-content" id="onglet-secteurs">
                <span>Secteurs :</span>
                <span id="secteurs-checkboxes">
                    @foreach($secteurs as $secteur)
                        <label style="margin-right:10px;"><input type="checkbox" class="secteur-filter" value="{{ $secteur->nom }}"> {{ $secteur->nom }}</label>
                    @endforeach
                    <label style="margin-left:20px;"><input type="checkbox" id="secteurs-all"> Tout / Aucun</label>
                </span>
            </div>
            <div class="onglet-content" id="onglet-filtres" style="display:none;">
                <label><input type="checkbox" id="filtre-animaux"> Avec animaux</label>
                <label><input type="checkbox" id="filtre-vulnerable"> Vulnérable</label>
                <label><input type="checkbox" id="filtre-internet"> Internet</label>
                <label><input type="checkbox" id="filtre-non_connecte"> Non connecté</label>
            </div>
            <div class="onglet-content" id="onglet-colonnes" style="display:none;">
                <div id="colonnes-options">
                    <label><input type="checkbox" class="col-affiche" value="adresse" checked> Adresse</label>
                    <label><input type="checkbox" class="col-affiche" value="complement_dadresse" checked> Complément d'adresse</label>
                    <label><input type="checkbox" class="col-affiche" value="code_postal" checked> Code postal</label>
                    <label><input type="checkbox" class="col-affiche" value="ville" checked> Ville</label>
                    <label><input type="checkbox" class="col-affiche" value="telephone_fixe" checked> Téléphone fixe</label>
                    <label><input type="checkbox" class="col-affiche" value="animaux" checked> Animaux</label>
                    <label><input type="checkbox" class="col-affiche" value="internet" checked> Internet</label>
                    <label><input type="checkbox" class="col-affiche" value="vulnerable" checked> Vulnérable</label>
                    <label><input type="checkbox" class="col-affiche" value="non_connecte" checked> Non connecté</label>
                    <label><input type="checkbox" class="col-affiche" value="indication" checked> Indication</label>
                    <label><input type="checkbox" class="col-affiche" value="info" checked> Info</label>
                    <label><input type="checkbox" class="col-affiche" value="latitude" checked> Latitude</label>
                    <label><input type="checkbox" class="col-affiche" value="longitude" checked> Longitude</label>
                    <label><input type="checkbox" class="col-affiche" value="periode_naissance" checked> Période de naissance</label>
                    <label><input type="checkbox" class="col-affiche" value="collectivite_id" checked> Collectivité ID</label>
                </div>
            </div>
            <div class="onglet-content" id="onglet-actions" style="display:none;">
                <section id="zone-actions"><strong>Actions</strong></section>
            </div>
        </div>
    </section>

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
    // Récupérer les colonnes à afficher
    const colonnes = $('.col-affiche:checked').map(function(){return this.value;}).get();
    let html = '';
    foyers.forEach(function(item, idx) {
        const f = item.foyer;
        html += `<div class=\"carte-foyer\" data-id=\"${f.id}\">\n` +
            `<input type=\"checkbox\" class=\"select-foyer\" data-id=\"${f.id}\" id=\"foyer-check-${f.id}\"> ` +
            `<label for=\"foyer-check-${f.id}\"><strong>${f.nom}</strong></label><br>`;
        if (colonnes.includes('adresse')) {
            html += `${f.adresse ? f.adresse + ', ' : ''}`;
        }
        if (colonnes.includes('complement_dadresse')) {
            html += `${f.complement_dadresse ? f.complement_dadresse + ', ' : ''}`;
        }
        if (colonnes.includes('code_postal')) {
            html += `${f.code_postal ? f.code_postal + ' ' : ''}`;
        }
        if (colonnes.includes('ville')) {
            html += `${f.ville ? f.ville + '<br>' : ''}`;
        }
        if (colonnes.includes('telephone_fixe')) {
            html += `Téléphone fixe : ${f.telephone_fixe || '-'}<br>`;
        }
        if (colonnes.includes('animaux')) {
            html += `Animaux : ${f.animaux ? 'Oui' : 'Non'}<br>`;
        }
        if (colonnes.includes('internet')) {
            html += `Internet : ${f.internet ? 'Oui' : 'Non'}<br>`;
        }
        if (colonnes.includes('vulnerable')) {
            html += `Vulnérable : ${f.vulnerable ? 'Oui' : 'Non'}<br>`;
        }
        if (colonnes.includes('non_connecte')) {
            html += `Non connecté : ${f.non_connecte ? 'Oui' : 'Non'}<br>`;
        }
        if (colonnes.includes('indication')) {
            html += `Indication : ${f.indication || '-'}<br>`;
        }
        if (colonnes.includes('info')) {
            html += `Info : ${f.info || '-'}<br>`;
        }
        if (colonnes.includes('latitude')) {
            html += `Latitude : ${f.latitude || '-'}<br>`;
        }
        if (colonnes.includes('longitude')) {
            html += `Longitude : ${f.longitude || '-'}<br>`;
        }
        if (colonnes.includes('periode_naissance')) {
            html += `Période de naissance : ${f.periode_naissance || '-'}<br>`;
        }
        if (colonnes.includes('collectivite_id')) {
            html += `Collectivité ID : ${f.collectivite_id || '-'}<br>`;
        }
        html += `<span class=\"secteur\">Secteurs : ${(f.secteurs && f.secteurs.length) ? f.secteurs.join(', ') : '-'}</span>` +
            `</div>`;
    });
    $('#liste-foyers').html(html);
}

function getFilteredFoyers() {
    // Filtrage secteurs
    const checkedSecteurs = $('.secteur-filter:checked').map(function(){return this.value;}).get();
    let filtered = foyersData;
    if (checkedSecteurs.length > 0) {
        filtered = filtered.filter(item => item.foyer.secteurs && item.foyer.secteurs.some(s => checkedSecteurs.includes(s)));
    }
    // Filtres avancés
    if ($('#filtre-animaux').is(':checked')) {
        filtered = filtered.filter(item => item.foyer.animaux);
    }
    if ($('#filtre-vulnerable').is(':checked')) {
        filtered = filtered.filter(item => item.foyer.vulnerable);
    }
    if ($('#filtre-internet').is(':checked')) {
        filtered = filtered.filter(item => item.foyer.internet);
    }
    if ($('#filtre-non_connecte').is(':checked')) {
        filtered = filtered.filter(item => item.foyer.non_connecte);
    }
    return filtered;
}

$(function() {
    // Onglets filtres
    $('.onglet-btn').on('click', function() {
        var onglet = $(this).data('onglet');
        $('.onglet-content').hide();
        $('#onglet-' + onglet).show();
        $('.onglet-btn').removeClass('active');
        $(this).addClass('active');
    });

    // Ajout case à cocher tout sélectionner
    $('#liste-foyers').before('<div><input type="checkbox" id="select-all-foyers"> <label for="select-all-foyers">Tout sélectionner / désélectionner</label></div>');

    // Affichage initial
    renderFoyers(foyersData);

    // Filtrage par secteurs (cases à cocher) et filtres avancés
    $(document).on('change', '.secteur-filter, #filtre-animaux, #filtre-vulnerable, #filtre-internet, #filtre-non_connecte', function() {
        renderFoyers(getFilteredFoyers());
        $('#select-all-foyers').prop('checked', false);
        // Mettre à jour la case "Tout/Aucun" selon l'état
        $('#secteurs-all').prop('checked', $('.secteur-filter:checked').length === $('.secteur-filter').length);
    });

    // Colonnes à afficher
    $('#toggle-colonnes').on('click', function() {
        $('#colonnes-options').toggle();
    });
    $(document).on('change', '.col-affiche', function() {
        renderFoyers(getFilteredFoyers());
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


