<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - AgriElevage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optionnel : Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #e7f6e7;
            min-height: 100vh;
        }
        .card {
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }
        .brand-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
        .form-icon {
            min-width: 40px;
            text-align: center;
            color: #4caf50;
        }
        .btn-success {
            background-color: #4caf50;
            border: none;
        }
        .btn-success:hover {
            background-color: #388e3c;
        }
        .elevage-card {
            border: 2px solid #e0e0e0;
            border-radius: 1rem;
            padding: 1.2rem 1rem;
            cursor: pointer;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .elevage-card.selected, .elevage-card:hover {
            border-color: #4caf50;
            box-shadow: 0 2px 12px 0 rgba(76,175,80,0.08);
        }
        .elevage-radio {
            display: none;
        }
        .elevage-icon {
            font-size: 2rem;
            color: #4caf50;
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="card p-4" style="width: 100%; max-width: 400px;">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-logo mb-2">
                <h3 class="fw-bold mb-0">Créer un compte</h3>
                <small class="text-muted">Rejoignez AgriElevage</small>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('signup.submit') }}">
                @csrf
                <div class="mb-3 input-group">
                    <span class="input-group-text form-icon"><i class="bi bi-person"></i></span>
                    <input type="text" name="name" class="form-control" placeholder="Nom complet" value="{{ old('name') }}" required autofocus>
                </div>
                <div class="mb-3 input-group">
                    <span class="input-group-text form-icon"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="Adresse email" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3 input-group">
                    <span class="input-group-text form-icon"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                </div>
                <div class="mb-3 input-group">
                    <span class="input-group-text form-icon"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmer le mot de passe" required>
                </div>
                <div class="mb-3">
                    <label class="form-label mb-2">Type d'éleveur</label>
                    <div class="d-flex gap-3 flex-column flex-md-row">
                        <label class="elevage-card @if(old('type')=='debutant') selected @endif flex-fill">
                            <input type="radio" name="type" value="debutant" class="elevage-radio" required
                                @if(old('type')=='debutant') checked @endif>
                            <span class="elevage-icon">
                                <!-- Icône Débutant (chapeau) SVG inline -->
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <ellipse cx="16" cy="24" rx="10" ry="4" fill="#E7F6E7"/>
                                    <path d="M16 4C10 4 7 10 7 16C7 22 10 28 16 28C22 28 25 22 25 16C25 10 22 4 16 4Z" fill="#4caf50"/>
                                    <ellipse cx="16" cy="13" rx="4" ry="3" fill="#fff"/>
                                </svg>
                            </span>
                            <span>
                                <span class="fw-bold">Débutant</span><br>
                                <small class="text-muted">Je débute dans l'élevage</small>
                            </span>
                        </label>
                        <label class="elevage-card @if(old('type')=='experimente') selected @endif flex-fill">
                            <input type="radio" name="type" value="experimente" class="elevage-radio" required
                                @if(old('type')=='experimente') checked @endif>
                            <span class="elevage-icon">
                                <!-- Icône Expérimenté (médaille) SVG inline -->
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <circle cx="16" cy="13" r="7" fill="#4caf50"/>
                                    <circle cx="16" cy="13" r="4" fill="#fff"/>
                                    <rect x="13" y="20" width="6" height="8" rx="2" fill="#FFD700"/>
                                    <rect x="15" y="24" width="2" height="4" rx="1" fill="#FFA000"/>
                                </svg>
                            </span>
                            <span>
                                <span class="fw-bold">Expérimenté</span><br>
                                <small class="text-muted">J'ai de l'expérience</small>
                            </span>
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-success w-100 mb-2">S'inscrire</button>
            </form>
            <div class="text-center mt-2">
                <small>Déjà un compte ? <a href="{{ route('login') }}" class="text-success fw-bold">Se connecter</a></small>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ajoute la classe 'selected' à la carte choisie
        document.querySelectorAll('.elevage-radio').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.elevage-card').forEach(function(card) {
                    card.classList.remove('selected');
                });
                radio.closest('.elevage-card').classList.add('selected');
            });
        });
    </script>
</body>
</html>
