<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Génétique - AgriElevage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #e6f3e6; }
        .sidebar {
            background: #345c37;
            min-height: 100vh;
            color: #fff;
            width: 240px;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: left 0.3s;
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
        .main-content {
            margin-left: 240px;
            min-height: 100vh;
            background: #e6f3e6;
            padding: 2rem 0;
        }
        .genetic-card {
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 1px 8px 0 rgba(31, 38, 135, 0.06);
            padding: 2rem 2.5rem;
            max-width: 900px;
            margin: 2rem auto;
        }
        .genetic-title {
            color: #345c37;
            font-weight: 700;
        }
        .genetic-section-title {
            color: #5fc77a;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.7rem;
        }
        .genetic-search {
            border-radius: 1rem;
            border: 1px solid #b2d8b2;
            padding: 0.7rem 1.2rem;
            margin-bottom: 1.5rem;
            width: 100%;
            max-width: 350px;
        }
        .genetic-table th, .genetic-table td {
            background: none;
            border: none;
            vertical-align: middle;
        }
        .genetic-table th {
            color: #5fc77a;
            font-weight: 700;
            background: #e6f3e6;
        }
        .genetic-table tr {
            border-radius: 0.7rem;
        }
        .genetic-table tr:hover {
            background: #e6f3e6;
        }
        .tree-container {
            background: #f7fbf7;
            border-radius: 1rem;
            padding: 1.2rem;
            margin-top: 1.5rem;
        }
        .tree-title {
            color: #345c37;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .tree {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .tree-level {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1rem;
        }
        .tree-node {
            background: #fff;
            border: 1.5px solid #5fc77a;
            border-radius: 0.7rem;
            padding: 0.5rem 1.2rem;
            color: #345c37;
            font-weight: 500;
            min-width: 120px;
            text-align: center;
        }
        @media (max-width: 991.98px) {
            .sidebar { left: -240px; }
            .sidebar.show { left: 0; }
            .main-content { margin-left: 0; padding: 1rem 0.2rem; }
            .genetic-card { padding: 1.2rem 0.5rem; }
        }
    </style>
</head>
<body>
<button class="hamburger d-lg-none" id="sidebarToggle" style="display:none;"><i class="bi bi-list"></i></button>
<div class="sidebar" id="sidebar">
    <div class="sidebar-title fs-5 fw-bold py-4 px-4 d-none d-lg-block">AgriElevage</div>
    <ul class="nav flex-column mb-2 px-2">
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i>Accueil</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{ route('suivi.individuel') }}"><i class="bi bi-person-lines-fill"></i>Suivi Individuel</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{ route('breeding-planning') }}"><i class="bi bi-activity"></i>Reproduction</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{ route('health.diagnostic') }}"><i class="bi bi-heart-pulse"></i>Santé</a></li>
        <li class="nav-item mb-2"><a class="nav-link active rounded" href="#"><i class="bi bi-activity"></i>Génétique</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="#"><i class="bi bi-journal-bookmark"></i>Apprentissage</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="#"><i class="bi bi-people"></i>Communauté</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{ route('chatbot') }}"><i class="bi bi-briefcase"></i>Assistant IA</a></li>
    </ul>
    <div class="sidebar-profile mt-auto mb-3">
        <div>
            <div class="fw-bold" style="font-size: 0.95rem;">{{ Auth::user()->name ?? 'Utilisateur' }}</div>
            <div style="font-size: 0.85rem; color:#5fc77a;">{{ Auth::user()->email ?? '' }}</div>
        </div>
    </div>
</div>
<div class="main-content">
    <div class="container">
        <h3 class="genetic-title mb-4">Génétique & Lignées</h3>
        <div class="genetic-card">
            <div class="genetic-section-title">Recherche d'un animal</div>
            <input type="text" class="genetic-search" id="searchAnimal" placeholder="Nom ou identifiant...">
            <div class="genetic-section-title">Liste des animaux</div>
            <div class="table-responsive">
                <table class="table genetic-table mb-0" id="geneticTable">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Espèce</th>
                            <th>Sexe</th>
                            <th>Catégorie</th>
                            <th>Identifiant</th>
                            <th>Lignée</th>
                        </tr>
                    </thead>
                    <tbody id="geneticTableBody">
                        <!-- Rempli dynamiquement -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lignée Génétique -->
<div class="modal fade" id="treeModal" tabindex="-1" aria-labelledby="treeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="treeModalLabel">Lignée Génétique</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div class="tree" id="geneticTreeModal">
          <!-- Rempli dynamiquement -->
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar hamburger responsive
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.style.display = 'block';
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 991 && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
                sidebar.classList.remove('show');
            }
        });
    }

    // Chargement dynamique des animaux (AJAX)
    let allAnimals = [];
    function loadAnimals() {
        fetch("{{ route('animals.index') }}", { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            allAnimals = data;
            renderAnimalTable(data);
        });
    }
    function renderAnimalTable(data) {
        const tbody = document.getElementById('geneticTableBody');
        tbody.innerHTML = '';
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">Aucun animal trouvé.</td></tr>';
            return;
        }
        data.forEach(animal => {
            tbody.innerHTML += `
                <tr>
                    <td>${animal.nom}</td>
                    <td>${animal.type}</td>
                    <td>${animal.sexe}</td>
                    <td>${animal.categorie || '-'}</td>
                    <td>${animal.identifiant || '-'}</td>
                    <td>
                        <button class="btn btn-outline-success btn-sm" onclick="showTree('${animal.id}', '${animal.nom}')">
                            Voir lignée
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    document.getElementById('searchAnimal').addEventListener('input', function() {
        const val = this.value.toLowerCase();
        const filtered = allAnimals.filter(a =>
            (a.nom && a.nom.toLowerCase().includes(val)) ||
            (a.identifiant && a.identifiant.toLowerCase().includes(val))
        );
        renderAnimalTable(filtered);
    });

    // Affichage de la lignée génétique dans une modale
    function showTree(animalId, animalNom) {
        fetch(`/animals/${animalId}/details`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(animal => {
            let html = `
                <div class="tree-level">
                    <div class="tree-node">${animal.nom} <br><span style="font-size:0.9em;color:#5fc77a;">(Animal sélectionné)</span></div>
                </div>
                <div class="tree-level">
                    <div class="tree-node">${animal.mere || '-' }<br><span style="font-size:0.9em;">Mère</span></div>
                    <div class="tree-node">${animal.pere || '-' }<br><span style="font-size:0.9em;">Père</span></div>
                </div>
                <div class="tree-level">
                    <div class="tree-node">${animal.grands_parents ? animal.grands_parents : '-' }<br><span style="font-size:0.9em;">Grands-parents</span></div>
                </div>
            `;
            document.getElementById('geneticTreeModal').innerHTML = html;
            document.getElementById('treeModalLabel').textContent = `Lignée génétique de ${animal.nom}`;
            var modal = new bootstrap.Modal(document.getElementById('treeModal'));
            modal.show();
        });
    }

    loadAnimals();
</script>
</body>
</html>
