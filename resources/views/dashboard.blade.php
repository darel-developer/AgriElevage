<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord - AgriElevage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #e6f3e6;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            background: #345c37;
            min-height: 100vh;
            color: #fff;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding-bottom: 2rem;
        }
        .sidebar .nav-link,
        .sidebar .sidebar-title {
            color: #fff;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: #5fc77a;
            color: #345c37;
            font-weight: 500;
        }
        .sidebar .nav-link i {
            margin-right: 12px;
        }
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
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
        }
        .main-content {
            background: #e6f3e6;
            min-height: 100vh;
            margin-left: 240px;
            /* largeur de la sidebar */
            padding-bottom: 2rem;
        }
        .card-box {
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 1px 8px 0 rgba(31, 38, 135, 0.06);
            padding: 1.2rem 1.5rem;
            margin-bottom: 1.2rem;
        }
        .quick-btn {
            background: #5fc77a;
            color: #fff;
            border: none;
            border-radius: 0.7rem;
            padding: 0.7rem 1rem;
            font-weight: 500;
            margin-bottom: 0.8rem;
            width: 100%;
            text-align: left;
            transition: background 0.2s;
        }
        .quick-btn:last-child {
            margin-bottom: 0;
        }
        .quick-btn:hover {
            background: #388e3c;
            color: #fff;
        }
        .alert-icon {
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }
        .calendar-date {
            background: #e6f3e6;
            color: #345c37;
            border-radius: 0.7rem;
            min-width: 48px;
            min-height: 48px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        .calendar-date .month {
            font-size: 0.8rem;
            font-weight: 400;
            text-transform: uppercase;
        }
        .settings-btn {
            background: none;
            border: none;
            color: #345c37;
            font-size: 1.3rem;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                position: static;
                width: 100%;
                min-height: auto;
                padding-bottom: 2rem;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="sidebar">
   
    <div class="sidebar-title fs-5 fw-bold py-4 px-4 d-none d-lg-block">AgriElevage</div>
    <ul class="nav flex-column mb-2 px-2">
        <li class="nav-item mb-2">
            <a class="nav-link active rounded" href="#"><i class="bi bi-house-door"></i>Accueil</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="{{route('suivi.individuel')}}"><i class="bi bi-person-lines-fill"></i>Suivi Individuel</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="{{route('health.diagnostic')}}"><i class="bi bi-heart-pulse"></i>Santé</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="{{route('reproductive-analytics')}}"><i class="bi bi-activity"></i>Reproduction</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="{{route('genetique')}}"><i class="bi bi-activity"></i>Génétique</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="{{route('learning-courses')}}"><i class="bi bi-journal-bookmark"></i>Apprentissage</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="#"><i class="bi bi-people"></i>Communauté</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="{{ route('chatbot') }}"><i class="bi bi-briefcase"></i>Assistant IA</a>
        </li>
    </ul>

     <div class="sidebar-profile mt-2">
        <!--<img src="https://randomuser.me/api/portraits/women/50.jpg" alt="Profil">-->
        <div>
            <div class="fw-bold" style="font-size: 0.95rem;">
                {{ Auth::user()->name ?? 'Utilisateur' }}
            </div>
            <div style="font-size: 0.85rem; color:#5fc77a;">
                {{ Auth::user()->email ?? '' }}
            </div>
        </div>
        <i class="bi bi-chevron-right ms-auto" style="color:#345c37;"></i>
    </div>
</div>
<div class="main-content">
    <div class="container-fluid py-4 px-3 px-lg-5">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="fw-bold mb-0" style="color:#345c37;">Tableau de Bord</h3>
            <div>
                <button class="settings-btn me-2"><i class="bi bi-bell"></i></button>
               <a href="{{route('account')}}"><button class="settings-btn"><i class="bi bi-gear"></i></button></a> 
            </div>
        </div>
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="row g-3">
                    <!-- Résumé des Animaux -->
                    <div class="col-12">
                        <div class="card-box d-flex flex-wrap align-items-center justify-content-between" id="resume-animaux">
                            <div>
                                <div class="fw-bold mb-2" style="color:#345c37;">Résumé des Animaux</div>
                                <div class="d-flex align-items-end gap-4" id="resume-animaux-content">
                                    <div class="text-center">
                                        <div class="fs-2 fw-bold" style="color:#5fc77a;" id="total-animaux">{{ $totalAnimaux ?? 0 }}</div>
                                        <div class="small text-muted">TOTAL ANIMAUX</div>
                                    </div>
                                    @foreach(['poule', 'lapin'] as $type)
                                    <div class="text-center">
                                        <div class="fs-4 fw-bold" style="color:#5fc77a;" id="type-{{ $type }}">{{ $typesCount[$type] ?? 0 }}</div>
                                        <div class="small text-muted text-uppercase">{{ $type }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <a href="{{ route('animals.index') }}" class="btn btn-outline-success ms-auto" style="border-radius:1rem;">Voir tout</a>
                        </div>
                    </div>
                    <!-- Événements à Venir -->
                    <div class="col-12">
                        <div class="card-box">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="fw-bold" style="color:#345c37;">Événements à Venir</div>
                                <a href="{{route('calendar')}}"><button class="btn btn-outline-success btn-sm" style="border-radius:0.7rem;">Calendrier</button></a>
                            </div>
                            <div id="evenements-avenir-list">
                                <div class="text-center text-muted py-2">Chargement...</div>
                            </div>
                        </div>
                    </div>
                    <!-- Accès Rapide -->
                    <div class="col-12">
                        <div class="card-box">
                            <div class="fw-bold mb-3" style="color:#345c37;">Accès Rapide</div>
                            <button class="quick-btn mb-2" data-bs-toggle="modal" data-bs-target="#animalModal">
                                <i class="bi bi-plus-circle me-2"></i>Ajouter un animal
                            </button>
                            <button class="quick-btn mb-2"><i class="bi bi-clipboard2-plus me-2"></i>Nouvelle tâche</button>
                            <a href="{{ route('chatbot') }}"><button class="quick-btn mb-2"><i class="bi bi-chat-dots me-2"></i>Poser une question à l'IA</button></a>
                            <button class="quick-btn mb-2"><i class="bi bi-journal-text me-2"></i>Accéder aux tutoriels</button>
                            <a href="{{route('calendar')}}"><button class="quick-btn mb-2"><i class="bi bi-calendar3 me-2"></i>Voir le calendrier</button></a>
                            <button class="quick-btn"><i class="bi bi-people me-2"></i>Rejoindre la communauté</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Alertes Importantes -->
            <div class="col-lg-4">
                <div class="card-box mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold" style="color:#345c37;">Alertes Importantes</div>
                        <button id="manageAlertsBtn" class="btn btn-outline-success btn-sm" style="border-radius:0.7rem;">Gérer</button>
                    </div>
                    <div id="importantAlerts">
                        <div class="text-center text-muted py-2">Chargement des alertes...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modale pour ajouter un animal -->
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
            <option value="poule">Poule</option>
            <option value="lapin">Lapin</option>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Catégories dynamiques
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
    });

    // Charger les animaux pour la lignée selon le type sélectionné
    document.getElementById('animalType').addEventListener('change', function() {
        const type = this.value;
        // ...catégories dynamiques...
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

    // Soumission AJAX du formulaire
    document.getElementById('animalForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const data = new FormData(form);
        fetch("{{ route('animals.store') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
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

    // Polling pour résumé des animaux
    function updateResumeAnimaux() {
        fetch("{{ route('dashboard') }}?poll=1", {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data && data.total !== undefined) {
                document.getElementById('total-animaux').textContent = data.total;
                document.getElementById('type-poule').textContent = data.poule || 0;
                document.getElementById('type-lapin').textContent = data.lapin || 0;
            }
            // Render alerts if present
            const container = document.getElementById('importantAlerts');
            if (data && Array.isArray(data.alerts)) {
                if (data.alerts.length === 0) {
                    container.innerHTML = '<div class="text-center text-muted py-2">Aucune alerte pour le moment.</div>';
                } else {
                    container.innerHTML = '';
                    data.alerts.forEach(alert => {
                        let color = '#f0ad4e'; // default
                        let icon = 'bi-info-circle';
                        if (alert.priority === 'high') { color = '#e74c3c'; icon = 'bi-exclamation-octagon-fill'; }
                        else if (alert.priority === 'medium') { color = '#f0ad4e'; icon = 'bi-bell-fill'; }
                        else if (alert.priority === 'low') { color = '#5fc77a'; icon = 'bi-check-circle-fill'; }
                        const dateHtml = alert.date ? `<div class="small text-muted">${new Date(alert.date).toLocaleDateString('fr-FR')}</div>` : '';
                        container.innerHTML += `
                            <div class="mb-2 d-flex align-items-start" style="color:${color};">
                                <i class="bi ${icon} alert-icon me-2" style="font-size:1.1rem;"></i>
                                <div>
                                    <div class="fw-bold" style="color:#345c37;">${alert.message}</div>
                                    ${dateHtml}
                                </div>
                            </div>
                        `;
                    });
                }
            }
        })
        .catch(() => {
            // keep existing content on error
        });
    }
    setInterval(updateResumeAnimaux, 2000);

    // Gestion simple du bouton "Gérer" (ouvre page des événements)
    document.getElementById('manageAlertsBtn').addEventListener('click', function() {
        window.location.href = "{{ route('breeding-planning') }}";
    });

    // Affichage dynamique des événements à venir (prochains 7 jours)
    function loadEvenementsAvenir() {
        fetch("{{ route('breeding.events') }}", {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(events => {
            const container = document.getElementById('evenements-avenir-list');
            container.innerHTML = '';
            const today = new Date();
            const in7days = new Date();
            in7days.setDate(today.getDate() + 7);
            // Filtrer les événements dans les 7 prochains jours
            const upcoming = events.filter(ev => {
                const evDate = new Date(ev.date);
                return evDate >= today && evDate <= in7days;
            }).sort((a, b) => new Date(a.date) - new Date(b.date));
            if (upcoming.length === 0) {
                container.innerHTML = '<div class="text-center text-muted py-2">Aucun événement à venir.</div>';
                return;
            }
            upcoming.forEach(ev => {
                const dateObj = new Date(ev.date);
                const day = String(dateObj.getDate()).padStart(2, '0');
                const month = dateObj.toLocaleString('fr-FR', { month: 'short' }).toUpperCase();
                container.innerHTML += `
                    <div class="d-flex align-items-center mb-2">
                        <div class="calendar-date me-3">
                            <div>${day}</div>
                            <div class="month">${month}</div>
                        </div>
                        <div>
                            <div class="fw-bold" style="color:#345c37;">${ev.title}</div>
                            <div class="small text-muted">${dateObj.toLocaleDateString('fr-FR')}</div>
                        </div>
                    </div>
                `;
            });
        });
    }
    loadEvenementsAvenir();
    setInterval(loadEvenementsAvenir, 60000); // rafraîchit toutes les 60s
</script>
</body>
</html>
