<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suivi Individuel des Animaux - AgriElevage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #e6f3e6; }
        .sidebar {
            background: #345c37;
            min-height: 100vh;
            color: #fff;
            width: 220px;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .sidebar .nav-link, .sidebar .sidebar-title { color: #fff; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: #5fc77a;
            color: #345c37;
            font-weight: 500;
        }
        .sidebar .nav-link i { margin-right: 12px; }
        .sidebar-profile {
            background: #e6f3e6;
            color: #345c37;
            border-radius: 1rem;
            padding: 0.6rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            margin: 1.2rem 1rem 1.2rem 1rem;
        }
        .sidebar-profile img {
            width: 38px; height: 38px; border-radius: 50%; object-fit: cover;
        }
        .main-content {
            margin-left: 220px;
            padding: 2rem 1rem;
            min-height: 100vh;
            background: #e6f3e6;
        }
        .animal-list-card {
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 1px 8px 0 rgba(31, 38, 135, 0.06);
            padding: 1.5rem 1.5rem;
        }
        .animal-list-table th, .animal-list-table td {
            background: none;
            border: none;
            vertical-align: middle;
        }
        .animal-list-table th {
            color: #5fc77a;
            font-weight: 700;
            font-size: 1rem;
            background: #e6f3e6;
        }
        .animal-list-table tr {
            border-radius: 0.7rem;
        }
        .animal-list-table tr.animal-row {
            cursor: pointer;
            border-radius: 0.7rem;
            transition: background 0.15s;
        }
        .animal-list-table tr.animal-row.selected, .animal-list-table tr.animal-row:hover {
            background: #e6f3e6;
        }
        .animal-list-table td {
            font-size: 1rem;
        }
        .filter-btn-group .btn {
            border-radius: 1rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .filter-btn-group .btn.active, .filter-btn-group .btn:focus {
            background: #5fc77a;
            color: #fff;
            border-color: #5fc77a;
        }
        .animal-detail-card {
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 1px 8px 0 rgba(31, 38, 135, 0.06);
            padding: 1.5rem 1.5rem;
            min-height: 400px;
        }
        .animal-detail-label { color: #5fc77a; font-weight: 600; }
        .animal-detail-value { color: #345c37; }
        .animal-detail-section-title { font-weight: 700; color: #345c37; margin-top: 1.2rem; }
        .animal-detail-history { font-size: 0.97rem; }
        .animal-detail-history .text-success { font-size: 0.95rem; }
        .animal-detail-actions .btn {
            border-radius: 1rem;
            margin-bottom: 0.5rem;
            margin-right: 0.5rem;
        }
        @media (max-width: 991.98px) {
            .sidebar { position: static; width: 100%; min-height: auto; }
            .main-content { margin-left: 0; padding: 1rem 0.2rem; }
        }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="sidebar-title fs-5 fw-bold py-4 px-4 d-none d-lg-block">AgriElevage</div>
    <ul class="nav flex-column mb-2 px-2">
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i>Accueil</a></li>
        <li class="nav-item mb-2"><a class="nav-link active rounded" href="#"><i class="bi bi-person-lines-fill"></i>Suivi Individuel</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{route('health.diagnostic')}}"><i class="bi bi-heart-pulse"></i>Santé</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{route('reproductive-analytics')}}"><i class="bi bi-activity"></i>Reproduction</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{route('genetique')}}"><i class="bi bi-activity"></i>Génétique</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{route('learning-courses')}}"><i class="bi bi-journal-bookmark"></i>Apprentissage</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="#"><i class="bi bi-people"></i>Communauté</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{ route('chatbot') }}"><i class="bi bi-briefcase"></i>Assistant IA</a></li>
    </ul>
    <div class="sidebar-profile mt-auto mb-3">
        <div>
            <div class="fw-bold" style="font-size: 0.95rem;">{{ Auth::user()->name ?? 'Utilisateur' }}</div>
            <div style="font-size: 0.85rem; color:#5fc77a;">{{ Auth::user()->email ?? '' }}</div>
        </div>
        <i class="bi bi-chevron-right ms-auto" style="color:#345c37;"></i>
    </div>
</div>
<div class="main-content">
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-outline-success" id="addBreedingBtn">
                        <i class="bi bi-plus-circle"></i> Ajouter un croisement
                    </button>
                </div>
                <div class="animal-list-card mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                        <div class="filter-btn-group">
                            <button class="btn btn-success" id="addAnimalBtn"><i class="bi bi-plus-circle"></i> Ajouter un animal</button>
                            <button class="btn btn-outline-success filter-btn active" data-type="">Tous</button>
                            @foreach($types as $type)
                                <button class="btn btn-outline-success filter-btn" data-type="{{ $type }}">{{ ucfirst($type) }}</button>
                            @endforeach
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <input type="text" class="form-control" id="searchAnimalInput" placeholder="Rechercher un animal...">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table animal-list-table mb-0">
                            <thead>
                                <tr>
                                    <th>NOM</th>
                                    <th>ESPÈCE</th>
                                    <th>SEXE</th>
                                    <th>ÂGE</th>
                                    <th>STATUT</th>
                                    <th>VENDU</th> <!-- colonne ajoutée -->
                                </tr>
                            </thead>
                            <tbody id="animalListBody">
                                <!-- Rempli dynamiquement -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" id="animalDetailCol" style="display:none;">
                <div class="animal-detail-card" id="animalDetailCard">
                    <!-- Rempli dynamiquement -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modale pour ajouter un animal (réutilise la modale existante du dashboard) -->
<div class="modal fade" id="animalModal" tabindex="-1" aria-labelledby="animalModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="animalForm">
      <div class="modal-header">
        <h5 class="modal-title" id="animalModalLabel">Ajouter un animal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div id="animalFormAlert"></div>
        <div class="mb-3">
          <label class="form-label">Nom de l'animal</label>
          <input type="text" class="form-control" name="nom" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Sexe</label>
          <select class="form-select" name="sexe" required>
            <option value="">Choisir...</option>
            <option value="male">Mâle</option>
            <option value="femelle">Femelle</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Type d'animal</label>
          <select class="form-select" name="type" id="animalType" required>
            <option value="">Choisir...</option>
            @foreach($types as $type)
                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Catégorie</label>
          <select class="form-select" name="categorie" id="animalCategorie" required>
            <option value="">Choisir le type d'abord</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Date de naissance</label>
          <input type="date" class="form-control" name="date_naissance" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Nom de la mère (optionnel)</label>
          <select class="form-select" name="mere" id="mereSelect">
            <option value="">Sélectionner la mère</option>
            <!-- Options dynamiques -->
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Nom du père (optionnel)</label>
          <select class="form-select" name="pere" id="pereSelect">
            <option value="">Sélectionner le père</option>
            <!-- Options dynamiques -->
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<!-- Modale pour ajouter un croisement -->
<div class="modal fade" id="breedingModal" tabindex="-1" aria-labelledby="breedingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="breedingForm">
      <div class="modal-header">
        <h5 class="modal-title" id="breedingModalLabel">Ajouter un croisement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div id="breedingFormAlert"></div>
        <div class="mb-3">
          <!-- Espèce : permet de filtrer mâles/femelles -->
          <label class="form-label">Espèce</label>
          <select class="form-select" name="espece" id="breedingSpeciesSelect" required>
              <option value="">Choisir...</option>
              @foreach($types as $type)
                  <option value="{{ $type }}">{{ ucfirst($type) }}</option>
              @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Mâle</label>
          <select class="form-select" name="male_id" id="breedingMaleSelect" required>
            <option value="">Sélectionner un mâle</option>
            <!-- Options dynamiques -->
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Femelle</label>
          <select class="form-select" name="female_id" id="breedingFemaleSelect" required>
            <option value="">Sélectionner une femelle</option>
            <!-- Options dynamiques -->
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Date de mise bas</label>
          <input type="date" id="breedingMiseBas" class="form-control" name="date_mise_bas" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Taille de la portée</label>
          <input type="number" class="form-control" name="taille_portee" min="0" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Nombre de morts-nés</label>
          <input type="number" class="form-control" name="nb_morts" min="0" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Enregistrer poids -->
<div class="modal fade" id="weightModal" tabindex="-1" aria-labelledby="weightModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="weightForm">
      <div class="modal-header">
        <h5 class="modal-title" id="weightModalLabel">Enregistrer le poids</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="weightAnimalId" name="id">
        <div id="weightFormAlert"></div>
        <div class="mb-3">
          <label class="form-label">Poids (kg)</label>
          <input type="number" step="0.01" class="form-control" name="poids" id="weightValue" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Date</label>
          <input type="date" class="form-control" name="poids_date" id="weightDate">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Marquer comme vendu -->
<div class="modal fade" id="sellModal" tabindex="-1" aria-labelledby="sellModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="sellForm">
      <div class="modal-header">
        <h5 class="modal-title" id="sellModalLabel">Marquer comme vendu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="sellAnimalId" name="id">
        <div id="sellFormAlert"></div>
        <p>Confirmer la vente de l'animal sélectionné ?</p>
        <div class="mb-3">
          <label class="form-label">Prix de vente (optionnel)</label>
          <input type="number" step="0.01" class="form-control" name="prix_vente" id="sellPrice">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger">Marquer comme vendu</button>
      </div>
    </form>
  </div>
</div>

<!-- Account modal (same markup) -->
<div class="modal fade" id="accountModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<form class="modal-content" id="accountForm">
			<div class="modal-header"><h5 class="modal-title">Paramètres du compte</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
			<div class="modal-body">
				<div id="accountAlert"></div>
				<div class="mb-3"><label class="form-label">Nom</label><input id="accountName" name="name" class="form-control" required></div>
				<div class="mb-3"><label class="form-label">Email</label><input id="accountEmail" name="email" type="email" class="form-control" required></div>
				<div class="mb-3"><button id="sendResetBtn" type="button" class="btn btn-outline-danger">Modifier le mot de passe (envoyer lien par email)</button></div>
			</div>
			<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button><button type="submit" class="btn btn-success">Enregistrer</button></div>
		</form>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Catégories dynamiques pour la modale
    const categories = {
        poule: [
            { value: 'goliath', label: 'Goliath' },
            { value: 'pantalonne', label: 'Pantalonné' },
            { value: 'village', label: 'Village' },
            { value: 'cou_nu', label: 'Cou nu' }
        ],
        lapin: [
            { value: 'ps-40', label: 'PS-40' },
            { value: 'ps-119', label: 'PS-119' }
        ]
        // Ajoutez d'autres types/catégories si besoin
    };
    document.getElementById('animalType').addEventListener('change', function() {
        const type = this.value;
        const catSelect = document.getElementById('animalCategorie');
        catSelect.innerHTML = '<option value="">Choisir...</option>';
        if (categories[type]) {
            categories[type].forEach(cat => {
                const opt = document.createElement('option');
                opt.value = cat.value;
                opt.textContent = cat.label;
                catSelect.appendChild(opt);
            });
        }
        // Charger les animaux pour la lignée selon le type sélectionné
        fetch(`/animals?type=${type}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            // Mères = femelles, Pères = mâles
            const mereSelect = document.getElementById('mereSelect');
            const pereSelect = document.getElementById('pereSelect');
            mereSelect.innerHTML = '<option value="">Sélectionner la mère</option>';
            pereSelect.innerHTML = '<option value="">Sélectionner le père</option>';
            data.forEach(animal => {
                if (animal.sexe && animal.sexe.toLowerCase() === 'femelle') {
                    mereSelect.innerHTML += `<option value="${animal.nom}">${animal.nom}</option>`;
                }
                if (animal.sexe && animal.sexe.toLowerCase() === 'male') {
                    pereSelect.innerHTML += `<option value="${animal.nom}">${animal.nom}</option>`;
                }
            });
        });
    });

    // Ouvre la modale d'ajout
    document.getElementById('addAnimalBtn').onclick = function() {
        var modal = new bootstrap.Modal(document.getElementById('animalModal'));
        modal.show();
    };

    // Soumission AJAX du formulaire d'ajout
    document.getElementById('animalForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const data = new FormData(form);
        fetch("{{ route('animals.store') }}", {
            method: "POST",
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            body: data
        })
        .then(res => res.json())
        .then(json => {
            if (json.success) {
                form.reset();
                document.getElementById('animalFormAlert').innerHTML =
                    '<div class="alert alert-success">Animal ajouté avec succès !</div>';
                setTimeout(() => {
                    var modal = bootstrap.Modal.getInstance(document.getElementById('animalModal'));
                    modal.hide();
                    document.getElementById('animalFormAlert').innerHTML = '';
                    loadAnimalList();
                }, 1200);
            } else {
                document.getElementById('animalFormAlert').innerHTML =
                    '<div class="alert alert-danger">Erreur lors de l\'ajout.</div>';
            }
        })
        .catch(() => {
            document.getElementById('animalFormAlert').innerHTML =
                '<div class="alert alert-danger">Erreur lors de l\'ajout.</div>';
        });
    });

    // Filtres dynamiques
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            loadAnimalList();
        });
    });

    // Recherche dynamique
    document.getElementById('searchAnimalInput').addEventListener('input', function() {
        loadAnimalList();
    });

    // Charger la liste des animaux (AJAX)
    function loadAnimalList(selectedId = null) {
        const type = document.querySelector('.filter-btn.active').dataset.type || '';
        const search = document.getElementById('searchAnimalInput').value;
        fetch(`{{ route('animals.index') }}?type=${type}&search=${encodeURIComponent(search)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('animalListBody');
            tbody.innerHTML = '';
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center">Aucun animal trouvé.</td></tr>';
                document.getElementById('animalDetailCol').style.display = 'none';
                return;
            }
            data.forEach(animal => {
                const tr = document.createElement('tr');
                tr.className = 'animal-row';
                tr.dataset.id = animal.id;
                tr.innerHTML = `
                    <td>${animal.nom}</td>
                    <td>${animal.type}</td>
                    <td>${animal.sexe}</td>
                    <td>${animal.age}</td>
                    <td>${animal.statut || 'En bonne santé'}</td>
                    <td>${animal.vendu ? 'Oui' : 'Non'}</td>
                `;
                tr.onclick = function() { selectAnimal(animal.id); };
                tbody.appendChild(tr);
            });
            // Sélectionne le premier animal si aucun n'est sélectionné
            if (selectedId) {
                selectAnimal(selectedId);
            } else {
                selectAnimal(data[0].id);
            }
        });
    }

    // Afficher les détails d'un animal (AJAX)
    function selectAnimal(id) {
        document.querySelectorAll('.animal-row').forEach(tr => tr.classList.remove('selected'));
        const tr = document.querySelector(`.animal-row[data-id="${id}"]`);
        if (tr) tr.classList.add('selected');
        fetch(`/animals/${id}/details`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(animal => {
            document.getElementById('animalDetailCol').style.display = '';
            document.getElementById('animalDetailCard').innerHTML = `
                <h5 class="fw-bold mb-3">Détails de ${animal.nom}</h5>
                <div>
                    <div class="animal-detail-section-title">Informations Générales</div>
                    <div><span class="animal-detail-label">Nom:</span> <span class="animal-detail-value">${animal.nom}</span></div>
                    <div><span class="animal-detail-label">Espèce:</span> <span class="animal-detail-value">${animal.type}</span></div>
                    <div><span class="animal-detail-label">Sexe:</span> <span class="animal-detail-value">${animal.sexe}</span></div>
                    <div><span class="animal-detail-label">Race:</span> <span class="animal-detail-value">${animal.race}</span></div>
                    <div><span class="animal-detail-label">Date de naissance:</span> <span class="animal-detail-value">${animal.date_naissance}</span></div>
                    <div><span class="animal-detail-label">Identifiant:</span> <span class="animal-detail-value">${animal.identifiant}</span></div>
                </div>
                <div class="animal-detail-section-title">Lignage</div>
                <div><span class="animal-detail-label">Mère:</span> <span class="animal-detail-value">${animal.mere}</span></div>
                <div><span class="animal-detail-label">Père:</span> <span class="animal-detail-value">${animal.pere}</span></div>
                <div><span class="animal-detail-label">Grands-parents:</span> <span class="animal-detail-value">${animal.grands_parents}</span></div>
                <div class="animal-detail-section-title">Historique</div>
                <div class="animal-detail-history">${animal.historique || '<span class="text-muted">Aucun historique.</span>'}</div>
                <div class="animal-detail-section-title">Actions Rapides</div>
                <div class="animal-detail-actions">
                    ${animal.type.toLowerCase() === 'lapin' ? '<a href="{{ route('breeding-planning') }}"><button class="btn btn-outline-success btn-sm">Reproduire</button></a>' : ''}
                    <button class="btn btn-outline-success btn-sm" id="btnTreat">Traiter</button>
                    <button class="btn btn-outline-secondary btn-sm" id="btnMarkSold">Marquer comme vendu</button>
                    <button class="btn btn-outline-success btn-sm" id="btnRecordWeight">Enregistrer poids</button>
                </div>
            `;

            // Attacher handlers pour les boutons nouvellement insérés
            // use onclick to avoid multiple listeners when selectAnimal est rappelé
            const btnRecord = document.getElementById('btnRecordWeight');
            if (btnRecord) {
                btnRecord.onclick = function() {
                    document.getElementById('weightAnimalId').value = animal.id;
                    document.getElementById('weightValue').value = animal.poids ?? '';
                    document.getElementById('weightFormAlert').innerHTML = '';
                    new bootstrap.Modal(document.getElementById('weightModal')).show();
                };
            }
            const btnSell = document.getElementById('btnMarkSold');
            if (btnSell) {
                btnSell.onclick = function() {
                    document.getElementById('sellAnimalId').value = animal.id;
                    document.getElementById('sellPrice').value = '';
                    document.getElementById('sellFormAlert').innerHTML = '';
                    new bootstrap.Modal(document.getElementById('sellModal')).show();
                };
            }
        });
    }

    function openBreedingModal() {
        var modal = new bootstrap.Modal(document.getElementById('breedingModal'));
        modal.show();
    }

    // Charger les animaux pour la modale de croisement (filtre par espèce)
    function loadBreedingAnimals(species = null) {
        // prendre la valeur du select si non fournie
        const speciesSelect = document.getElementById('breedingSpeciesSelect');
        const type = species || (speciesSelect ? speciesSelect.value : '') || '';
        const url = type ? `/animals?type=${encodeURIComponent(type)}` : `/animals`;
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
         .then(res => res.json())
         .then(data => {
             const maleSelect = document.getElementById('breedingMaleSelect');
             const femaleSelect = document.getElementById('breedingFemaleSelect');
             maleSelect.innerHTML = '<option value="">Sélectionner un mâle</option>';
             femaleSelect.innerHTML = '<option value="">Sélectionner une femelle</option>';
             data.forEach(animal => {
                 if (animal.sexe && animal.sexe.toLowerCase() === 'male') {
                     maleSelect.innerHTML += `<option value="${animal.id}">${animal.nom}</option>`;
                 }
                 if (animal.sexe && animal.sexe.toLowerCase() === 'femelle') {
                     femaleSelect.innerHTML += `<option value="${animal.id}">${animal.nom}</option>`;
                 }
             });
         });
    }

    // lorsque l'utilisateur change l'espèce dans la modale, recharger la liste
    const breedingSpeciesEl = document.getElementById('breedingSpeciesSelect');
    if (breedingSpeciesEl) {
        breedingSpeciesEl.addEventListener('change', function() {
            loadBreedingAnimals(this.value);
        });
    }

     // Ouvre la modale d'ajout de croisement
     document.getElementById('addBreedingBtn').onclick = function() {
        // définir une valeur par défaut 'lapin' si présente dans le select
        const sp = document.getElementById('breedingSpeciesSelect');
        if (sp && !sp.value) {
            const lap = Array.from(sp.options).find(o => o.value && o.value.toLowerCase() === 'lapin');
            if (lap) sp.value = lap.value;
        }
        loadBreedingAnimals();
         document.getElementById('breedingForm').reset();
         document.getElementById('breedingFormAlert').innerHTML = '';
         // guard : si l'élément existe on le vide sinon on ignore
         const bb = document.getElementById('breedingMiseBas');
         if (bb) bb.value = '';
         var modal = new bootstrap.Modal(document.getElementById('breedingModal'));
         modal.show();
     };

    // Soumission AJAX du formulaire de croisement
    document.getElementById('breedingForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const form = this;
      const alertEl = document.getElementById('breedingFormAlert');
      alertEl.innerHTML = ''; // clear previous message

      const data = new FormData(form);
      // ensure CSRF token is sent in body
      data.append('_token', '{{ csrf_token() }}');

      fetch("{{ url('/breeding') }}", {
        method: "POST",
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: data
      })
      .then(async res => {
        const isJson = res.headers.get('content-type')?.includes('application/json');
        const payload = isJson ? await res.json().catch(() => null) : null;
        if (res.ok) {
          // success response
          form.reset();
          alertEl.innerHTML = '<div class="alert alert-success">Croisement enregistré avec succès !</div>';
          setTimeout(() => {
            hideModalClean('breedingModal');
            alertEl.innerHTML = '';
            loadAnimalList();
          }, 900);
          return;
        }
        // validation error 422
        if (res.status === 422 && payload && payload.errors) {
          const msgs = Object.values(payload.errors).flat().join('<br>');
          alertEl.innerHTML = `<div class="alert alert-danger">${msgs}</div>`;
          return;
        }
        // other errors: display provided message or status text
        const msg = payload && payload.message ? payload.message : `Erreur (${res.status}) lors de l'enregistrement.`;
        alertEl.innerHTML = `<div class="alert alert-danger">${msg}</div>`;
      })
      .catch(err => {
        console.error(err);
        alertEl.innerHTML = '<div class="alert alert-danger">Erreur lors de l\'enregistrement (connexion).</div>';
      });
    });

    // Soumission enregistrement poids
    document.getElementById('weightForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('weightAnimalId').value;
        const form = this;
        const formData = new FormData(form);
        // add token to be safe
        formData.append('_token', '{{ csrf_token() }}');
        fetch(`/animals/${id}`, {
            method: "POST",
            headers: {
                'X-HTTP-Method-Override': 'PUT',
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => {
            if (!res.ok) throw res;
            return res.json();
        })
        .then(json => {
            if (json.success) {
                document.getElementById('weightFormAlert').innerHTML = '<div class="alert alert-success">Poids enregistré.</div>';
                setTimeout(() => {
                    hideModalClean('weightModal');
                    loadAnimalList(id);
                }, 700);
            } else {
                document.getElementById('weightFormAlert').innerHTML = '<div class="alert alert-danger">Erreur.</div>';
            }
        })
        .catch(async (err) => {
            let msg = 'Erreur.';
            try {
                const j = await (err.json ? err.json() : Promise.resolve(null));
                if (j && j.errors) msg = Object.values(j.errors).flat().join(' ');
            } catch(e){}
            console.error(err);
            document.getElementById('weightFormAlert').innerHTML = `<div class="alert alert-danger">${msg}</div>`;
        });
    });

    // Soumission marquer comme vendu
    document.getElementById('sellForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('sellAnimalId').value;
        const form = this;
        const formData = new FormData(form);
        formData.append('vendu', 1);
        formData.append('_token', '{{ csrf_token() }}');
        fetch(`/animals/${id}`, {
            method: "POST",
            headers: {
                'X-HTTP-Method-Override': 'PUT',
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => {
            if (!res.ok) throw res;
            return res.json();
        })
        .then(json => {
            if (json.success) {
                document.getElementById('sellFormAlert').innerHTML = '<div class="alert alert-success">Marqué comme vendu.</div>';
                setTimeout(() => {
                    hideModalClean('sellModal');
                    loadAnimalList(id);
                }, 700);
            } else {
                document.getElementById('sellFormAlert').innerHTML = '<div class="alert alert-danger">Erreur.</div>';
            }
        })
        .catch(async (err) => {
            let msg = 'Erreur.';
            try {
                const j = await (err.json ? err.json() : Promise.resolve(null));
                if (j && j.errors) msg = Object.values(j.errors).flat().join(' ');
            } catch(e){}
            console.error(err);
            document.getElementById('sellFormAlert').innerHTML = `<div class="alert alert-danger">${msg}</div>`;
        });
    });

    // account modal behavior (same as others)
(function(){
	const profile = document.querySelector('.sidebar-profile');
	if (!profile) return;
	const accountModalEl = document.getElementById('accountModal');
	const accountModal = new bootstrap.Modal(accountModalEl);
	const accountForm = document.getElementById('accountForm');
	const accountName = document.getElementById('accountName');
	const accountEmail = document.getElementById('accountEmail');
	const accountAlert = document.getElementById('accountAlert');
	const sendResetBtn = document.getElementById('sendResetBtn');

	const currentUser = { name: {!! json_encode(Auth::user()->name ?? '') !!}, email: {!! json_encode(Auth::user()->email ?? '') !!} };

	profile.style.cursor = 'pointer';
	profile.addEventListener('click', () => { accountAlert.innerHTML=''; accountName.value=currentUser.name; accountEmail.value=currentUser.email; accountModal.show(); });

	accountForm.addEventListener('submit', function(e){ e.preventDefault(); accountAlert.innerHTML=''; const fd=new FormData(accountForm); fd.append('_token','{{ csrf_token() }}'); fetch("{{ route('account.update') }}",{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest'},body:fd}).then(async res=>{ const json=await res.json().catch(()=>null); if(!res.ok) throw json||new Error('Erreur'); currentUser.name=json.user.name; currentUser.email=json.user.email; document.querySelectorAll('.sidebar-profile').forEach(sp=>{ sp.querySelector('div > .fw-bold').textContent=currentUser.name; const em = sp.querySelector('div > div.small, div > div:nth-child(2)'); if(em) em.textContent=currentUser.email; }); accountAlert.innerHTML='<div class="alert alert-success">Profil mis à jour.</div>'; setTimeout(()=>accountModal.hide(),900); }).catch(async err=>{ let msg='Erreur lors de la mise à jour.'; if(err && err.errors) msg=Object.values(err.errors).flat().join('<br>'); accountAlert.innerHTML=`<div class="alert alert-danger">${msg}</div>`; }); });

	sendResetBtn.addEventListener('click', function(){ if(!confirm("Vous allez recevoir un email contenant un lien pour mettre à jour votre mot de passe. Continuer ?")) return; sendResetBtn.disabled=true; fetch("{{ route('account.password.reset') }}",{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({ email: accountEmail.value })}).then(async res=>{ const json=await res.json().catch(()=>null); if(!res.ok) throw json||new Error('Erreur'); accountAlert.innerHTML='<div class="alert alert-success">Un email va vous être envoyé contenant le lien de modification du mot de passe.</div>'; }).catch(err=>{ let msg='Impossible d\'envoyer le lien pour le moment.'; if(err && err.message) msg=err.message; accountAlert.innerHTML=`<div class="alert alert-danger">${msg}</div>`; }).finally(()=>sendResetBtn.disabled=false); });

})();

    // helper pour cacher correctement une modal et nettoyer backdrop
    function hideModalClean(id) {
        const modalEl = document.getElementById(id);
        if (!modalEl) return;
        const inst = bootstrap.Modal.getInstance(modalEl);
        if (inst) {
            inst.hide();
        } else {
            try { new bootstrap.Modal(modalEl).hide(); } catch(e) {}
        }
        // supprimer tout backdrop résiduel et la classe sur body
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
    }

    // Initialisation
    loadAnimalList();

</script>
</body>
</html>
