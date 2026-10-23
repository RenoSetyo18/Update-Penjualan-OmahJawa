<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1f4034">
    <title>Masuk | Omah Jawa</title>
    <link rel="stylesheet" href="{{ asset('dataset/plugins/fontawesome-free/css/all.min.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            color-scheme: light;
            --login-green: #315d46;
            --login-green-dark: #1f4034;
            --login-gold: #c7a56a;
            --login-ink: #263a30;
            --login-muted: #748078;
            --login-border: #e2e9e3;
        }

        * {
            box-sizing: border-box;
        }

        body {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 2rem;
            background:
                radial-gradient(circle at 10% 10%, rgba(199, 165, 106, 0.13), transparent 28rem),
                linear-gradient(135deg, #f3f7f3 0%, #e9f0ea 100%);
            color: var(--login-ink);
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .login-shell {
            display: grid;
            width: min(100%, 1000px);
            min-height: 610px;
            grid-template-columns: 1fr 0.92fr;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 24px;
            background: #fff;
            box-shadow: 0 28px 80px rgba(28, 48, 39, 0.16);
        }

        .login-brand {
            position: relative;
            display: flex;
            min-height: 100%;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            padding: 2.5rem;
            background:
                linear-gradient(180deg, rgba(19, 48, 35, 0.12) 0%, rgba(16, 39, 29, 0.88) 100%),
                url("{{ asset('dataset/dist/img/omahjawa.jpg') }}") center / cover;
            color: #fff;
        }

        .login-brand::before,
        .login-brand::after {
            position: absolute;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 50%;
            content: "";
            pointer-events: none;
        }

        .login-brand::before {
            top: -8rem;
            right: -8rem;
            width: 25rem;
            height: 25rem;
        }

        .login-brand::after {
            right: -3rem;
            bottom: -12rem;
            width: 31rem;
            height: 31rem;
        }

        .brand-content,
        .brand-footer {
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            display: inline-flex;
            width: 84px;
            height: 84px;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 3px solid rgba(255, 255, 255, 0.75);
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.22);
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-eyebrow {
            margin: 2rem 0 0.6rem;
            color: #e8d5ae;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }

        .brand-title {
            max-width: 380px;
            margin: 0;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            letter-spacing: -0.045em;
            line-height: 1.08;
        }

        .brand-description {
            max-width: 340px;
            margin: 1rem 0 0;
            color: rgba(255, 255, 255, 0.78);
            font-size: 1rem;
            line-height: 1.65;
        }

        .brand-footer {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.82rem;
        }

        .brand-footer i {
            color: var(--login-gold);
        }

        .login-form-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3.25rem;
        }

        .login-form-content {
            width: 100%;
            max-width: 370px;
        }

        .login-heading {
            margin: 0;
            color: var(--login-ink);
            font-size: 1.9rem;
            font-weight: 700;
            letter-spacing: -0.04em;
        }

        .login-subheading {
            margin: 0.55rem 0 2rem;
            color: var(--login-muted);
            font-size: 0.96rem;
            line-height: 1.55;
        }

        .login-field {
            margin-bottom: 1.25rem;
        }

        .login-field label {
            display: block;
            margin-bottom: 0.5rem;
            color: #44564a;
            font-size: 0.88rem;
            font-weight: 700;
        }

        .login-input-wrap {
            position: relative;
        }

        .login-input-wrap > i {
            position: absolute;
            top: 50%;
            left: 1rem;
            color: #829087;
            font-size: 0.95rem;
            pointer-events: none;
            transform: translateY(-50%);
        }

        .login-input {
            display: block;
            width: 100%;
            min-height: 50px;
            padding: 0.75rem 1rem 0.75rem 2.8rem;
            border: 1px solid var(--login-border);
            border-radius: 10px;
            outline: none;
            background: #fbfcfb;
            color: var(--login-ink);
            font: inherit;
            transition: border-color 0.18s ease, background-color 0.18s ease, box-shadow 0.18s ease;
        }

        .login-input::placeholder {
            color: #a1aaa3;
        }

        .login-input:focus {
            border-color: #7da58d;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(57, 111, 78, 0.12);
        }

        .login-remember {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            margin: 0.15rem 0 1.5rem;
            color: #637168;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .login-remember input {
            width: 16px;
            height: 16px;
            accent-color: var(--login-green);
        }

        .login-submit {
            display: inline-flex;
            width: 100%;
            min-height: 50px;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            border: 1px solid var(--login-green);
            border-radius: 10px;
            background: linear-gradient(135deg, #3b6a50 0%, #274a37 100%);
            color: #fff;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
            letter-spacing: 0.035em;
            box-shadow: 0 8px 18px rgba(49, 93, 70, 0.2);
            transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        }

        .login-submit:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #315d46 0%, #1f3e2d 100%);
            box-shadow: 0 12px 23px rgba(49, 93, 70, 0.27);
        }

        .login-submit:focus-visible,
        .login-input:focus-visible,
        .login-remember input:focus-visible {
            outline: 3px solid rgba(199, 165, 106, 0.65);
            outline-offset: 3px;
        }

        .login-error {
            margin: 0 0 1.25rem;
            padding: 0.8rem 0.95rem;
            border: 1px solid #f0d4ce;
            border-left: 4px solid #c76b58;
            border-radius: 9px;
            background: #fff5f2;
            color: #934638;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .login-error ul {
            margin: 0;
            padding-left: 1.2rem;
        }

        .login-security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            margin: 1.35rem 0 0;
            color: #8a958d;
            font-size: 0.8rem;
        }

        .login-security-note i {
            color: #66836f;
        }

        @media (max-width: 760px) {
            body {
                padding: 1rem;
            }

            .login-shell {
                max-width: 460px;
                min-height: 0;
                grid-template-columns: 1fr;
                border-radius: 20px;
            }

            .login-brand {
                min-height: 230px;
                padding: 1.5rem;
                background-position: center 42%;
            }

            .brand-logo {
                width: 62px;
                height: 62px;
            }

            .brand-eyebrow {
                margin-top: 1rem;
                font-size: 0.68rem;
            }

            .brand-title {
                font-size: 1.8rem;
            }

            .brand-description,
            .brand-footer {
                display: none;
            }

            .login-form-panel {
                padding: 2rem 1.5rem;
            }

            .login-heading {
                font-size: 1.65rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
    <main class="login-shell">
        <section class="login-brand" aria-label="Omah Jawa">
            <div class="brand-content">
                <div class="brand-logo">
                    <img src="{{ asset('dataset/dist/img/LogoOmahJawa.png') }}" alt="Logo Omah Jawa">
                </div>
                <p class="brand-eyebrow">Selamat datang di</p>
                <h1 class="brand-title">Omah Jawa</h1>
                <p class="brand-description">Masuk untuk mengelola operasional dan layanan Omah Jawa.</p>
            </div>
            <div class="brand-footer">
                <i class="fas fa-leaf" aria-hidden="true"></i>
                <span>Hangat, nyaman, dan penuh rasa.</span>
            </div>
        </section>

        <section class="login-form-panel" aria-labelledby="loginHeading">
            <div class="login-form-content">
                <h2 class="login-heading" id="loginHeading">Masuk ke akun</h2>
                <p class="login-subheading">Gunakan email dan kata sandi yang terdaftar untuk melanjutkan.</p>

                @if ($errors->any())
                    <div class="login-error" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="login-field">
                        <label for="email">Email</label>
                        <div class="login-input-wrap">
                            <i class="fas fa-envelope" aria-hidden="true"></i>
                            <input
                                class="login-input"
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                autocomplete="email"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="login-field">
                        <label for="password">Kata sandi</label>
                        <div class="login-input-wrap">
                            <i class="fas fa-lock" aria-hidden="true"></i>
                            <input
                                class="login-input"
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Masukkan kata sandi"
                                autocomplete="current-password"
                                required
                            >
                        </div>
                    </div>

                    <label class="login-remember" for="remember">
                        <input type="checkbox" id="remember" name="remember">
                        <span>Ingat saya</span>
                    </label>

                    <button class="login-submit" type="submit">
                        <span>Masuk</span>
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>

                <p class="login-security-note">
                    <i class="fas fa-shield-alt" aria-hidden="true"></i>
                    <span>Informasi akun Anda terlindungi.</span>
                </p>
            </div>
        </section>
    </main>

    @if(session('error-unauth'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Error!",
                    text: @json(session('error-unauth')),
                    icon: "error"
                });
            });
        </script>
    @endif
</body>
</html>
