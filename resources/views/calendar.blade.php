<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calendrier - AgriElevage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #e6f3e6;
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
            transition: left 0.3s;
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
            padding-bottom: 2rem;
            transition: margin-left 0.3s;
        }
        .calendar-box {
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 1px 8px 0 rgba(31, 38, 135, 0.06);
            padding: 1.5rem 2rem;
            margin-top: 2rem;
        }
        .calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .calendar-table th, .calendar-table td {
            text-align: center;
            vertical-align: middle;
            height: 48px;
            width: 48px;
            font-size: 1.1rem;
        }
        .calendar-table th {
            color: #345c37;
            font-weight: 600;
            background: #e6f3e6;
        }
        .calendar-table td {
            cursor: pointer;
            border-radius: 0.7rem;
            transition: background 0.15s;
        }
        .calendar-table td.today {
            background: #5fc77a;
            color: #fff;
            font-weight: bold;
        }
        .calendar-table td:hover:not(.today):not(.empty) {
            background: #e6f3e6;
        }
        .calendar-table td.empty {
            background: transparent;
            cursor: default;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                left: -240px;
            }
            .sidebar.show {
                left: 0;
            }
            .main-content {
                margin-left: 0;
            }
            .calendar-box {
                padding: 1rem 0.5rem;
            }
        }
        .hamburger {
            display: none;
            position: fixed;
            top: 18px;
            left: 18px;
            z-index: 200;
            background: #5fc77a;
            border: none;
            border-radius: 0.5rem;
            color: #fff;
            font-size: 1.7rem;
            padding: 6px 12px;
        }
        @media (max-width: 991.98px) {
            .hamburger {
                display: block;
            }
        }
    </style>
</head>
<body>
<button class="hamburger" id="sidebarToggle"><i class="bi bi-list"></i></button>
<div class="sidebar" id="sidebar">
   
    <div class="sidebar-title fs-5 fw-bold py-4 px-4 d-none d-lg-block">AgriElevage</div>
    <ul class="nav flex-column mb-2 px-2">
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i>Accueil</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="{{route('suivi.individuel')}}"><i class="bi bi-person-lines-fill"></i>Suivi Individuel</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="#"><i class="bi bi-heart-pulse"></i>Santé</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="#"><i class="bi bi-activity"></i>Génétique</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="#"><i class="bi bi-journal-bookmark"></i>Apprentissage</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="#"><i class="bi bi-people"></i>Communauté</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="{{route('chatbot')}}"><i class="bi bi-briefcase"></i>Assistant IA</a>
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
    <div class="container-fluid">
        <div class="calendar-box mx-auto" style="max-width: 600px;">
            <div class="calendar-header">
                <button class="btn btn-outline-success" id="prevMonth"><i class="bi bi-chevron-left"></i></button>
                <div class="fw-bold fs-4" id="calendarMonth"></div>
                <button class="btn btn-outline-success" id="nextMonth"><i class="bi bi-chevron-right"></i></button>
                <button class="btn btn-success ms-3" id="addEventBtn"><i class="bi bi-plus-circle"></i> Ajouter un évènement</button>
            </div>
            <table class="table calendar-table mb-0">
                <thead>
                    <tr>
                        <th>Lun</th>
                        <th>Mar</th>
                        <th>Mer</th>
                        <th>Jeu</th>
                        <th>Ven</th>
                        <th>Sam</th>
                        <th>Dim</th>
                    </tr>
                </thead>
                <tbody id="calendarBody">
                    <!-- Calendrier JS -->
                </tbody>
            </table>
            <div id="eventDetailsCard" style="display:none;" class="mt-3"></div>
        </div>
    </div>
</div>

<!-- Modale ajout événement -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="eventForm">
      <div class="modal-header">
        <h5 class="modal-title" id="eventModalLabel">Ajouter un évènement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div id="eventFormAlert"></div>
        <div class="mb-3">
          <label class="form-label">Titre</label>
          <input type="text" class="form-control" name="title" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea class="form-control" name="description" rows="2"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Date</label>
          <input type="date" class="form-control" name="date" id="eventDateInput" required>
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
    // Sidebar hamburger
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('show');
    });
    // Fermer le menu si on clique hors sidebar sur mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 991 && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
            sidebar.classList.remove('show');
        }
    });

    // Calendrier JS
    const monthNames = [
        "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
        "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
    ];
    let today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();
    let events = [];
    let customEvents = [];

    // Charger les événements personnalisés
    function loadCustomEvents(callback) {
        fetch("{{ route('events.all') }}")
            .then(res => res.json())
            .then(data => {
                customEvents = data.map(ev => ({
                    date: ev.date,
                    title: ev.title,
                    description: ev.description,
                    type: 'custom'
                }));
                if (callback) callback();
            });
    }

    // Fusionne les événements de mise bas et personnalisés
    function getAllEvents() {
        return [...events, ...customEvents];
    }

    // Initial load
    fetch("{{ route('breeding.events') }}")
        .then(res => res.json())
        .then(data => { events = data; loadCustomEvents(() => renderCalendar(currentMonth, currentYear)); });

    function renderCalendar(month, year) {
        const firstDay = (new Date(year, month, 1).getDay() + 6) % 7; // Lundi = 0
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const calendarBody = document.getElementById('calendarBody');
        const calendarMonth = document.getElementById('calendarMonth');
        calendarBody.innerHTML = "";
        calendarMonth.textContent = `${monthNames[month]} ${year}`;

        let date = 1;
        for (let i = 0; i < 6; i++) {
            let row = document.createElement("tr");
            for (let j = 0; j < 7; j++) {
                let cell = document.createElement("td");
                if (i === 0 && j < firstDay) {
                    cell.classList.add('empty');
                    cell.innerHTML = "";
                } else if (date > daysInMonth) {
                    cell.classList.add('empty');
                    cell.innerHTML = "";
                } else {
                    cell.innerHTML = date;
                    const cellDate = `${year}-${String(month+1).padStart(2,'0')}-${String(date).padStart(2,'0')}`;
                    const allEvents = getAllEvents().filter(ev => ev.date === cellDate);
                    if (allEvents.length > 0) {
                        allEvents.forEach(ev => {
                            cell.innerHTML += `<div style="font-size:0.7rem;color:#5fc77a;cursor:pointer;" class="event-title" data-date="${cellDate}">${ev.title}</div>`;
                        });
                        cell.classList.add('has-event');
                        cell.onclick = function(e) {
                            showEventDetails(cellDate);
                            e.stopPropagation();
                        };
                    } else {
                        cell.onclick = function() {
                            hideEventDetails();
                        };
                    }
                    if (
                        date === today.getDate() &&
                        month === today.getMonth() &&
                        year === today.getFullYear()
                    ) {
                        cell.classList.add('today');
                    }
                    date++;
                }
                row.appendChild(cell);
            }
            calendarBody.appendChild(row);
            if (date > daysInMonth) break;
        }
        hideEventDetails();
    }

    // Navigation mois
    document.getElementById('prevMonth').onclick = function() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar(currentMonth, currentYear);
    };
    document.getElementById('nextMonth').onclick = function() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar(currentMonth, currentYear);
    };

    // Ajouter un évènement
    document.getElementById('addEventBtn').onclick = function() {
        document.getElementById('eventForm').reset();
        document.getElementById('eventFormAlert').innerHTML = '';
        var modal = new bootstrap.Modal(document.getElementById('eventModal'));
        modal.show();
    };

    // Soumission AJAX du formulaire d'ajout d'évènement
    document.getElementById('eventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const data = new FormData(form);
        fetch("{{ route('events.store') }}", {
            method: "POST",
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            body: data
        })
        .then(res => res.json())
        .then(json => {
            if (json.success) {
                form.reset();
                document.getElementById('eventFormAlert').innerHTML =
                    '<div class="alert alert-success">Évènement ajouté avec succès !</div>';
                loadCustomEvents(() => renderCalendar(currentMonth, currentYear));
                setTimeout(() => {
                    var modal = bootstrap.Modal.getInstance(document.getElementById('eventModal'));
                    modal.hide();
                    document.getElementById('eventFormAlert').innerHTML = '';
                }, 1200);
            } else {
                document.getElementById('eventFormAlert').innerHTML =
                    '<div class="alert alert-danger">Erreur lors de l\'ajout.</div>';
            }
        })
        .catch(() => {
            document.getElementById('eventFormAlert').innerHTML =
                '<div class="alert alert-danger">Erreur lors de l\'ajout.</div>';
        });
    });

    // Affichage des détails d'évènement (corrigé pour inclure tous les types d'événements)
    function showEventDetails(date) {
        const allEvents = getAllEvents().filter(ev => ev.date === date);
        let html = '';
        if (allEvents.length === 0) {
            html = '<div class="alert alert-info">Aucun évènement ce jour.</div>';
        } else {
            html = allEvents.map(ev => `
                <div class="card mb-3" style="border-radius:1.2rem;box-shadow:0 1px 8px 0 rgba(31,38,135,0.06);">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3" style="background:#e6f3e6;color:#345c37;border-radius:0.7rem;min-width:48px;min-height:48px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:600;">
                                <div style="font-size:1.3rem;">${new Date(ev.date).getDate()}</div>
                                <div class="month" style="font-size:0.8rem;font-weight:400;text-transform:uppercase;">${new Date(ev.date).toLocaleString('fr-FR', { month: 'short' })}</div>
                            </div>
                            <div>
                                <div class="fw-bold" style="color:#345c37;font-size:1.1rem;">${ev.title}</div>
                                <div class="small text-muted">${new Date(ev.date).toLocaleDateString('fr-FR')}</div>
                            </div>
                        </div>
                        <div class="mt-2" style="color:#345c37;">
                            ${ev.description ? `<div>${ev.description}</div>` : ''}
                        </div>
                    </div>
                </div>
            `).join('');
        }
        document.getElementById('eventDetailsCard').innerHTML = html;
        document.getElementById('eventDetailsCard').style.display = '';
    }

    function hideEventDetails() {
        document.getElementById('eventDetailsCard').style.display = 'none';
        document.getElementById('eventDetailsCard').innerHTML = '';
    }

    renderCalendar(currentMonth, currentYear);
</script>
</body>
</html>
