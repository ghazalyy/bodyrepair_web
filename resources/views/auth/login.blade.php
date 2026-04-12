<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Garasi Kampoeng X HGMP Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0b0f1a;
            overflow: hidden;
            position: relative;
        }

        /* ── Animated Background ── */
        .bg-glow {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }
        .bg-glow span {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .55;
            animation: float 10s ease-in-out infinite alternate;
        }
        .bg-glow span:nth-child(1) {
            width: 420px; height: 420px;
            background: radial-gradient(circle, #4f46e5 0%, transparent 70%);
            top: -80px; left: -80px;
            animation-duration: 12s;
        }
        .bg-glow span:nth-child(2) {
            width: 360px; height: 360px;
            background: radial-gradient(circle, #7c3aed 0%, transparent 70%);
            bottom: -60px; right: -60px;
            animation-duration: 9s;
        }
        .bg-glow span:nth-child(3) {
            width: 240px; height: 240px;
            background: radial-gradient(circle, #06b6d4 0%, transparent 70%);
            top: 40%; left: 60%;
            opacity: .3;
            animation-duration: 15s;
        }
        @keyframes float {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(30px, 20px) scale(1.08); }
        }

        /* ── Card ── */
        .login-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            margin: 1.5rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2.75rem 2.5rem 2.25rem;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.05) inset;
            animation: slideUp .6s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Logo / Brand ── */
        .brand-icon {
            width: 56px; height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.5);
        }
        .brand-icon i { font-size: 1.6rem; color: #fff; }

        .card-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #f1f5f9;
            letter-spacing: -.3px;
        }
        .card-subtitle {
            font-size: .82rem;
            color: rgba(255,255,255,.45);
            margin-top: .3rem;
            margin-bottom: 1.75rem;
        }

        /* ── Alert ── */
        .alert-custom {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .7rem 1rem;
            background: rgba(239, 68, 68, .12);
            border: 1px solid rgba(239, 68, 68, .3);
            border-radius: 10px;
            color: #fca5a5;
            font-size: .8rem;
            margin-bottom: 1.25rem;
            animation: slideUp .4s ease;
        }

        /* ── Form Fields ── */
        .field-group {
            margin-bottom: 1.1rem;
        }
        .field-label {
            display: block;
            font-size: .75rem;
            font-weight: 600;
            color: rgba(255,255,255,.55);
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-bottom: .45rem;
        }
        .field-input-wrap {
            position: relative;
        }
        .field-input-wrap i.input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,.3);
            font-size: .95rem;
            pointer-events: none;
            transition: color .2s;
        }
        .field-input {
            width: 100%;
            padding: .78rem 1rem .78rem 2.6rem;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #f1f5f9;
            font-size: .9rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color .25s, box-shadow .25s, background .25s;
            -webkit-appearance: none;
        }
        .field-input::placeholder { color: rgba(255,255,255,.22); }
        .field-input:focus {
            border-color: #6d63ff;
            background: rgba(79, 70, 229, 0.08);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }
        .field-input:focus ~ i.input-icon,
        .field-input-wrap:focus-within i.input-icon {
            color: #a5b4fc;
        }
        .field-input.is-invalid {
            border-color: rgba(239,68,68,.6);
        }
        .invalid-msg {
            font-size: .74rem;
            color: #fca5a5;
            margin-top: .35rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        /* Password toggle */
        .toggle-eye {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255,255,255,.3);
            font-size: .9rem;
            padding: 2px;
            transition: color .2s;
        }
        .toggle-eye:hover { color: rgba(255,255,255,.7); }

        /* ── Remember row ── */
        .remember-row {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .custom-check {
            display: flex;
            align-items: center;
            gap: .5rem;
            cursor: pointer;
            user-select: none;
        }
        .custom-check input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #6d63ff;
            cursor: pointer;
            border-radius: 4px;
        }
        .custom-check span {
            font-size: .8rem;
            color: rgba(255,255,255,.45);
        }

        /* ── Submit Button ── */
        .btn-submit {
            width: 100%;
            padding: .82rem;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: .9rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            letter-spacing: .01em;
            transition: transform .2s, box-shadow .2s, opacity .2s;
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            position: relative;
            overflow: hidden;
        }
        .btn-submit::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.12) 0%, transparent 60%);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 32px rgba(79, 70, 229, 0.55);
        }
        .btn-submit:active {
            transform: translateY(0);
            opacity: .9;
        }

        /* ── Demo box ── */
        .demo-box {
            margin-top: 1.5rem;
            padding: .85rem 1rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 12px;
            font-size: .74rem;
            color: rgba(255,255,255,.35);
            line-height: 1.7;
        }
        .demo-box strong { color: rgba(255,255,255,.5); }
        .demo-box code {
            background: rgba(255,255,255,.08);
            padding: .1rem .35rem;
            border-radius: 4px;
            color: #a5b4fc;
            font-size: .72rem;
        }

        /* ── Divider / Footer ── */
        .card-footer-text {
            text-align: center;
            font-size: .72rem;
            color: rgba(255,255,255,.25);
            margin-top: 1.25rem;
        }

        /* ── Responsive ── */
        @media (max-width: 480px) {
            .login-card { padding: 2rem 1.5rem 1.75rem; margin: 1rem; }
            .card-title { font-size: 1.3rem; }
        }
    </style>
</head>
<body>

    {{-- Ambient background glows --}}
    <div class="bg-glow" aria-hidden="true">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="login-card">

        {{-- Brand --}}
        <div class="mb-4 text-center">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="img-fluid" style="max-height: 80px;">
        </div>
        <h1 class="card-title">Selamat Datang 👋</h1>
        <p class="card-subtitle">Garasi Kampoeng X HGMP Admin System &mdash; masuk untuk melanjutkan</p>

        {{-- Error alert --}}
        @if($errors->any())
        <div class="alert-custom" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" novalidate>
            @csrf

            {{-- Email --}}
            <div class="field-group">
                <label class="field-label" for="email">Email</label>
                <div class="field-input-wrap">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="field-input @error('email') is-invalid @enderror"
                        placeholder="admin@gkxhgmp.com"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required>
                    <i class="bi bi-envelope input-icon"></i>
                </div>
                @error('email')
                <p class="invalid-msg"><i class="bi bi-x-circle-fill"></i>{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="field-group">
                <label class="field-label" for="password">Password</label>
                <div class="field-input-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="field-input @error('password') is-invalid @enderror"
                        placeholder="••••••••"
                        required>
                    <i class="bi bi-lock input-icon"></i>
                    <button type="button" class="toggle-eye" id="togglePwd" aria-label="Toggle password visibility">
                        <i class="bi bi-eye-slash" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                <p class="invalid-msg"><i class="bi bi-x-circle-fill"></i>{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-submit" id="btnLogin">
                <i class="bi bi-box-arrow-in-right"></i>
                Masuk ke Sistem
            </button>
        </form>

        {{-- Demo accounts --}}
        <div class="demo-box">
            <strong>Demo akun:</strong><br>
            Admin: <code>admin@gkxhgmp.com</code> / <code>password123</code><br>
            Owner: <code>owner@gkxhgmp.com</code> / <code>password123</code>
        </div>

        <p class="card-footer-text">&copy; {{ date('Y') }} Garasi Kampoeng X HGMP Admin &mdash; All rights reserved</p>
    </div>

    <script>
        // Password visibility toggle
        const togglePwd = document.getElementById('togglePwd');
        const pwdInput  = document.getElementById('password');
        const eyeIcon   = document.getElementById('eyeIcon');

        togglePwd.addEventListener('click', () => {
            const isHidden = pwdInput.type === 'password';
            pwdInput.type  = isHidden ? 'text' : 'password';
            eyeIcon.className = isHidden ? 'bi bi-eye' : 'bi bi-eye-slash';
        });

        // Focus icon color sync
        document.querySelectorAll('.field-input').forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.querySelector('.input-icon')?.classList.add('focused');
            });
            input.addEventListener('blur', () => {
                input.parentElement.querySelector('.input-icon')?.classList.remove('focused');
            });
        });
    </script>
</body>
</html>
