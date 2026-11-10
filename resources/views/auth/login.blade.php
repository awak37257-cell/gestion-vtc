<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <title>Connexion recruteur — TIHE'S Group</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>
        :root {
            color-scheme: light;
            --ink: #0f172a;
            --ink-soft: #1e293b;
            --muted: #64748b;
            --gold: #eab308;
            --gold-dark: #a16207;
            --gold-light: #fef9c3;
            --emerald: #10b981;
            --emerald-dark: #047857;
            --emerald-light: #d1fae5;
            --rose: #f43f5e;
            --rose-light: #ffe4e6;
            --paper: #f8fafc;
            --white: #ffffff;
            --line: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.05), 0 2px 4px -2px rgba(15, 23, 42, 0.05);
            --shadow-lg: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            --radius: 14px;
        }

        * { box-sizing: border-box; }
        body {
            min-width: 320px;
            min-height: 100vh;
            margin: 0;
            background: var(--paper);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }
        a:focus-visible, input:focus-visible, button:focus-visible {
            outline: 3px solid var(--gold);
            outline-offset: 3px;
        }
        h1, h2, p { margin-top: 0; }
        h1, h2 { font-family: 'Manrope', sans-serif; }

        .login-shell {
            display: grid;
            min-height: 100vh;
            grid-template-columns: minmax(0, 1fr) minmax(420px, .9fr);
        }

        /* BRAND PANEL (gauche) */
        .brand-panel {
            position: relative;
            display: flex;
            min-height: 100%;
            flex-direction: column;
            justify-content: space-between;
            padding: 38px clamp(35px, 7vw, 105px);
            overflow: hidden;
            background:
                linear-gradient(135deg, rgba(15, 23, 42, .97), rgba(15, 23, 42, .82)),
                url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1400&q=85') center / cover;
            color: var(--white);
        }
        .brand-panel::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--emerald), var(--gold));
        }
        .brand-panel::after {
            position: absolute;
            right: -200px;
            bottom: -240px;
            width: 580px;
            height: 580px;
            border: 1px solid rgba(234, 179, 8, .2);
            border-radius: 50%;
            box-shadow: 0 0 0 55px rgba(234, 179, 8, .04), 0 0 0 110px rgba(16, 185, 129, .025);
            content: '';
            pointer-events: none;
        }

        .brand {
            position: relative; z-index: 1;
            display: inline-flex; width: fit-content;
            align-items: center; gap: 11px;
            font-family: 'Manrope', sans-serif;
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -.04em;
        }
        .brand-mark {
            display: grid; width: 42px; height: 42px; place-items: center;
            border-radius: 11px;
            background: linear-gradient(135deg, var(--gold) 0%, #f59e0b 100%);
            color: var(--ink);
            box-shadow: 0 4px 14px rgba(234, 179, 8, 0.4);
        }
        .brand-logo { width: 180px; height: 80px; padding: 3px; border-radius: 8px; background: var(--white); object-fit: contain; }
        .brand-name span { color: var(--gold); }

        .brand-message { position: relative; z-index: 1; max-width: 560px; margin: auto 0; padding: 100px 0; }
        .eyebrow {
            display: inline-flex; align-items: center; gap: 9px;
            margin-bottom: 20px;
            color: var(--gold);
            font-size: .73rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
        }
        .eyebrow::before { width: 22px; height: 2px; background: linear-gradient(90deg, var(--gold), var(--emerald)); border-radius: 2px; content: ''; }
        .brand-message h1 {
            max-width: 540px;
            margin-bottom: 18px;
            font-size: clamp(2.6rem, 5vw, 4.5rem);
            font-weight: 800;
            letter-spacing: -.065em;
            line-height: 1.06;
        }
        .brand-message h1 span {
            background: linear-gradient(135deg, var(--gold) 0%, #fbbf24 50%, var(--emerald) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .brand-message p { max-width: 440px; margin-bottom: 0; color: rgba(255,255,255,.72); font-size: 1rem; line-height: 1.75; }
        .brand-foot { position: relative; z-index: 1; color: rgba(255,255,255,.5); font-size: .78rem; }

        /* FORM PANEL (droite) */
        .form-panel {
            display: flex; align-items: center; justify-content: center;
            padding: 48px clamp(28px, 7vw, 105px);
            position: relative;
        }
        .form-content { width: min(100%, 430px); }
        .mobile-brand { display: none; }

        .back-link {
            display: inline-flex; align-items: center; gap: 8px;
            margin-bottom: 47px;
            color: var(--muted);
            font-size: .82rem;
            font-weight: 600;
            transition: all .2s ease;
            padding: 6px 10px;
            border-radius: 7px;
        }
        .back-link:hover { color: var(--gold-dark); background: var(--gold-light); }

        .form-heading { margin-bottom: 32px; }
        .form-heading h2 {
            margin-bottom: 10px;
            font-size: clamp(1.8rem, 3vw, 2.25rem);
            font-weight: 800;
            letter-spacing: -.05em;
            background: linear-gradient(135deg, var(--ink) 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .form-heading p { margin-bottom: 0; color: var(--muted); font-size: .91rem; line-height: 1.65; }

        /* ERRORS */
        .errors {
            display: grid; gap: 7px;
            margin-bottom: 22px; padding: 15px 17px;
            border: 1px solid #fecdd3;
            border-radius: 10px;
            background: linear-gradient(135deg, #fff1f2, #ffe4e6);
            color: #be123c;
            font-size: .81rem;
            line-height: 1.5;
        }
        .errors-title { display: flex; align-items: center; gap: 9px; font-weight: 700; }
        .errors ul { margin: 0; padding-left: 26px; }

        /* FIELDS */
        .field { margin-bottom: 20px; }
        .field label {
            display: block; margin-bottom: 8px;
            color: var(--ink-soft);
            font-size: .83rem;
            font-weight: 700;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; top: 50%; left: 15px;
            color: #94a3b8;
            font-size: .9rem;
            pointer-events: none;
            transform: translateY(-50%);
            transition: color .2s ease;
        }
        .field input {
            display: block;
            width: 100%;
            min-height: 52px;
            padding: 12px 14px 12px 44px;
            border: 1.5px solid var(--line);
            border-radius: 9px;
            background: var(--white);
            color: var(--ink);
            font: inherit;
            font-size: .9rem;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .field input::placeholder { color: #94a3b8; }
        .field input:hover { border-color: #cbd5e1; }
        .field input:focus {
            border-color: var(--gold);
            outline: 0;
            box-shadow: 0 0 0 3px rgba(234, 179, 8, .18);
        }
        .field input:focus + .input-icon,
        .input-wrap:focus-within .input-icon { color: var(--gold-dark); }
        .field input.is-invalid { border-color: var(--rose); }
        .field-error { display: block; margin-top: 7px; color: var(--rose); font-size: .77rem; font-weight: 600; }

        /* SUBMIT BUTTON */
        .submit-button {
            display: flex; width: 100%; min-height: 54px;
            align-items: center; justify-content: center; gap: 10px;
            margin-top: 10px; padding: 12px 20px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--ink) 0%, #334155 100%);
            color: var(--white);
            font: inherit;
            font-size: .9rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s ease;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.2);
        }
        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.3);
        }
        .submit-button i { transition: transform .2s ease; }
        .submit-button:hover i { transform: translateX(3px); }

        /* SECURE NOTE */
        .secure-note {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin: 22px 0 0;
            color: var(--muted);
            font-size: .76rem;
        }
        .secure-note i { color: var(--emerald-dark); }

        /* RESPONSIVE */
        @media (max-width: 800px) {
            .login-shell { grid-template-columns: 1fr; }
            .brand-panel { display: none; }
            .form-panel {
                min-height: 100vh;
                padding: 36px 24px;
                background:
                    radial-gradient(circle at 100% 0%, rgba(234, 179, 8, 0.08) 0%, transparent 40%),
                    radial-gradient(circle at 0% 100%, rgba(16, 185, 129, 0.06) 0%, transparent 40%);
            }
            .mobile-brand { display: inline-flex; margin-bottom: 42px; color: var(--ink); }
            .mobile-brand .brand-name span { color: var(--gold-dark); }
            .back-link { margin-bottom: 30px; }
        }
        @media (max-width: 480px) {
            .form-panel { align-items: flex-start; padding: 25px 22px 40px; }
            .mobile-brand { margin-bottom: 36px; }
            .form-heading { margin-bottom: 25px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <main class="login-shell">
        <section class="brand-panel" aria-label="TIHE'S Group">
            <a class="brand" href="{{ route('welcome') }}" aria-label="TIHE'S Group, accueil">
                <img class="brand-logo" src="{{ asset('images/logo.png') }}" alt="">
                <span class="brand-name">TIHE'S<span>Group</span></span>
            </a>
            <div class="brand-message">
                <p class="eyebrow">Espace professionnel</p>
                <h1>Le recrutement,en mouvement.</h1>
                <p>Retrouvez les candidatures et accompagnez les chauffeurs dans les prochaines étapes de leur parcours.</p>
            </div>
            <p class="brand-foot">&copy; {{ date('Y') }} TIHE'S Group · Espace recruteur</p>
        </section>

        <section class="form-panel" aria-labelledby="login-title">
            <div class="form-content">
                <a class="brand mobile-brand" href="{{ route('welcome') }}" aria-label="TIHE'S Group, accueil">
                    <img class="brand-logo" src="{{ asset('images/logo.png') }}" alt="">
                    <span class="brand-name">VTC<span>Drive</span></span>
                </a>
                <a class="back-link" href="{{ route('welcome') }}"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour au site</a>

                <div class="form-heading">
                    <h2 id="login-title">Bon retour parmi nous.</h2>
                    <p>Connectez-vous pour accéder à votre espace de recrutement.</p>
                </div>

                @if($errors->any())
                    <div class="errors" role="alert">
                        <div class="errors-title"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> Connexion impossible</div>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="field">
                        <label for="email">Adresse e-mail</label>
                        <div class="input-wrap">
                            <input
                                id="email"
                                name="email"
                                type="email"
                                class="@error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="nom@exemple.com"
                                autocomplete="username"
                                required
                                autofocus
                                aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
                            >
                            <i class="input-icon fa-regular fa-envelope" aria-hidden="true"></i>
                        </div>
                        @error('email')<span class="field-error" id="email-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="password">Mot de passe</label>
                        <div class="input-wrap">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                placeholder="••••••••"
                                required
                            >
                            <i class="input-icon fa-solid fa-lock" aria-hidden="true"></i>
                        </div>
                    </div>

                    <button class="submit-button" type="submit">
                        Accéder à mon espace <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>

                <p class="secure-note">
                    <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                    Accès réservé à l'équipe de recrutement
                </p>
            </div>
        </section>
    </main>
</body>
</html>