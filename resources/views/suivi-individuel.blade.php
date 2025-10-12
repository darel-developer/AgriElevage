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
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{route('reproductive.analytics')}}"><i class="bi bi-activity"></i>Reproduction</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{route('genetique')}}"><i class="bi bi-activity"></i>Génétique</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="#"><i class="bi bi-journal-bookmark"></i>Apprentissage</a></li>
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
          <input type="date" class="form-control" name="date_mise_bas" required>
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
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">Aucun animal trouvé.</td></tr>';
                document.getElementById('animalDetailCol').style.display = 'none';
                return;
            }
            data.forEach(animal => {
                const tr = document.createElement('tr');
                tr.className = 'animal-row';
                tr.dataset.id = animal.id;
                tr.innerHTML = `
                    <td>${animal.nom}</td>
                    <td>${animal.type.charAt(0).toUpperCase() + animal.type.slice(1)}</td>
                    <td>${animal.sexe.charAt(0).toUpperCase() + animal.sexe.slice(1)}</td>
                    <td>${animal.age}</td>
                    <td>${animal.statut || 'En bonne santé'}</td>
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
                    <div><span class="animal-detail-label">Race:</span> <span class="animal-detail-value">${animal.race || '-'}</span></div>
                    <div><span class="animal-detail-label">Date de naissance:</span> <span class="animal-detail-value">${animal.date_naissance}</span></div>
                    <div><span class="animal-detail-label">Identifiant:</span> <span class="animal-detail-value">${animal.identifiant || '-'}</span></div>
                </div>
                <div class="animal-detail-section-title">Lignage</div>
                <div><span class="animal-detail-label">Mère:</span> <span class="animal-detail-value">${animal.mere || '-'}</span></div>
                <div><span class="animal-detail-label">Père:</span> <span class="animal-detail-value">${animal.pere || '-'}</span></div>
                <div><span class="animal-detail-label">Grands-parents:</span> <span class="animal-detail-value">${animal.grands_parents || '-'}</span></div>
                <div class="animal-detail-section-title">Historique</div>
                <div class="animal-detail-history">${animal.historique || '<span class="text-muted">Aucun historique.</span>'}</div>
                <div class="animal-detail-section-title">Actions Rapides</div>
                <div class="animal-detail-actions">
                    ${animal.type === 'Lapin' ? '<a href="{{ route('breeding-planning') }}"><button class="btn btn-outline-success btn-sm">Reproduire</button></a>' : ''}
                    <button class="btn btn-outline-success btn-sm">Traiter</button>
                    <button class="btn btn-outline-secondary btn-sm">Marquer comme vendu</button>
                    <button class="btn btn-outline-success btn-sm">Enregistrer poids</button>
                </div>
            `;
        });
    }

    function openBreedingModal() {
        var modal = new bootstrap.Modal(document.getElementById('breedingModal'));
        modal.show();
    }

    // Ouvre la modale d'ajout de croisement
    document.getElementById('addBreedingBtn').onclick = function() {
        loadBreedingAnimals();
        document.getElementById('breedingForm').reset();
        document.getElementById('breedingFormAlert').innerHTML = '';
        document.getElementById('breedingMiseBas').value = '';
        var modal = new bootstrap.Modal(document.getElementById('breedingModal'));
        modal.show();
    };

    // Charger les animaux pour la modale de croisement
    function loadBreedingAnimals() {
        fetch('/animals?type=lapin', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
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

    // Soumission AJAX du formulaire de croisement
    document.getElementById('breedingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const data = new FormData(form);
        fetch("{{ route('breeding.store') }}", {
            method: "POST",
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            body: data
        })
        .then(res => res.json())
        .then(json => {
            if (json.success) {
                form.reset();
                document.getElementById('breedingFormAlert').innerHTML =
                    '<div class="alert alert-success">Croisement enregistré avec succès !</div>';
                setTimeout(() => {
                    var modal = bootstrap.Modal.getInstance(document.getElementById('breedingModal'));
                    modal.hide();
                }, 1200);
            } else {
                document.getElementById('breedingFormAlert').innerHTML =
                    '<div class="alert alert-danger">Erreur lors de l\'enregistrement.</div>';
            }
        })
        .catch(() => {
            document.getElementById('breedingFormAlert').innerHTML =
                '<div class="alert alert-danger">Erreur lors de l\'enregistrement.</div>';
        });
    });

    // Initialisation
    loadAnimalList();

</script>
</body>
</html>
</html>
        e.preventDefault();
        const form = this;
        const data = new FormData(form);
        fetch("{{ route('breeding.store') }}", {
            method: "POST",
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            body: data
        })
        .then(res => res.json())
        .then(json => {
            if (json.success) {
                form.reset();
                document.getElementById('breedingFormAlert').innerHTML =
                    '<div class="alert alert-success">Croisement enregistré avec succès !</div>';
                setTimeout(() => {
                    var modal = bootstrap.Modal.getInstance(document.getElementById('breedingModal'));
                    modal.hide();
                }, 1200);
            } else {
                document.getElementById('breedingFormAlert').innerHTML =
                    '<div class="alert alert-danger">Erreur lors de l\'enregistrement.</div>';
            }
        })
        .catch(() => {
            document.getElementById('breedingFormAlert').innerHTML =
                '<div class="alert alert-danger">Erreur lors de l\'enregistrement.</div>';
        });
    });

    // Initialisation
    loadAnimalList();

</script>
</body>
</html>
