<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - AgriElevage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="card p-4" style="width: 100%; max-width: 400px;">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-logo mb-2">
                <h3 class="fw-bold mb-0">Connexion</h3>
                <small class="text-muted">Connectez-vous à AgriElevage</small>
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
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                <div class="mb-3 input-group">
                    <span class="input-group-text form-icon"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="Adresse email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="mb-3 input-group">
                    <span class="input-group-text form-icon"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                </div>
                <button type="submit" class="btn btn-success w-100 mb-2">Se connecter</button>
            </form>
            <div class="text-center mt-2">
                <small>Pas encore de compte ? <a href="{{ route('signup') }}" class="text-success fw-bold">Créer un compte</a></small>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
                                     