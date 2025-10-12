<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Planifier un Croisement - AgriElevage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .breeding-card {
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 1px 8px 0 rgba(31, 38, 135, 0.06);
            padding: 2rem 2.5rem;
            max-width: 480px;
            margin: 2rem auto;
        }
        .form-label { color: #345c37; font-weight: 600; }
        .form-control, .form-select {
            border-radius: 0.7rem;
            border: 1px solid #b2d8b2;
        }
        .btn-success {
            background: #5fc77a;
            border: none;
            border-radius: 0.7rem;
            font-weight: 600;
            padding: 0.7rem 2.5rem;
        }
        .btn-success:hover { background: #388e3c; }
        .form-check-input:checked { background-color: #5fc77a; border-color: #5fc77a; }
        .prediction-label { color: #5fc77a; font-weight: 600; }
        .prediction-value { color: #5fc77a; }
        @media (max-width: 991.98px) {
            .sidebar { position: static; width: 100%; min-height: auto; }
            .main-content { margin-left: 0; padding: 1rem 0.2rem; }
            .breeding-card { padding: 1.2rem 0.5rem; }
        }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="sidebar-title fs-5 fw-bold py-4 px-4 d-none d-lg-block">AgriElevage</div>
    <ul class="nav flex-column mb-2 px-2">
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i>Accueil</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{ route('suivi.individuel') }}"><i class="bi bi-person-lines-fill"></i>Suivi Individuel</a></li>
        <li class="nav-item mb-2"><a class="nav-link active rounded" href="#"><i class="bi bi-activity"></i>Reproduction</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="#"><i class="bi bi-heart-pulse"></i>Santé</a></li>
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
    <div class="container">
        <h3 class="fw-bold mb-4" style="color:#345c37;">Planifier un Croisement</h3>
        <div class="breeding-card">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('breeding.store') }}" id="breedingForm">
                @csrf
                <div class="mb-4">
                    <div class="fw-bold mb-2" style="color:#345c37;">Sélection des Animaux</div>
                    <label class="form-label">Mâle:</label>
                    <select class="form-select mb-3" name="male_id" id="maleSelect" required>
                        <option value="">Sélectionner un mâle</option>
                        @foreach($males as $male)
                            <option value="{{ $male->id }}">{{ $male->nom }}</option>
                        @endforeach
                    </select>
                    <label class="form-label">Femelle:</label>
                    <select class="form-select" name="female_id" id="femaleSelect" required>
                        <option value="">Sélectionner une femelle</option>
                        @foreach($femelles as $femelle)
                            <option value="{{ $femelle->id }}">{{ $femelle->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <div class="fw-bold mb-2" style="color:#345c37;">Date et Heure du Croisement</div>
                    <label class="form-label">Date:</label>
                    <input type="date" class="form-control mb-3" name="date_croisement" id="dateCroisement" required>
                    <label class="form-label">Heure:</label>
                    <div class="form-check form-check-inline ms-2">
                        <input class="form-check-input" type="radio" name="heure" id="heureMatin" value="Matin" checked>
                        <label class="form-check-label" for="heureMatin">Matin</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="heure" id="heureSoir" value="Soir">
                        <label class="form-check-label" for="heureSoir">Soir</label>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="fw-bold mb-2" style="color:#345c37;">Prévisions</div>
                    <div class="prediction-label">Date de mise bas prévue:</div>
                    <div class="prediction-value" id="dateMiseBasPreview" style="font-size:1.1rem;">
                        Calculée automatiquement après sélection
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-success">Valider le Croisement</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Calcul automatique de la date de mise bas (30 jours après croisement)
    document.getElementById('dateCroisement').addEventListener('change', function() {
        updateMiseBas();
    });
    function updateMiseBas() {
        const dateStr = document.getElementById('dateCroisement').value;
        if (!dateStr) {
            document.getElementById('dateMiseBasPreview').textContent = "Calculée automatiquement après sélection";
            return;
        }
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) {
            document.getElementById('dateMiseBasPreview').textContent = "Date invalide";
            return;
        }
        const miseBas = new Date(date);
        miseBas.setDate(miseBas.getDate() + 30);
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('dateMiseBasPreview').textContent = miseBas.toLocaleDateString('fr-FR', options);
    }
</script>
</body>
</html>
