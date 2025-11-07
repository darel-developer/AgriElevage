<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Apprentissage - AgriElevage</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		/* ...design reused from other views... */
		body { background: #e6f3e6; }
		.sidebar {
			background: #345c37; min-height: 100vh; color: #fff; width: 220px; position: fixed; top:0; left:0; bottom:0;
			z-index:100; display:flex; flex-direction:column; justify-content:flex-start;
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
		.main-content { margin-left:220px; padding:2rem 1rem; min-height:100vh; }
		.course-card { border-radius:0.8rem; box-shadow:0 1px 8px rgba(31,38,135,0.06); background:#fff; padding:1rem; }
		.badge-free { background:#5fc77a; color:#fff; }
		.badge-paid { background:#e74c3c; color:#fff; }
		.filter-row .form-control, .filter-row .form-select { min-width:150px; }
		@media (max-width:991.98px) { .sidebar { position:static; width:100%; min-height:auto; } .main-content{margin-left:0;} }
	</style>
</head>
<body>
	<div class="sidebar">
   
    <div class="sidebar-title fs-5 fw-bold py-4 px-4 d-none d-lg-block">AgriElevage</div>
    <ul class="nav flex-column mb-2 px-2">
        <li class="nav-item mb-2">
            <a class="nav-link active rounded" href="{{route('dashboard')}}"><i class="bi bi-house-door"></i>Accueil</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="{{route('suivi.individuel')}}"><i class="bi bi-person-lines-fill"></i>Suivi Individuel</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="{{route('health.diagnostic')}}"><i class="bi bi-heart-pulse"></i>Santé</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link rounded" href="#"><i class="bi bi-activity"></i>Reproduction</a>
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
		<div class="container-fluid">
			<div class="d-flex align-items-center justify-content-between mb-4">
				<div>
					<h3 class="mb-0" style="color:#345c37;">Cours d'apprentissage</h3>
					<small class="text-muted">Ressources gratuites pour l'élevage (poules, lapins, etc.)</small>
				</div>
				<div>
					<button class="btn btn-outline-success me-2" id="refreshCoursesBtn"><i class="bi bi-arrow-clockwise"></i> Actualiser</button>
					<button class="btn btn-success" id="addCourseBtn"><i class="bi bi-plus-circle"></i> Ajouter un cours</button>
				</div>
			</div>

			<div class="mb-3 p-3 course-card">
				<div class="row g-2 align-items-center filter-row">
					<div class="col-auto">
						<input id="searchCourse" type="search" class="form-control" placeholder="Rechercher un cours...">
					</div>
					<div class="col-auto">
						<select id="filterSpecies" class="form-select">
							<option value="">Toutes les espèces</option>
							<option value="poule">Poule</option>
							<option value="lapin">Lapin</option>
						</select>
					</div>
					<div class="col-auto">
						<select id="filterType" class="form-select">
							<option value="">Tous niveaux</option>
							<option value="débutant">Débutant</option>
							<option value="intermédiaire">Intermédiaire</option>
							<option value="avancé">Avancé</option>
						</select>
					</div>
					<div class="col-auto">
						<select id="filterPrice" class="form-select">
							<option value="">Tous</option>
							<option value="free">Gratuit</option>
							<option value="paid">Payant</option>
						</select>
					</div>
					<div class="col-auto ms-auto">
						<button class="btn btn-success" id="applyFilters">Appliquer</button>
						<button class="btn btn-light" id="clearFilters">Réinitialiser</button>
					</div>
				</div>
			</div>

			<div id="coursesGrid" class="row g-3">
				<!-- cards generated by JS -->
			</div>

			<!-- modal cours -->
			<div class="modal fade" id="courseModal" tabindex="-1" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="courseModalTitle">Titre du cours</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
						</div>
						<div class="modal-body" id="courseModalBody">
							<!-- détail rempli par JS -->
						</div>
						<div class="modal-footer">
							<span id="courseBadge"></span>
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
							<button id="startCourseBtn" type="button" class="btn btn-success">Commencer (gratuit)</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal upload cours -->
			<div class="modal fade" id="courseUploadModal" tabindex="-1" aria-hidden="true">
				<div class="modal-dialog">
					<form class="modal-content" id="courseUploadForm" enctype="multipart/form-data">
						<div class="modal-header">
							<h5 class="modal-title">Ajouter un cours</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
						</div>
						<div class="modal-body">
							<div id="courseUploadAlert"></div>
							<div class="mb-3">
								<label class="form-label">Titre</label>
								<input type="text" name="title" class="form-control" required>
							</div>
							<div class="mb-3">
								<label class="form-label">Espèce</label>
								<select name="species" class="form-select">
									<option value="">Toutes</option>
									<option value="poule">Poule</option>
									<option value="lapin">Lapin</option>
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label">Niveau</label>
								<select name="level" class="form-select">
									<option value="">Tous</option>
									<option value="débutant">Débutant</option>
									<option value="intermédiaire">Intermédiaire</option>
									<option value="avancé">Avancé</option>
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label">Description</label>
								<textarea name="description" class="form-control" rows="3"></textarea>
							</div>
							<div class="mb-3">
								<label class="form-label">Fichier (PDF / PPT / vidéo)</label>
								<input type="file" name="file" accept=".pdf,.ppt,.pptx,video/*,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="form-control" required>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
							<button type="submit" class="btn btn-success">Téléverser</button>
						</div>
					</form>
				</div>
			</div>

		</div>
	</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
	// coursesData chargé depuis l'API
	let coursesData = [];

	async function fetchCourses() {
		try {
			const res = await fetch("{{ url('/api/courses') }}", { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
			if (!res.ok) throw res;
			coursesData = await res.json();
			renderCourses(coursesData);
		} catch (e) {
			console.error(e);
			// fallback: empty list
			coursesData = [];
			renderCourses(coursesData);
		}
	}

	function renderCourses(list) {
		grid.innerHTML = '';
		if (!list.length) {
			grid.innerHTML = '<div class="col-12"><div class="course-card text-center">Aucun cours trouvé.</div></div>';
			return;
		}
		list.forEach(c => {
			const col = document.createElement('div');
			col.className = 'col-md-4';
			col.innerHTML = `
				<div class="course-card h-100 d-flex flex-column">
					<div class="d-flex justify-content-between mb-2">
						<h5 class="mb-0">${c.title}</h5>
						<span class="badge ${c.price==='free'?'badge-free':'badge-paid'}">${c.price==='free'?'Gratuit':'Payant'}</span>
					</div>
					<div class="text-muted small mb-2">${c.species ? c.species.charAt(0).toUpperCase()+c.species.slice(1) : ''} • ${c.level} • ${c.duration}</div>
					<p class="flex-grow-1">${c.summary}</p>
					<div class="mt-3 d-flex justify-content-between align-items-center">
						<button class="btn btn-outline-success btn-sm" data-id="${c.id}" onclick="openCourse(${c.id})">Voir</button>
						<small class="text-muted">Leçons: ${c.lessons}</small>
					</div>
				</div>
			`;
			grid.appendChild(col);
		});
	}

	function applyFilters() {
		const q = searchEl.value.trim().toLowerCase();
		const sp = speciesEl.value;
		const tp = typeEl.value;
		const pr = priceEl.value;
		let out = coursesData.filter(c => {
			if (sp && c.species !== sp) return false;
			if (tp && c.level !== tp) return false;
			if (pr && (pr==='free' ? c.price !== 'free' : c.price === 'free')) return false;
			if (q && !(c.title.toLowerCase().includes(q) || c.summary.toLowerCase().includes(q))) return false;
			return true;
		});
		renderCourses(out);
	}

	document.getElementById('applyFilters').addEventListener('click', applyFilters);
	document.getElementById('clearFilters').addEventListener('click', function(){
		searchEl.value=''; speciesEl.value=''; typeEl.value=''; priceEl.value=''; renderCourses(coursesData);
	});
	document.getElementById('refreshCoursesBtn').addEventListener('click', function(){ renderCourses(coursesData); });

	// open course detail
	window.openCourse = function(id) {
		const c = coursesData.find(x=>x.id==id);
		if (!c) return;
		document.getElementById('courseModalTitle').textContent = c.title;
		document.getElementById('courseModalBody').innerHTML = `
			<div><strong>Espèce :</strong> ${c.species}</div>
			<div><strong>Niveau :</strong> ${c.level}</div>
			<div><strong>Durée :</strong> ${c.duration}</div>
			<div class="mt-3"><strong>Résumé :</strong><p>${c.summary}</p></div>
			<div><strong>Contenu :</strong><p>${c.content}</p></div>
			<div class="mt-2 text-muted"><small>Auteur : ${c.author} • Leçons : ${c.lessons}</small></div>
		`;
		const badge = document.getElementById('courseBadge');
		badge.innerHTML = `<span class="badge ${c.price==='free'?'badge-free':'badge-paid'}">${c.price==='free'?'Gratuit':'Payant'}</span>`;
		const startBtn = document.getElementById('startCourseBtn');
		if (c.price === 'free') {
			startBtn.textContent = 'Commencer (gratuit)';
			startBtn.classList.remove('btn-outline-primary'); startBtn.classList.add('btn-success');
			startBtn.onclick = () => { alert('Démarrage du cours "'+c.title+'". (Simulation)'); courseModal.hide(); };
		} else {
			startBtn.textContent = 'Voir l\'offre';
			startBtn.classList.remove('btn-success'); startBtn.classList.add('btn-primary');
			startBtn.onclick = () => { alert('Redirection vers la page de paiement (simulation)'); };
		}
		courseModal.show();
	};

	// open upload modal
	document.getElementById('addCourseBtn').addEventListener('click', function() {
		document.getElementById('courseUploadForm').reset();
		document.getElementById('courseUploadAlert').innerHTML = '';
		new bootstrap.Modal(document.getElementById('courseUploadModal')).show();
	});

	// submit upload form
	document.getElementById('courseUploadForm').addEventListener('submit', function(e) {
		e.preventDefault();
		const form = this;
		const fd = new FormData(form);
		fd.append('_token','{{ csrf_token() }}');
		fetch("{{ route('learning-courses.upload') }}", {
			method: "POST",
			headers: { 'X-Requested-With': 'XMLHttpRequest' },
			body: fd
		})
		.then(async res => {
			if (!res.ok) {
				const j = await res.json().catch(()=>null);
				throw j || new Error('Upload failed');
			}
			return res.json();
		})
		.then(json => {
			document.getElementById('courseUploadAlert').innerHTML = '<div class="alert alert-success">Cours ajouté.</div>';
			setTimeout(() => {
				hideModalClean('courseUploadModal');
				fetchCourses();
			}, 700);
		})
		.catch(async err => {
			console.error(err);
			let msg = 'Erreur lors du téléversement.';
			if (err && err.errors) msg = Object.values(err.errors).flat().join('<br>');
			document.getElementById('courseUploadAlert').innerHTML = `<div class="alert alert-danger">${msg}</div>`;
		});
	});

	const grid = document.getElementById('coursesGrid');
	const searchEl = document.getElementById('searchCourse');
	const speciesEl = document.getElementById('filterSpecies');
	const typeEl = document.getElementById('filterType');
	const priceEl = document.getElementById('filterPrice');
	const courseModal = new bootstrap.Modal(document.getElementById('courseModal'));

	// initial load
	fetchCourses();
</script>
</body>
</html>
