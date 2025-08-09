@include('header', ['title' => 'Accueil'])

<main>
    <!-- Bloc onglets filtres -->
    <section class="bloc-onglets">
        <div class="onglets-titres">
            <button class="onglet-btn" data-onglet="secteurs" type="button">Secteurs</button>
            <button class="onglet-btn" data-onglet="filtres" type="button">Filtres avancés</button>
            <button class="onglet-btn" data-onglet="colonnes" type="button">Colonnes à afficher</button>
            <button class="onglet-btn" data-onglet="actions" type="button">Actions</button>
            <button class="onglet-btn" data-onglet="recherche" type="button">Recherche</button>
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
                    <label><input type="checkbox" class="col-affiche" value="secteurs" checked> Secteurs</label>
                    <label><input type="checkbox" class="col-affiche" value="complement_dadresse"> Complément d'adresse</label>
                    <label><input type="checkbox" class="col-affiche" value="code_postal"> Code postal</label>
                    <label><input type="checkbox" class="col-affiche" value="ville"> Ville</label>
                    <label><input type="checkbox" class="col-affiche" value="telephone_fixe"> Téléphone fixe</label>
                    <label><input type="checkbox" class="col-affiche" value="animaux"> Animaux</label>
                    <label><input type="checkbox" class="col-affiche" value="internet"> Internet</label>
                    <label><input type="checkbox" class="col-affiche" value="vulnerable"> Vulnérable</label>
                    <label><input type="checkbox" class="col-affiche" value="non_connecte"> Non connecté</label>
                    <label><input type="checkbox" class="col-affiche" value="indication"> Indication</label>
                    <label><input type="checkbox" class="col-affiche" value="info"> Info</label>
                    <label><input type="checkbox" class="col-affiche" value="latitude"> Latitude</label>
                    <label><input type="checkbox" class="col-affiche" value="longitude"> Longitude</label>
                    <label><input type="checkbox" class="col-affiche" value="periode_naissance"> Période de naissance</label>
                    <label><input type="checkbox" class="col-affiche" value="collectivite_id"> Collectivité ID</label>
                    <span style="margin-left:20px;">
                        <button type="button" id="colonnes-tous">Tous</button>
                        <button type="button" id="colonnes-aucun">Aucun</button>
                        <button type="button" id="colonnes-defaut" style="font-weight:bold;">Défaut</button>
                    </span>
                </div>
            </div>
            <div class="onglet-content" id="onglet-actions" style="display:none;">
                <section id="zone-actions">
                    <strong>Actions</strong><br><br>
                    <label><input type="checkbox" id="imprimable-action"> Imprimable</label>
                    <button id="btn-imprimer" type="button" style="margin-left:15px;">Imprimer</button>
                    <button id="btn-sms" type="button" style="margin-left:15px;">Envoyer SMS</button>
                    <button id="btn-email" type="button" style="margin-left:10px;">Envoyer email</button>
                    <hr style="margin:15px 0;">
                    <div id="export-mail-bloc">
                        <label style="display:block;margin-bottom:5px;">Emails destinataires (séparés par virgule, point-virgule ou espace) :</label>
                        <textarea id="export-emails" rows="2" style="width:100%;"></textarea>
                        <div style="margin-top:8px;">
                            <button id="btn-export-pdf" type="button">Télécharger PDF sélection</button>
                            <button id="btn-export-mail" type="button" style="margin-left:10px;">Envoyer PDF par email</button>
                            <span id="export-status" style="margin-left:15px;color:#555;"></span>
                        </div>
                    </div>
                </section>
            </div>
            <div class="onglet-content" id="onglet-recherche" style="display:none;">
                <input type="text" id="input-recherche" placeholder="Rechercher..." style="width:100%;padding:8px;margin-top:10px;">
            </div>
        </div>
    </section>

    <!-- Section statistiques -->
    <section id="stats-foyers">
        <span id="stat-total-foyers"></span> |
        <span id="stat-filtre-foyers"></span> |
        <span id="stat-selection-foyers"></span>
    </section>

    <!-- Liste des foyers -->
    <section id="liste-foyers">
        <div id="select-all-wrapper" class="no-print">
            <input type="checkbox" id="select-all-foyers" checked class="no-print">
            <label for="select-all-foyers" class="no-print">Tout sélectionner / désélectionner</label>
        </div>
        <!-- Foyers affichés ici par JS -->
    </section>

    <!-- Popup fiche foyer -->
    <div id="popup-foyer" style="display:none;"></div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let foyersData = @json($foyersData);

function updateStats(filteredFoyers) {
    // Total
    let totalHabitants = 0;
    foyersData.forEach(item => { totalHabitants += (item.habitants ? item.habitants.length : 0); });
    $('#stat-total-foyers').text('Foyers total : ' + foyersData.length + ' (' + totalHabitants + ' habitants)');
    // Filtrés
    let filtredHabitants = 0;
    filteredFoyers.forEach(item => { filtredHabitants += (item.habitants ? item.habitants.length : 0); });
    $('#stat-filtre-foyers').text('Foyers affichés : ' + filteredFoyers.length + ' (' + filtredHabitants + ' habitants)');
    // Sélectionnés
    let selectedFoyers = $('.select-foyer:checked').map(function(){return $(this).closest('.carte-foyer').data('id');}).get();
    let selectedCount = selectedFoyers.length;
    let selectedHabitants = 0;
    filteredFoyers.forEach(item => {
        if (selectedFoyers.includes(item.foyer.id)) {
            selectedHabitants += (item.habitants ? item.habitants.length : 0);
        }
    });
    $('#stat-selection-foyers').text('Foyers sélectionnés : ' + selectedCount + ' (' + selectedHabitants + ' habitants)');
}

function renderFoyers(foyers) {
    const colonnes = $('.col-affiche:checked').map(function(){return this.value;}).get();
    let html = '';
    html += $('#select-all-wrapper')[0].outerHTML;
    html += '<div id="cartes-foyers">';
    foyers.forEach(function(item) {
        const f = item.foyer;
        html += `<div class="carte-foyer" data-id="${f.id}" style="cursor:pointer;">`+
            `<input type=\"checkbox\" class=\"select-foyer no-print\" data-id=\"${f.id}\" id=\"foyer-check-${f.id}\"> `+
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
        if (colonnes.includes('collectivite_id')) { html += `Collectivité ID : ${f.collectivite_id || '-'}<br>`; }
        html += `<span class=\"secteur\">Secteurs : ${(f.secteurs && f.secteurs.length) ? f.secteurs.join(', ') : '-'}<\/span>`+
            `</div>`;
    });
    html += '</div>';
    $('#liste-foyers').html(html);
    $('#liste-foyers').find('.select-foyer').prop('checked', true);
    updateStats(foyers);
}

function normalizeString(str) {
    if (!str) return '';
    // Supprime les accents
    str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    // Supprime espaces et met en minuscule
    return str.replace(/\s+/g, '').toLowerCase();
}

function foyerMatchesSearch(item, search) {
    if (!search) return true;
    const f = item.foyer;
    let allFields = '';
    for (const key in f) {
        if (f[key]) allFields += ' ' + f[key];
    }
    if (item.habitants && item.habitants.length) {
        item.habitants.forEach(h => {
            for (const key in h) {
                if (h[key]) allFields += ' ' + h[key];
            }
        });
    }
    return normalizeString(allFields).includes(normalizeString(search));
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
    // Recherche
    const search = $('#input-recherche').val();
    if (search && search.trim() !== '') {
        filtered = filtered.filter(item => foyerMatchesSearch(item, search));
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

    // Liste des foyers
    $('#select-all-foyers').change(function() {
        $('.select-foyer').prop('checked', this.checked);
    });

    // Affichage initial
    renderFoyers(foyersData);
    // Cocher toutes les cases au démarrage
    $(document).ready(function() {
        $('#select-all-foyers').prop('checked', true);
        $('.select-foyer').prop('checked', true);
    });

    // Classe par défaut sur #liste-foyers
    $('#liste-foyers').addClass('foyers-display');
    // Case à cocher imprimable (suppr. print-optimized)
    $(document).on('change', '#imprimable-action', function() {
        if (this.checked) {
            $('#liste-foyers').removeClass('foyers-display').addClass('foyers-print');
        } else {
            $('#liste-foyers').removeClass('foyers-print').addClass('foyers-display');
        }
    });

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

    // Recherche dynamique
    $(document).on('input', '#input-recherche', function() {
        renderFoyers(getFilteredFoyers());
    });

    // Mettre à jour stats à chaque changement de filtre ou sélection
    $(document).on('change', '.secteur-filter, #filtre-animaux, #filtre-vulnerable, #filtre-internet, #filtre-non_connecte, .select-foyer', function() {
        updateStats(getFilteredFoyers());
    });

    // Bouton imprimer cartes-foyers
    $(document).on('click', '#btn-imprimer', function() {
        // Marquer les cartes non cochées comme no-print temporairement
        $('.carte-foyer').each(function(){
            if(!$(this).find('.select-foyer').is(':checked')) {
                $(this).addClass('no-print-temp');
            }
        });
        // Ajouter style print temporaire pour no-print-temp
        const styleTag = $('<style id="print-temp-style" media="print">.no-print-temp{display:none !important;}</style>');
        $('head').append(styleTag);
        window.print();
        // Nettoyage après un léger délai (certains navigateurs retardent print)
        setTimeout(function(){
            $('.no-print-temp').removeClass('no-print-temp');
            $('#print-temp-style').remove();
        }, 500);
    });

    // Boutons colonnes Tous/Aucun/Défaut
    $(document).on('click', '#colonnes-tous', function() {
        $('.col-affiche').prop('checked', true).trigger('change');
    });
    $(document).on('click', '#colonnes-aucun', function() {
        $('.col-affiche').prop('checked', false).trigger('change');
    });
    $(document).on('click', '#colonnes-defaut', function() {
        $('.col-affiche').prop('checked', false);
        $('.col-affiche[value="adresse"], .col-affiche[value="secteurs"]').prop('checked', true);
        $('.col-affiche').trigger('change');
    });

    // Rendre la carte cliquable pour toggler la sélection
    $(document).on('click', '.carte-foyer', function(e){
        if($(e.target).is('input.select-foyer, label, a, button')) return; // éviter double toggle
        const cb = $(this).find('.select-foyer').get(0);
        if(cb){
            cb.checked = !cb.checked;
            $(cb).trigger('change');
        }
    });

    function getSelectedFoyerIds() {
        return $('.select-foyer:checked').map(function(){return $(this).data('id');}).get();
    }
    function getSelectedColumns() {
        return $('.col-affiche:checked').map(function(){return this.value;}).get();
    }
    function postJson(url, data) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify(data)
        });
    }
    function extractErrorResponse(r){
        const ct = r.headers.get('content-type')||'';
        if(ct.includes('application/json')) return r.json().then(j=>JSON.stringify(j));
        return r.text();
    }
    // Bouton export PDF avec debug erreurs
    $('#btn-export-pdf').on('click', function(){
        const ids = getSelectedFoyerIds();
        if(!ids.length){ alert('Aucun foyer sélectionné'); return; }
        const cols = getSelectedColumns();
        postJson('{{ route('foyers.export.pdf') }}', {foyer_ids: ids, columns: cols})
            .then(async r=>{
                if(!r.ok){
                    let body;
                    try { body = await extractErrorResponse(r); } catch(e){ body = e.message; }
                    alert('Erreur génération PDF (HTTP '+r.status+')\n'+ (body||'Sans détail'));
                    console.error('PDF error', r.status, body);
                    return;
                }
                const blob = await r.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url; a.download = 'liste_foyers.pdf'; a.click();
                window.URL.revokeObjectURL(url);
            })
            .catch(e=>{
                alert('Erreur réseau PDF: '+ e.message);
                console.error(e);
            });
    });
    $('#btn-export-mail').on('click', function(){
        const ids = getSelectedFoyerIds();
        if(!ids.length){ alert('Aucun foyer sélectionné'); return; }
        const cols = getSelectedColumns();
        const emails = $('#export-emails').val();
        $('#export-status').text('Envoi...');
        postJson('{{ route('foyers.export.email') }}', {foyer_ids: ids, columns: cols, emails: emails})
            .then(async r=>{
                if(!r.ok){
                    let body;
                    try { body = await extractErrorResponse(r); } catch(e){ body = e.message; }
                    $('#export-status').text('Erreur ('+r.status+')');
                    alert('Erreur envoi email (HTTP '+r.status+')\n'+ (body||'Sans détail'));
                    console.error('Email export error', r.status, body);
                    return;
                }
                const data = await r.json();
                if(data.ok){ $('#export-status').text('Envoyé à '+data.count+' destinataire(s)'); }
                else { $('#export-status').text(data.message||'Erreur'); alert('Erreur: '+(data.message||'Inconnue')); }
            })
            .catch(e=>{ $('#export-status').text('Erreur réseau'); alert('Erreur réseau: '+e.message); console.error(e); });
    });
});
</script>

@include('footer')


