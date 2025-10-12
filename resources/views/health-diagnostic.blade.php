<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Diagnostic Santé - AgriElevage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #e6f3e6; }
        .sidebar {
            background: #2d4c2e;
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
            background: #7ed957;
            color: #2d4c2e;
            font-weight: 500;
        }
        .sidebar .nav-link i { margin-right: 12px; }
        .sidebar-profile {
            background: #e6f3e6;
            color: #2d4c2e;
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
            margin-left: 240px;
            min-height: 100vh;
            background: #e6f3e6;
            padding: 2rem 0;
        }
        .diagnostic-card {
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 1px 8px 0 rgba(31, 38, 135, 0.06);
            padding: 2.5rem 2.5rem 2rem 2.5rem;
            max-width: 900px;
            margin: 2rem auto;
        }
        .diagnostic-card h5 {
            font-weight: 700;
            color: #2d4c2e;
        }
        .diagnostic-card .desc {
            color: #7ed957;
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }
        .symptom-section-title {
            font-weight: 600;
            color: #2d4c2e;
            margin-top: 1.5rem;
            margin-bottom: 0.7rem;
        }
        .symptom-btn {
            border: 1.5px solid #b2d8b2;
            background: #f7fbf7;
            color: #2d4c2e;
            border-radius: 1rem;
            margin: 0.3rem 0.5rem 0.3rem 0;
            padding: 0.5rem 1.2rem;
            font-size: 1rem;
            font-weight: 500;
            transition: background 0.15s, color 0.15s, border 0.15s;
        }
        .symptom-btn.selected, .symptom-btn:active {
            background: #7ed957;
            color: #fff;
            border-color: #7ed957;
        }
        .diagnostic-btn {
            background: #43b649;
            color: #fff;
            border: none;
            border-radius: 1rem;
            font-weight: 600;
            padding: 0.7rem 2.5rem;
            margin-top: 2rem;
            float: right;
        }
        .diagnostic-btn:hover { background: #2d4c2e; }
        .hamburger {
            display: none;
            position: fixed;
            top: 18px;
            left: 18px;
            z-index: 200;
            background: #7ed957;
            border: none;
            border-radius: 0.5rem;
            color: #fff;
            font-size: 1.7rem;
            padding: 6px 12px;
        }
        @media (max-width: 991.98px) {
            .sidebar { left: -240px; }
            .sidebar.show { left: 0; }
            .main-content { margin-left: 0; padding: 1rem 0.2rem; }
            .diagnostic-card { padding: 1.2rem 0.5rem; }
            .hamburger { display: block; }
        }
    </style>
</head>
<body>
<button class="hamburger" id="sidebarToggle"><i class="bi bi-list"></i></button>
<div class="sidebar" id="sidebar">
    <div class="sidebar-title fs-5 fw-bold py-4 px-4 d-none d-lg-block">AgriElevage</div>
    <ul class="nav flex-column mb-2 px-2">
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{route('dashboard')}}"><i class="bi bi-house-door"></i>Accueil</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{route('suivi.individuel')}}"><i class="bi bi-person-lines-fill"></i>Suivi Individuel</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="#"><i class="bi bi-activity"></i>Reproduction</a></li>
        <li class="nav-item mb-2"><a class="nav-link active rounded" href="#"><i class="bi bi-heart-pulse"></i>Santé</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{route('genetique')}}"><i class="bi bi-activity"></i>Génétique</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="#"><i class="bi bi-journal-bookmark"></i>Apprentissage</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="#"><i class="bi bi-people"></i>Communauté</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{route('chatbot')}}"><i class="bi bi-briefcase"></i>Assistant IA</a></li>
    </ul>
    <div class="sidebar-profile mt-auto mb-3">
        <!--<img src="https://randomuser.me/api/portraits/women/50.jpg" alt="Profil">-->
        <div>
            <div class="fw-bold" style="font-size: 0.95rem;">{{ Auth::user()->name ?? 'Mon Profil' }}</div>
            <div style="font-size: 0.85rem; color:#7ed957;">{{ Auth::user()->email ?? 'Connectez-vous' }}</div>
        </div>
    </div>
</div>
<div class="main-content">
    <div class="container">
        <h3 class="fw-bold mb-4" style="color:#2d4c2e;">Diagnostic Santé</h3>
        <div class="diagnostic-card">
            <h5>Vérificateur de Symptômes</h5>
            <div class="desc mb-3">
                Sélectionnez les symptômes observés chez votre animal pour obtenir un diagnostic préliminaire et des recommandations.
            </div>
            <form id="diagnosticForm">
                <div class="symptom-section-title">Symptômes Généraux</div>
                <div>
                    <button type="button" class="symptom-btn" data-symptom="Fièvre">Fièvre</button>
                    <button type="button" class="symptom-btn" data-symptom="Léthargie">Léthargie</button>
                    <button type="button" class="symptom-btn" data-symptom="Perte d'appétit">Perte d'appétit</button>
                    <button type="button" class="symptom-btn" data-symptom="Perte de poids">Perte de poids</button>
                </div>
                <div class="symptom-section-title">Symptômes Digestifs</div>
                <div>
                    <button type="button" class="symptom-btn" data-symptom="Vomissements">Vomissements</button>
                    <button type="button" class="symptom-btn" data-symptom="Diarrhée">Diarrhée</button>
                    <button type="button" class="symptom-btn" data-symptom="Constipation">Constipation</button>
                    <button type="button" class="symptom-btn" data-symptom="Ballonnements">Ballonnements</button>
                </div>
                <div class="symptom-section-title">Symptômes Respiratoires</div>
                <div>
                    <button type="button" class="symptom-btn" data-symptom="Toux">Toux</button>
                    <button type="button" class="symptom-btn" data-symptom="Éternuements">Éternuements</button>
                    <button type="button" class="symptom-btn" data-symptom="Difficulté à respirer">Difficulté à respirer</button>
                    <button type="button" class="symptom-btn" data-symptom="Écoulement nasal">Écoulement nasal</button>
                </div>
                <div class="symptom-section-title">Symptômes Cutanés</div>
                <div>
                    <button type="button" class="symptom-btn" data-symptom="Démangeaisons">Démangeaisons</button>
                    <button type="button" class="symptom-btn" data-symptom="Rougeurs">Rougeurs</button>
                    <button type="button" class="symptom-btn" data-symptom="Perte de poils">Perte de poils</button>
                    <button type="button" class="symptom-btn" data-symptom="Lésions cutanées">Lésions cutanées</button>
                </div>
                <div class="symptom-section-title">Symptômes Comportementaux</div>
                <div>
                    <button type="button" class="symptom-btn" data-symptom="Agressivité">Agressivité</button>
                    <button type="button" class="symptom-btn" data-symptom="Anxiété">Anxiété</button>
                    <button type="button" class="symptom-btn" data-symptom="Abattement">Abattement</button>
                    <button type="button" class="symptom-btn" data-symptom="Changement d'habitudes">Changement d'habitudes</button>
                    <button type="button" class="symptom-btn" data-symptom="Boiterie">Boiterie</button>
                </div>
                <button type="submit" class="diagnostic-btn">Calculer le Diagnostic</button>
            </form>
        </div>
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
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 991 && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
            sidebar.classList.remove('show');
        }
    });

    // Sélection des symptômes
    document.querySelectorAll('.symptom-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            btn.classList.toggle('selected');
        });
    });

    // Soumission du diagnostic (à compléter côté backend si besoin)
    document.getElementById('diagnosticForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const selected = Array.from(document.querySelectorAll('.symptom-btn.selected')).map(b => b.dataset.symptom);
        alert("Symptômes sélectionnés :\n" + selected.join(', '));
        // Ici, envoyer les symptômes au backend pour diagnostic si besoin
    });
</script>
</body>
</html>
