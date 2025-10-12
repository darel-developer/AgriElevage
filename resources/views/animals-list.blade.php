<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Animaux - AgriElevage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#e6f3e6;">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold" style="color:#345c37;">Tous les animaux</h3>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-success">Retour au dashboard</a>
    </div>
    <div class="card p-3">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Sexe</th>
                        <th>Type</th>
                        <th>Catégorie</th>
                        <th>Date de naissance</th>
                        <th>Créé le</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($animaux as $animal)
                    <tr data-animal='@json($animal)'>
                        <td>{{ $animal->id }}</td>
                        <td>{{ $animal->nom }}</td>
                        <td>{{ ucfirst($animal->sexe) }}</td>
                        <td>{{ ucfirst($animal->type) }}</td>
                        <td>{{ ucfirst($animal->categorie) }}</td>
                        <td>{{ $animal->date_naissance }}</td>
                        <td>{{ $animal->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary me-1 btn-edit" data-id="{{ $animal->id }}">Modifier</button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $animal->id }}">Supprimer</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Aucun animal enregistré.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Suppression -->
<div class="modal fade" id="deleteAnimalModal" tabindex="-1" aria-labelledby="deleteAnimalModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="deleteAnimalForm">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteAnimalModalLabel">Supprimer l'animal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="deleteAnimalId">
        <p>Êtes-vous sûr de vouloir supprimer cet animal ?</p>
        <div id="deleteAnimalName" class="fw-bold"></div>
        <div id="deleteAnimalAlert"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-danger">Supprimer</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Modification -->
<div class="modal fade" id="editAnimalModal" tabindex="-1" aria-labelledby="editAnimalModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="editAnimalForm">
      <div class="modal-header">
        <h5 class="modal-title" id="editAnimalModalLabel">Modifier l'animal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="editAnimalId">
        <div id="editAnimalAlert"></div>
        <div class="mb-3">
          <label class="form-label">Nom de l'animal</label>
          <input type="text" class="form-control" name="nom" id="editAnimalNom" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Sexe</label>
          <select class="form-select" name="sexe" id="editAnimalSexe" required>
            <option value="">Choisir...</option>
            <option value="male">Mâle</option>
            <option value="femelle">Femelle</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Type d'animal</label>
          <select class="form-select" name="type" id="editAnimalType" required>
            <option value="">Choisir...</option>
            <option value="poule">Poule</option>
            <option value="lapin">Lapin</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Catégorie</label>
          <select class="form-select" name="categorie" id="editAnimalCategorie" required>
            <option value="">Choisir le type d'abord</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Date de naissance</label>
          <input type="date" class="form-control" name="date_naissance" id="editAnimalDateNaissance" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-success">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Catégories dynamiques pour modification
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
    };

    // Ouvrir la modale de suppression
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const tr = btn.closest('tr');
            const animal = JSON.parse(tr.dataset.animal);
            document.getElementById('deleteAnimalId').value = animal.id;
            document.getElementById('deleteAnimalName').textContent = animal.nom + " (" + animal.type + ")";
            document.getElementById('deleteAnimalAlert').innerHTML = '';
            new bootstrap.Modal(document.getElementById('deleteAnimalModal')).show();
        });
    });

    // Soumission suppression AJAX
    document.getElementById('deleteAnimalForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('deleteAnimalId').value;
        fetch(`/animals/${id}`, {
            method: "DELETE",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        })
        .then(res => res.json())
        .then(json => {
            if (json.success) {
                location.reload();
            } else {
                document.getElementById('deleteAnimalAlert').innerHTML =
                    '<div class="alert alert-danger">Erreur lors de la suppression.</div>';
            }
        })
        .catch(() => {
            document.getElementById('deleteAnimalAlert').innerHTML =
                '<div class="alert alert-danger">Erreur lors de la suppression.</div>';
        });
    });

    // Ouvrir la modale de modification
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const tr = btn.closest('tr');
            const animal = JSON.parse(tr.dataset.animal);
            document.getElementById('editAnimalId').value = animal.id;
            document.getElementById('editAnimalNom').value = animal.nom;
            document.getElementById('editAnimalSexe').value = animal.sexe;
            document.getElementById('editAnimalType').value = animal.type;
            // Catégories dynamiques
            const catSelect = document.getElementById('editAnimalCategorie');
            catSelect.innerHTML = '<option value="">Choisir...</option>';
            if (categories[animal.type]) {
                categories[animal.type].forEach(cat => {
                    const opt = document.createElement('option');
                    opt.value = cat.value;
                    opt.textContent = cat.label;
                    catSelect.appendChild(opt);
                });
            }
            catSelect.value = animal.categorie;
            document.getElementById('editAnimalDateNaissance').value = animal.date_naissance;
            document.getElementById('editAnimalAlert').innerHTML = '';
            new bootstrap.Modal(document.getElementById('editAnimalModal')).show();
        });
    });

    // Changement de type dans la modale modification
    document.getElementById('editAnimalType').addEventListener('change', function() {
        const type = this.value;
        const catSelect = document.getElementById('editAnimalCategorie');
        catSelect.innerHTML = '<option value="">Choisir...</option>';
        if (categories[type]) {
            categories[type].forEach(cat => {
                const opt = document.createElement('option');
                opt.value = cat.value;
                opt.textContent = cat.label;
                catSelect.appendChild(opt);
            });
        }
    });

    // Soumission modification AJAX
    document.getElementById('editAnimalForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('editAnimalId').value;
        const form = this;
        const data = new FormData(form);
        fetch(`/animals/${id}`, {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'X-HTTP-Method-Override': 'PUT'
            },
            body: data
        })
        .then(res => res.json())
        .then(json => {
            if (json.success) {
                location.reload();
            } else {
                document.getElementById('editAnimalAlert').innerHTML =
                    '<div class="alert alert-danger">Erreur lors de la modification.</div>';
            }
        })
        .catch(() => {
            document.getElementById('editAnimalAlert').innerHTML =
                '<div class="alert alert-danger">Erreur lors de la modification.</div>';
        });
    });
</script>
</body>
</html>
