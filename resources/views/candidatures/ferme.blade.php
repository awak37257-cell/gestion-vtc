<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <title>Recrutement clôturé — TIHE'S Group</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
            --paper: #f8fafc;
            --white: #ffffff;
            --line: #e2e8f0;
            --shadow-lg: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            --radius: 18px;
        }

        * { box-sizing: border-box; }
        body {
            min-width: 320px;
            min-height: 100vh;
            margin: 0;
            background:
                radial-gradient(circle at 15% 15%, rgba(234, 179, 8, 0.08) 0%, transparent 45%),
                radial-gradient(circle at 85% 85%, rgba(16, 185, 129, 0.06) 0%, transparent 45%),
                var(--paper);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        a { color: inherit; text-decoration: none; }
        a:focus-visible, button:focus-visible {
            outline: 3px solid var(--gold);
            outline-offset: 3px;
        }

        /* CARD */
        .closed-card {
            position: relative;
            width: min(100%, 540px);
            padding: 0 44px 42px;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--white);
            box-shadow: var(--shadow-lg);
            text-align: center;
            overflow: hidden;
        }

        /* BRAND HEADER (bannière en haut de la carte) */
        .brand-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin: 0 -44px 32px;
            padding: 22px 24px;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-bottom: 1px solid rgba(234, 179, 8, 0.25);
            position: relative;
        }
        .brand-header::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--emerald), var(--gold));
        }
        .brand-logo {
            height: 38px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.25));
        }
        .brand-name {
            font-family: 'Manrope', sans-serif;
            font-size: 1.28rem;
            font-weight: 800;
            color: var(--white);
            letter-spacing: -.03em;
        }
        .brand-name span {
            color: var(--gold);
        }

        /* ICON */
        .closed-icon {
            display: grid;
            width: 72px; height: 72px;
            margin: 0 auto 22px;
            place-items: center;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--gold-light), #fef3c7);
            color: var(--gold-dark);
            font-size: 1.75rem;
            border: 2px solid var(--white);
            box-shadow: 0 10px 24px rgba(234, 179, 8, 0.25);
            position: relative;
            z-index: 1;
        }

        /* EYEBROW */
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
            padding: 6px 12px;
            border-radius: 99px;
            background: linear-gradient(135deg, var(--gold-light), #fef3c7);
            color: var(--gold-dark);
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        .eyebrow::before {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--gold-dark);
            content: '';
        }

        /* TITLE */
        .closed-card h1 {
            margin: 0 0 14px;
            font-family: 'Manrope', sans-serif;
            font-size: clamp(1.5rem, 3vw, 1.85rem);
            font-weight: 800;
            letter-spacing: -.035em;
            line-height: 1.2;
            color: var(--ink);
        }

        /* DESCRIPTION */
        .closed-card p {
            margin: 0 0 32px;
            color: var(--muted);
            font-size: .93rem;
            line-height: 1.7;
        }
        .closed-card p strong {
            color: var(--ink);
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 6px;
            background: var(--gold-light);
            border: 1px solid #fde68a;
            white-space: nowrap;
        }

        /* DIVIDER */
        .closed-divider {
            height: 1px;
            margin: 0 0 28px;
            background: linear-gradient(90deg, transparent, var(--line) 20%, var(--line) 80%, transparent);
        }

        /* BUTTON */
        .back-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 52px;
            padding: 0 26px;
            border: 0;
            border-radius: 11px;
            background: linear-gradient(135deg, var(--ink) 0%, #334155 100%);
            color: var(--white);
            font-size: .87rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s ease;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.2);
        }
        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.3);
        }
        .back-button i {
            transition: transform .2s ease;
        }
        .back-button:hover i {
            transform: translateX(-3px);
        }

        /* FOOTER NOTE */
        .closed-note {
            margin-top: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--muted);
            font-size: .76rem;
        }
        .closed-note i {
            color: var(--emerald-dark);
        }

        @media (max-width: 480px) {
            .closed-card { padding: 0 22px 30px; }
            .brand-header {
                margin: 0 -22px 28px;
                padding: 18px 16px;
                gap: 10px;
            }
            .brand-logo { height: 32px; }
            .brand-name { font-size: 1.12rem; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <main class="closed-card" aria-labelledby="closed-title">

        <!-- BANNIÈRE MARQUE (logo + nom) -->
        <header class="brand-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo TIHE'S Group" class="brand-logo">
            <span class="brand-name">TIHE'S<span>Group</span></span>
        </header>

        <div class="closed-icon" aria-hidden="true">
            <i class="fa-solid fa-hourglass-end"></i>
        </div>

        <span class="eyebrow">Session clôturée</span>

        <h1 id="closed-title">Recrutement actuellement clôturé</h1>

        <p>
            La date limite pour déposer une candidature était fixée au
            <strong>{{ $dateLimite->format('d/m/Y à H:i') }}</strong>.
            Nous n'acceptons plus de nouveaux dossiers pour cette session.
        </p>

        <div class="closed-divider" aria-hidden="true"></div>

        <a href="{{ route('welcome') }}" class="back-button">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
            Retour à l'accueil
        </a>

        <p class="closed-note">
            <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
            De nouvelles sessions seront bientôt annoncées.
        </p>
    </main>
</body>
</html>