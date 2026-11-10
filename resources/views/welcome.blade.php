<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rejoignez TIHE'S Group : une candidature chauffeur VTC simple, 100 % en ligne et accompagnée par notre équipe.">
    <meta name="theme-color" content="#0f172a">
    <title>TIHE'S Group — Prenez la route de votre réussite</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
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
            --shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.05), 0 2px 4px -2px rgba(15, 23, 42, 0.05);
            --shadow-lg: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; background: var(--paper); color: var(--ink); font-family: 'DM Sans', sans-serif; -webkit-font-smoothing: antialiased; }
        a { color: inherit; text-decoration: none; }
        .skip-link { position: absolute; z-index: 5; top: 12px; left: -9999px; padding: 12px 16px; border-radius: 8px; background: var(--white); color: var(--ink); }
        .skip-link:focus { left: 12px; }
        a:focus-visible, summary:focus-visible { outline: 3px solid var(--gold); outline-offset: 4px; }
        .container { width: min(1120px, calc(100% - 48px)); margin: 0 auto; }
        .eyebrow { display: inline-flex; align-items: center; gap: 9px; color: var(--gold-dark); font-size: .75rem; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; }
        .eyebrow::before { width: 22px; height: 2px; background: linear-gradient(90deg, var(--gold), var(--emerald)); border-radius: 2px; content: ''; }
        .button {
            display: inline-flex; min-height: 54px;
            align-items: center; justify-content: center; gap: 10px;
            padding: 0 24px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--gold) 0%, #f59e0b 100%);
            color: var(--ink);
            font-size: .9rem;
            font-weight: 800;
            transition: all .2s ease;
            box-shadow: 0 6px 16px rgba(234, 179, 8, .3);
        }
        .button:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(234, 179, 8, .45); }
        .button i { transition: transform .2s ease; }
        .button:hover i { transform: translateX(3px); }

        /* HEADER */
        .site-header { position: absolute; z-index: 2; top: 0; right: 0; left: 0; color: var(--white); }
        .nav-wrap { display: flex; min-height: 88px; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,.15); }
        .brand { display: inline-flex; align-items: center; gap: 11px; font-family: 'Manrope', sans-serif; font-size: 1.15rem; font-weight: 800; letter-spacing: -.04em; }
        .brand-logo { width: 150px; height: 67px; padding: 3px; border-radius: 8px; background: var(--white); object-fit: contain; }
        .brand-mark {
            display: grid; width: 40px; height: 40px; place-items: center;
            border-radius: 11px;
            background: linear-gradient(135deg, var(--gold) 0%, #f59e0b 100%);
            color: var(--ink);
            font-size: .95rem;
            box-shadow: 0 4px 14px rgba(234, 179, 8, 0.4);
        }
        .brand span span { color: var(--gold); }
        .nav-links { display: flex; align-items: center; gap: 32px; color: rgba(255,255,255,.8); font-size: .84rem; font-weight: 600; }
        .nav-links a:not(.nav-login):hover { color: var(--gold); }
        .nav-login {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 11px 16px;
            border: 1px solid rgba(255,255,255,.28);
            border-radius: 8px;
            color: var(--white);
            transition: all .2s ease;
        }
        .nav-login:hover { border-color: var(--gold); color: var(--gold); background: rgba(234, 179, 8, 0.1); }

        /* HERO */
        .hero {
            position: relative;
            display: flex; min-height: 740px;
            align-items: center;
            overflow: hidden;
            background:
                linear-gradient(90deg, rgba(15, 23, 42, .97) 0%, rgba(15, 23, 42, .89) 42%, rgba(15, 23, 42, .3) 100%),
                linear-gradient(0deg, rgba(15, 23, 42, .45), transparent 55%),
                url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=2200&q=85') center 53% / cover;
            color: var(--white);
        }
        .hero::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--emerald), var(--gold));
            z-index: 2;
        }
        .hero-content { max-width: 650px; padding: 145px 0 90px; }
        .hero .eyebrow { color: var(--gold); }
        .hero .eyebrow::before { background: linear-gradient(90deg, var(--gold), var(--emerald)); }
        .availability {
            display: inline-flex; align-items: center; gap: 9px;
            margin-bottom: 26px; padding: 9px 14px;
            border: 1px solid rgba(234, 179, 8, .3);
            border-radius: 99px;
            background: rgba(234, 179, 8, .1);
            color: #fef3c7;
            font-size: .76rem;
            font-weight: 600;
        }
        .availability i { color: var(--gold); font-size: .55rem; }
        h1, h2, h3, p { margin-top: 0; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
        .hero h1 { max-width: 640px; margin: 20px 0; font-size: clamp(2.8rem, 6vw, 5rem); font-weight: 800; letter-spacing: -.065em; line-height: 1.06; }
        .hero h1 span {
            background: linear-gradient(135deg, var(--gold) 0%, #fbbf24 50%, var(--emerald) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-copy { max-width: 540px; margin-bottom: 32px; color: rgba(255,255,255,.75); font-size: 1.08rem; line-height: 1.8; }
        .hero-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 20px; }
        .text-link { display: inline-flex; align-items: center; gap: 9px; color: var(--white); font-size: .88rem; font-weight: 600; transition: color .2s ease; }
        .text-link:hover { color: var(--gold); }
        .hero-note { display: flex; align-items: center; gap: 10px; margin-top: 40px; color: rgba(255,255,255,.7); font-size: .8rem; }
        .hero-note i { color: var(--gold); }
        .hero-note strong { color: var(--white); }

        /* TRUST STRIP */
        .trust-strip { position: relative; z-index: 1; margin-top: -1px; background: var(--white); }
        .trust-inner { display: grid; grid-template-columns: repeat(3, 1fr); padding: 26px 0; border-bottom: 1px solid var(--line); }
        .trust-item { display: flex; align-items: center; justify-content: center; gap: 12px; color: var(--ink-soft); font-size: .84rem; font-weight: 600; }
        .trust-item + .trust-item { border-left: 1px solid var(--line); }
        .trust-item i { color: var(--gold-dark); font-size: 1.1rem; }

        /* SECTIONS */
        .section { padding: 105px 0; }
        .section-heading { max-width: 650px; margin-bottom: 45px; }
        .section-heading h2 { margin: 14px 0 13px; font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; letter-spacing: -.055em; line-height: 1.12; }
        .requirements-title { margin: 14px 0 13px; font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; letter-spacing: -.055em; line-height: 1.12; }
        .requirements-copy { max-width: 520px; margin: 0; color: var(--muted); font-size: 1rem; line-height: 1.75; }
        .section-heading p { max-width: 560px; margin-bottom: 0; color: var(--muted); font-size: 1rem; line-height: 1.75; }

        /* BENEFITS */
        .benefit-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .benefit-card {
            min-height: 260px;
            padding: 32px;
            border: 1px solid var(--line);
            border-radius: var(--radius, 14px);
            background: var(--white);
            transition: all .25s ease;
            position: relative;
            overflow: hidden;
        }
        .benefit-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--emerald));
            opacity: 0;
            transition: opacity .3s ease;
        }
        .benefit-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); border-color: var(--gold); }
        .benefit-card:hover::before { opacity: 1; }
        .icon-box {
            display: grid; width: 50px; height: 50px;
            margin-bottom: 26px; place-items: center;
            border-radius: 13px;
            background: linear-gradient(135deg, var(--gold-light), #fef3c7);
            color: var(--gold-dark);
            font-size: 1.18rem;
            transition: all .25s ease;
        }
        .benefit-card:hover .icon-box {
            background: linear-gradient(135deg, var(--gold), #f59e0b);
            color: var(--ink);
        }
        .benefit-card h3 { margin-bottom: 10px; font-size: 1.05rem; font-weight: 700; letter-spacing: -.02em; }
        .benefit-card p { margin-bottom: 0; color: var(--muted); font-size: .9rem; line-height: 1.7; }

        /* PROCESS */
        .process { background: #eef1ea; }
        .process-layout { display: grid; grid-template-columns: .85fr 1.15fr; align-items: start; gap: 90px; }
        .process .section-heading { position: sticky; top: 36px; margin-bottom: 0; }
        .steps { display: grid; gap: 0; }
        .step { display: grid; grid-template-columns: 54px 1fr; gap: 20px; padding: 0 0 34px; }
        .step + .step { padding-top: 30px; border-top: 1px solid #dce2d5; }
        .step-number {
            display: grid; width: 48px; height: 48px; place-items: center;
            border-radius: 50%;
            background: var(--white);
            color: var(--gold-dark);
            font-family: 'Manrope', sans-serif;
            font-size: .9rem;
            font-weight: 800;
            border: 1px solid #fde68a;
            box-shadow: 0 4px 10px rgba(234, 179, 8, .1);
        }
        .step h3 { margin: 3px 0 8px; font-size: 1.08rem; }
        .step p { margin-bottom: 0; color: var(--muted); font-size: .91rem; line-height: 1.7; }

        /* REQUIREMENTS */
        .requirements-layout { display: grid; grid-template-columns: 1fr .85fr; align-items: center; gap: 80px; }
        .requirements-list { display: grid; gap: 17px; margin: 28px 0 0; padding: 0; list-style: none; }
        .requirements-list li { display: flex; align-items: flex-start; gap: 13px; color: var(--ink-soft); font-size: .93rem; line-height: 1.6; }
        .requirements-list i { margin-top: 4px; color: var(--emerald); }
        .prep-card {
            position: relative;
            padding: 36px;
            overflow: hidden;
            border-radius: var(--radius, 14px);
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: var(--white);
            box-shadow: var(--shadow-lg);
        }
        .prep-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--emerald));
        }
        .prep-card::after {
            position: absolute; right: -80px; bottom: -100px;
            width: 240px; height: 240px;
            border: 1px solid rgba(234, 179, 8, .2);
            border-radius: 50%;
            content: '';
            pointer-events: none;
        }
        .prep-card .icon-box { background: rgba(234, 179, 8, .15); color: var(--gold); position: relative; z-index: 1; }
        .prep-card:hover .icon-box { background: linear-gradient(135deg, var(--gold), #f59e0b); color: var(--ink); }
        .prep-card h3 { max-width: 270px; margin-bottom: 12px; font-size: 1.45rem; letter-spacing: -.04em; position: relative; z-index: 1; }
        .prep-card p { margin-bottom: 24px; color: rgba(255,255,255,.72); font-size: .9rem; line-height: 1.7; position: relative; z-index: 1; }
        .prep-card .button { position: relative; z-index: 1; width: 100%; }

        /* FAQ */
        .faq { padding-top: 10px; }
        .faq-layout { display: grid; grid-template-columns: .8fr 1.2fr; gap: 90px; }
        .faq .section-heading { margin-bottom: 0; }
        .faq-list { border-top: 1px solid var(--line); }
        .faq-item { border-bottom: 1px solid var(--line); }
        .faq-item summary {
            display: flex; align-items: center; justify-content: space-between; gap: 20px;
            padding: 22px 0;
            cursor: pointer;
            font-family: 'Manrope', sans-serif;
            font-size: .96rem;
            font-weight: 700;
            list-style: none;
            transition: color .2s ease;
        }
        .faq-item summary:hover { color: var(--gold-dark); }
        .faq-item summary::-webkit-details-marker { display: none; }
        .faq-item summary i { color: var(--gold-dark); font-size: .82rem; transition: transform .2s ease; }
        .faq-item[open] summary i { transform: rotate(45deg); }
        .faq-item p { max-width: 600px; margin: -4px 0 22px; color: var(--muted); font-size: .9rem; line-height: 1.75; }

        /* CLOSING */
        .closing { padding: 0 0 100px; }
        .closing-panel {
            display: flex; align-items: center; justify-content: space-between; gap: 35px;
            padding: 50px 58px;
            border-radius: var(--radius, 14px);
            background: linear-gradient(135deg, var(--gold) 0%, #f59e0b 100%);
            box-shadow: 0 20px 40px rgba(234, 179, 8, .3);
            position: relative;
            overflow: hidden;
        }
        .closing-panel::after {
            content: '';
            position: absolute; top: -100px; right: -100px;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,.2) 0%, transparent 70%);
            pointer-events: none;
        }
        .closing-panel h2 { max-width: 580px; margin-bottom: 9px; font-size: clamp(1.7rem, 4vw, 2.45rem); font-weight: 800; letter-spacing: -.055em; line-height: 1.15; position: relative; z-index: 1; }
        .closing-panel p { margin-bottom: 0; color: rgba(15, 23, 42, .75); font-size: .93rem; position: relative; z-index: 1; }
        .closing-panel .button { flex: 0 0 auto; background: linear-gradient(135deg, var(--ink) 0%, #334155 100%); color: var(--white); box-shadow: 0 6px 16px rgba(15, 23, 42, .3); position: relative; z-index: 1; }
        .closing-panel .button:hover { background: linear-gradient(135deg, #1e293b 0%, #475569 100%); box-shadow: 0 10px 24px rgba(15, 23, 42, .4); }

        /* FOOTER */
        .site-footer { padding: 28px 0; border-top: 1px solid var(--line); background: var(--white); }
        .footer-inner { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .footer-brand { color: var(--ink); font-size: 1rem; }
        .footer-brand .brand-logo { width: 130px; height: 58px; }
        .footer-copy { margin: 0; color: var(--muted); font-size: .8rem; }
        .footer-login { color: var(--muted); font-size: .82rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; transition: color .2s ease; }
        .footer-login:hover { color: var(--gold-dark); }

        /* RESPONSIVE */
        @media (max-width: 800px) {
            .nav-wrap { min-height: 76px; }
            .nav-links { gap: 16px; }
            .nav-links > a:not(.nav-login) { display: none; }
            .hero { min-height: 700px; background-position: 60% center; }
            .hero-content { padding-top: 130px; }
            .benefit-grid { grid-template-columns: 1fr; }
            .benefit-card { min-height: auto; }
            .process-layout, .requirements-layout, .faq-layout { grid-template-columns: 1fr; gap: 45px; }
            .process .section-heading { position: static; }
            .section { padding: 78px 0; }
            .faq { padding-top: 0; }
            .closing { padding-bottom: 78px; }
        }
        @media (max-width: 560px) {
            .container { width: min(100% - 36px, 1120px); }
            .brand { gap: 7px; font-size: 1.02rem; }
            .brand-logo { width: 105px; height: 47px; padding: 2px; }
            .nav-login { padding: 10px 12px; font-size: .76rem; }
            .hero { min-height: 690px; background-position: 64% center; }
            .hero-content { padding: 125px 0 70px; }
            .hero h1 { font-size: clamp(2.55rem, 13vw, 3.65rem); }
            .hero-copy { font-size: .98rem; }
            .hero-actions { align-items: flex-start; flex-direction: column; }
            .trust-inner { grid-template-columns: 1fr; gap: 17px; padding: 22px 0; }
            .trust-item { justify-content: flex-start; }
            .trust-item + .trust-item { padding-top: 15px; border-top: 1px solid var(--line); border-left: 0; }
            .section { padding: 68px 0; }
            .section-heading { margin-bottom: 32px; }
            .benefit-card { padding: 26px; }
            .prep-card { padding: 28px; }
            .closing { padding-bottom: 68px; }
            .closing-panel { align-items: flex-start; flex-direction: column; padding: 34px 26px; }
            .closing-panel .button { width: 100%; }
            .footer-inner { align-items: flex-start; flex-direction: column; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <a class="skip-link" href="#contenu">Aller au contenu</a>
    <header class="site-header">
        <nav class="container nav-wrap" aria-label="Navigation principale">
            <a class="brand" href="{{ route('welcome') }}" aria-label="TIHE'S Group, accueil">
                <img class="brand-logo" src="{{ asset('images/logo.png') }}" alt="">
                <span>TIHE'S <span>Group</span></span>
            </a>
            <div class="nav-links">
                <a href="#avantages">Pourquoi TIHE'S Group</a>
                <a href="#parcours">Les étapes</a>
                <a href="#questions">Questions</a>
                <a class="nav-login" href="{{ route('login') }}"><i class="fa-solid fa-lock" aria-hidden="true"></i> Espace recruteur</a>
            </div>
        </nav>
    </header>

    <main id="contenu">
        <section class="hero" aria-labelledby="hero-title">
            <div class="container">
                <div class="hero-content">
                    <div class="availability"><i class="fa-solid fa-circle" aria-hidden="true"></i> Recrutement ouvert · Session 2026</div>
                    <p class="eyebrow">Votre prochain trajet commence ici</p>
                    <h1 id="hero-title">Prenez la route de votre réussite.</h1>
                    <p class="hero-copy">Rejoignez une équipe qui valorise votre métier. Candidatez en ligne, depuis votre téléphone, et échangez directement avec notre équipe de recrutement.</p>
                    <div class="hero-actions">
                        <a class="button" href="{{ route('candidature.create') }}">Déposer ma candidature <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                        <a class="text-link" href="#parcours">Découvrir le parcours <i class="fa-solid fa-arrow-down" aria-hidden="true"></i></a>
                    </div>
                    <p class="hero-note"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> <span>Un dossier transmis en toute simplicité, <strong>100 % en ligne.</strong></span></p>
                </div>
            </div>
        </section>

        <section class="trust-strip" aria-label="Les atouts de la candidature">
            <div class="container trust-inner">
                <div class="trust-item"><i class="fa-solid fa-mobile-screen-button" aria-hidden="true"></i> Candidature depuis votre mobile</div>
                <div class="trust-item"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Un échange direct avec l'équipe</div>
                <div class="trust-item"><i class="fa-solid fa-file-circle-check" aria-hidden="true"></i> Vos documents au même endroit</div>
            </div>
        </section>

        <section class="section" id="avantages">
            <div class="container">
                <div class="section-heading">
                    <p class="eyebrow">L'expérience TIHE'S Group</p>
                    <h2>Un accompagnement pensé pour les chauffeurs.</h2>
                    <p>Votre temps compte. Nous avons simplifié les démarches pour que vous puissiez vous concentrer sur l'essentiel : votre activité.</p>
                </div>
                <div class="benefit-grid">
                    <article class="benefit-card">
                        <div class="icon-box"><i class="fa-solid fa-route" aria-hidden="true"></i></div>
                        <h3>Un parcours sans détour</h3>
                        <p>Remplissez votre dossier en ligne et transmettez vos pièces justificatives en quelques étapes claires.</p>
                    </article>
                    <article class="benefit-card">
                        <div class="icon-box"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></div>
                        <h3>Un contact humain</h3>
                        <p>Notre équipe reste disponible pour vous guider et échanger avec vous au fil du recrutement.</p>
                    </article>
                    <article class="benefit-card">
                        <div class="icon-box"><i class="fa-solid fa-handshake-angle" aria-hidden="true"></i></div>
                        <h3>Un suivi de votre dossier</h3>
                        <p>Votre candidature est transmise à notre équipe pour examen. Vous êtes informé des prochaines étapes.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section process" id="parcours">
            <div class="container process-layout">
                <div class="section-heading">
                    <p class="eyebrow">Comment ça marche</p>
                    <h2>Trois étapes pour rejoindre l'aventure.</h2>
                    <p>Préparez vos documents, complétez le formulaire et notre équipe prendra le relais.</p>
                </div>
                <div class="steps">
                    <article class="step">
                        <span class="step-number">01</span>
                        <div><h3>Préparez vos documents</h3><p>Munissez-vous de votre permis de conduire et d'une pièce d'identité en cours de validité.</p></div>
                    </article>
                    <article class="step">
                        <span class="step-number">02</span>
                        <div><h3>Complétez votre dossier</h3><p>Renseignez vos informations et téléversez vos justificatifs depuis votre téléphone ou votre ordinateur.</p></div>
                    </article>
                    <article class="step">
                        <span class="step-number">03</span>
                        <div><h3>Échangez avec notre équipe</h3><p>Après étude de votre candidature, notre équipe vous contactera pour vous informer de la suite.</p></div>
                    </article>
                </div>
            </div>
        </section>

        <section class="section" id="conditions">
            <div class="container requirements-layout">
                <div>
                    <p class="eyebrow">Avant de commencer</p>
                    <h2 class="requirements-title">Votre dossier, prêt à partir.</h2>
                    <p class="requirements-copy">Pour faciliter l'étude de votre candidature, gardez ces éléments à portée de main :</p>
                    <ul class="requirements-list">
                        <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Un permis de conduire valide et les informations associées.</span></li>
                        <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Le numéro de votre pièce d'identité (CNI ou passeport).</span></li>
                        <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Une pièce d'identité officielle : CNI ou passeport.</span></li>
                        <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Un numéro WhatsApp actif pour rester joignable.</span></li>
                    </ul>
                </div>
                <aside class="prep-card">
                    <div class="icon-box"><i class="fa-solid fa-clipboard-check" aria-hidden="true"></i></div>
                    <h3>Prêt à prendre la route ?</h3>
                    <p>Le formulaire vous guidera étape par étape pour déposer votre candidature et vos justificatifs.</p>
                    <a class="button" href="{{ route('candidature.create') }}">Commencer ma candidature <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                </aside>
            </div>
        </section>

        <section class="section faq" id="questions">
            <div class="container faq-layout">
                <div class="section-heading">
                    <p class="eyebrow">Questions fréquentes</p>
                    <h2>Vous avez des questions ?</h2>
                    <p>Voici quelques repères pour préparer votre candidature sereinement.</p>
                </div>
                <div class="faq-list">
                    <details class="faq-item">
                        <summary>Comment déposer ma candidature ? <i class="fa-solid fa-plus" aria-hidden="true"></i></summary>
                        <p>Cliquez sur « Déposer ma candidature », renseignez le formulaire et ajoutez les documents demandés. Vous pouvez effectuer ces étapes depuis un téléphone ou un ordinateur.</p>
                    </details>
                    <details class="faq-item">
                        <summary>Quels documents dois-je fournir ? <i class="fa-solid fa-plus" aria-hidden="true"></i></summary>
                        <p>Le formulaire demande les deux faces de votre permis, le numéro de votre pièce d'identité et une copie de celle-ci. Prévoyez des fichiers lisibles au format image ou PDF.</p>
                    </details>
                    <details class="faq-item">
                        <summary>Comment serai-je informé de la suite ? <i class="fa-solid fa-plus" aria-hidden="true"></i></summary>
                        <p>L'équipe de recrutement étudie votre dossier et vous contactera via les coordonnées indiquées dans le formulaire, notamment WhatsApp.</p>
                    </details>
                </div>
            </div>
        </section>

        <section class="closing">
            <div class="container">
                <div class="closing-panel">
                    <div><h2>La prochaine étape de votre carrière commence au volant.</h2><p>Déposez votre dossier dès maintenant et échangeons sur votre projet.</p></div>
                    <a class="button" href="{{ route('candidature.create') }}">Je candidate <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <a class="brand footer-brand" href="{{ route('welcome') }}">
                <img class="brand-logo" src="{{ asset('images/logo.png') }}" alt="">
                <span>TIHE'S <span>Group</span></span>
            </a>
            <p class="footer-copy">&copy; {{ date('Y') }} TIHE'S Group. Tous droits réservés.</p>
            <a class="footer-login" href="{{ route('login') }}">Espace recruteur <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </div>
    </footer>
</body>
</html>