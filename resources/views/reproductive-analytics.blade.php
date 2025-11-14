<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Analyse des Performances Reproductives</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .analytics-title {
            color: #345c37;
            font-weight: 700;
        }
        .analytics-filter-box {
            background: #f7fbf7;
            border-radius: 1rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 1rem;
        }
        .analytics-card {
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 1px 8px 0 rgba(31, 38, 135, 0.06);
            padding: 1.2rem 1.5rem;
            min-width: 220px;
            min-height: 120px;
            margin-bottom: 1.2rem;
        }
        .analytics-card .main-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: #345c37;
        }
        .analytics-card .main-label {
            font-size: 1.1rem;
            color: #5fc77a;
            font-weight: 600;
        }
        .analytics-card .trend-up { color: #4caf50; font-size: 1rem; }
        .analytics-card .trend-down { color: #e74c3c; font-size: 1rem; }
        .analytics-card .trend-stable { color: #888; font-size: 1rem; }
        .analytics-progress {
            background: #e6f3e6;
            border-radius: 0.7rem;
            height: 12px;
            margin-top: 0.5rem;
        }
        .analytics-progress-bar {
            background: #5fc77a;
            border-radius: 0.7rem;
            height: 100%;
        }
        .chart-card {
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 1px 8px 0 rgba(31, 38, 135, 0.06);
            padding: 1.2rem 1.5rem;
            min-height: 320px;
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
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{ route('suivi.individuel') }}"><i class="bi bi-person-lines-fill"></i>Suivi Individuel</a></li>
        <li class="nav-item mb-2"><a class="nav-link active rounded" href="#"><i class="bi bi-activity"></i>Reproduction</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{ route('health.diagnostic') }}"><i class="bi bi-heart-pulse"></i>Santé</a></li>
        <li class="nav-item mb-2"><a class="nav-link rounded" href="{{ route('genetique') }}"><i class="bi bi-activity"></i>Génétique</a></li>
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
    <div class="container-fluid">
        <h2 class="analytics-title mb-4">Analyse des Performances Reproductives</h2>
        <form method="GET" class="analytics-filter-box" id="analyticsFilterForm">
            <label class="form-label mb-0 me-2">Espèce:</label>
            <select class="form-select" name="espece" style="max-width:160px;">
                <option value="">Toutes</option>
                @foreach($especes as $esp)
                    <option value="{{ $esp }}" @if($esp == $espece) selected @endif>{{ ucfirst($esp) }}</option>
                @endforeach
            </select>
            <label class="form-label mb-0 ms-3 me-2">Période:</label>
            <select class="form-select" name="periode" style="max-width:180px;">
                <option value="30" @if($periode==30) selected @endif>30 derniers jours</option>
                <option value="90" @if($periode==90) selected @endif>3 derniers mois</option>
                <option value="365" @if($periode==365) selected @endif>12 derniers mois</option>
            </select>
            <button class="btn btn-success ms-3" type="submit"><i class="bi bi-funnel"></i> Appliquer</button>
        </form>
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="analytics-card">
                    <div class="main-label">Accouplements Réussis</div>
                    <div class="main-value">{{ $totalAccouplements }}</div>
                    <div>
                        @if(!is_null($variationAccouplements))
                            @if($variationAccouplements >= 0)
                                <span class="trend-up">↑ +{{ $variationAccouplements }}% vs mois dernier</span>
                            @else
                                <span class="trend-down">↓ {{ $variationAccouplements }}% vs mois dernier</span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="analytics-card">
                    <div class="main-label">Taille Moyenne Portée</div>
                    <div class="main-value">{{ $tailleMoyennePortee }}</div>
                    <div>
                        @if(!is_null($variationPortee))
                            @if($variationPortee >= 0)
                                <span class="trend-up">↑ +{{ $variationPortee }} vs année dernière</span>
                            @else
                                <span class="trend-down">↓ {{ $variationPortee }} vs année dernière</span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="analytics-card">
                    <div class="main-label">Taux de Mortalité</div>
                    <div class="main-value" style="color:#e74c3c;">{{ $tauxMortalite }}%</div>
                    <div>
                        @if(!is_null($variationMortalite))
                            @if($variationMortalite >= 0)
                                <span class="trend-down">↓ {{ $variationMortalite }}% vs mois dernier</span>
                            @else
                                <span class="trend-up">↑ +{{ abs($variationMortalite) }}% vs mois dernier</span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="analytics-card">
                    <div class="main-label">Durée Moyenne Gestation</div>
                    <div class="main-value">{{ $dureeMoyenneGestation }} Jours</div>
                    <div class="trend-stable">→ Stable</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="analytics-card">
                    <div class="main-label">Taux de Réussite Gestations</div>
                    <div class="main-value">{{ $tauxReussite }}%</div>
                    <div class="analytics-progress mt-2">
                        <div class="analytics-progress-bar" style="width:{{ $tauxReussite }}%"></div>
                    </div>
                    <div class="small text-muted mt-1">{{ $tauxReussite }}% des gestations réussies</div>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="chart-card">
                    <div class="fw-bold mb-2" style="color:#345c37;">Accouplements Réussis par Mois</div>
                    <canvas id="accouplementsChart" height="120"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-card">
                    <div class="fw-bold mb-2" style="color:#345c37;">Taille de Portée par Espèce</div>
                    <canvas id="porteeChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Account modal (same markup) -->
<div class="modal fade" id="accountModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog"> <form class="modal-content" id="accountForm">
		<div class="modal-header"><h5 class="modal-title">Paramètres du compte</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
		<div class="modal-body">
			<div id="accountAlert"></div>
			<div class="mb-3"><label class="form-label">Nom</label><input id="accountName" name="name" class="form-control" required></div>
			<div class="mb-3"><label class="form-label">Email</label><input id="accountEmail" name="email" type="email" class="form-control" required></div>
			<div class="mb-3"><button id="sendResetBtn" type="button" class="btn btn-outline-danger">Modifier le mot de passe (envoyer lien par email)</button></div>
		</div>
		<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button><button type="submit" class="btn btn-success">Enregistrer</button></div>
	</form></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Accouplements par mois
    const accMonths = @json($accouplementsParMois->pluck('mois'));
    const accValues = @json($accouplementsParMois->pluck('total'));
    new Chart(document.getElementById('accouplementsChart'), {
        type: 'bar',
        data: {
            labels: accMonths,
            datasets: [{
                label: 'Accouplements',
                data: accValues,
                backgroundColor: '#5fc77a'
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Taille de portée par espèce
    const porteeLabels = @json($tailleParEspece->pluck('espece'));
    const porteeValues = @json($tailleParEspece->pluck('moyenne'));
    new Chart(document.getElementById('porteeChart'), {
        type: 'bar',
        data: {
            labels: porteeLabels,
            datasets: [{
                label: 'Taille moyenne portée',
                data: porteeValues,
                backgroundColor: '#345c37'
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // account modal behaviour (same as others)
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

	const currentUser = { name: {!! json_encode(Auth::user()->name ?? '') !!}, email: {!! json_encode(Auth::user()->email ?? '') !!}, photo: {!! json_encode(Auth::user()->profile_photo_url ?? null) !!} };

	profile.style.cursor = 'pointer';
	profile.addEventListener('click', () => { accountAlert.innerHTML=''; accountName.value=currentUser.name; accountEmail.value=currentUser.email; accountModal.show(); });

	accountForm.addEventListener('submit', function(e){ e.preventDefault();
		accountAlert.innerHTML='';
		const fd=new FormData(accountForm); fd.append('_token','{{ csrf_token() }}');
		fetch("{{ route('account.update') }}",{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest'},body:fd})
		.then(async res=>{
			const json=await res.json().catch(()=>null);
			if(!res.ok) throw json||new Error('Erreur');
+			currentUser.name = json.user.name ?? currentUser.name;
+			currentUser.email = json.user.email ?? currentUser.email;
+			currentUser.photo = json.user.avatar ?? json.user.photo ?? json.user.profile_photo_url ?? currentUser.photo;
+			document.querySelectorAll('.sidebar-profile').forEach(sp=>{
+				const nameEl = sp.querySelector('.fw-bold') || sp.querySelector('div > .fw-bold');
+				if (nameEl) nameEl.textContent = currentUser.name;
+				const em = sp.querySelector('div > div.small, div > div:nth-child(2), .profile-email');
+				if (em) em.textContent = currentUser.email;
+				const img = sp.querySelector('img');
+				if (img && currentUser.photo) img.src = currentUser.photo;
+			});
+			accountAlert.innerHTML='<div class="alert alert-success">Profil mis à jour.</div>';
+			setTimeout(()=>accountModal.hide(),900);
		}).catch(async err=>{ let msg='Erreur lors de la mise à jour.'; if(err && err.errors) msg=Object.values(err.errors).flat().join('<br>'); accountAlert.innerHTML=`<div class="alert alert-danger">${msg}</div>`; });

	sendResetBtn.addEventListener('click', function(){ if(!confirm("Vous allez recevoir un email contenant un lien pour mettre à jour votre mot de passe. Continuer ?")) return; sendResetBtn.disabled=true; fetch("{{ route('account.password.reset') }}",{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({ email: accountEmail.value })}).then(async res=>{ const json=await res.json().catch(()=>null); if(!res.ok) throw json||new Error('Erreur'); accountAlert.innerHTML='<div class="alert alert-success">Un email va vous être envoyé contenant le lien de modification du mot de passe.</div>'; }).catch(err=>{ let msg='Impossible d\'envoyer le lien pour le moment.'; if(err && err.message) msg=err.message; accountAlert.innerHTML=`<div class="alert alert-danger">${msg}</div>`; }).finally(()=>sendResetBtn.disabled=false); });

})();
</script>
</body>
</html>
