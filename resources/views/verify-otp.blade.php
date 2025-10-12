<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification Email - AgriElevage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        #resend-btn[disabled] {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="card p-4" style="width: 100%; max-width: 400px;">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-logo mb-2">
                <h3 class="fw-bold mb-0">Vérification Email</h3>
                <small class="text-muted">Un code à 6 chiffres a été envoyé à votre adresse email.</small>
            </div>
            @if (session('resent'))
                <div class="alert alert-success">
                    Un nouveau code a été envoyé à votre adresse email.
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('verify.otp') }}">
                @csrf
                <div class="mb-3 input-group">
                    <span class="input-group-text form-icon"><i class="bi bi-shield-lock"></i></span>
                    <input type="text" name="otp_code" class="form-control" placeholder="Code à 6 chiffres" maxlength="6" required autofocus value="{{ old('otp_code') }}">
                </div>
                <button type="submit" class="btn btn-success w-100 mb-2">Vérifier</button>
            </form>
            <form method="POST" action="{{ route('verify.otp.resend') }}">
                @csrf
                <button type="submit" class="btn btn-link w-100" id="resend-btn" disabled>
                    Renvoyer le code <span id="timer">(30s)</span>
                </button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let seconds = 30;
        const btn = document.getElementById('resend-btn');
        const timer = document.getElementById('timer');
        const interval = setInterval(() => {
            seconds--;
            timer.textContent = `(${seconds}s)`;
            if (seconds <= 0) {
                btn.disabled = false;
                timer.textContent = '';
                clearInterval(interval);
            }
        }, 1000);
    </script>
</body>
</html>
