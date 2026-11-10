<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <title>Votre candidature — TIHE'S Group</title>
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
        body { min-width: 320px; margin: 0; background: var(--paper); color: var(--ink); font-family: 'DM Sans', sans-serif; -webkit-font-smoothing: antialiased; }
        a { color: inherit; text-decoration: none; }
        a:focus-visible, input:focus-visible, button:focus-visible { outline: 3px solid var(--gold); outline-offset: 3px; }
        h1, h2, h3, p { margin-top: 0; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }

        /* TOPBAR */
        .topbar { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: var(--white); border-bottom: 1px solid rgba(234, 179, 8, 0.15); }
        .topbar-inner { display: flex; width: min(1120px, calc(100% - 48px)); min-height: 76px; align-items: center; justify-content: space-between; margin: 0 auto; }
        .brand { display: inline-flex; align-items: center; gap: 10px; font-family: 'Manrope', sans-serif; font-size: 1.08rem; font-weight: 800; letter-spacing: -.04em; }
        .brand-logo { width: 142px; height: 63px; padding: 3px; border-radius: 8px; background: var(--white); object-fit: contain; }
        .brand-mark {
            display: grid; width: 38px; height: 38px; place-items: center;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--gold) 0%, #f59e0b 100%);
            color: var(--ink);
            box-shadow: 0 4px 12px rgba(234, 179, 8, 0.4);
        }
        .brand-name span { color: var(--gold); }
        .back-link { display: inline-flex; align-items: center; gap: 9px; color: rgba(255,255,255,.8); font-size: .84rem; font-weight: 600; transition: color .2s ease; }
        .back-link:hover { color: var(--gold); }

        /* PAGE */
        .page { width: min(1120px, calc(100% - 48px)); margin: 0 auto; padding: 52px 0 76px; }
        .intro { max-width: 700px; margin-bottom: 34px; }
        .eyebrow { display: inline-flex; align-items: center; gap: 9px; margin-bottom: 13px; color: var(--gold-dark); font-size: .74rem; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; }
        .eyebrow::before { width: 22px; height: 2px; background: linear-gradient(90deg, var(--gold), var(--emerald)); border-radius: 2px; content: ''; }
        .intro h1 {
            margin-bottom: 12px;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            letter-spacing: -.055em;
            line-height: 1.12;
            background: linear-gradient(135deg, var(--ink) 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .intro p { max-width: 620px; margin-bottom: 0; color: var(--muted); font-size: .98rem; line-height: 1.7; }

        .layout { display: grid; grid-template-columns: minmax(0, 1fr) 300px; align-items: start; gap: 25px; }
        .form-column { display: grid; gap: 18px; }

        /* CARDS */
        .form-card, .side-card { border: 1px solid var(--line); border-radius: var(--radius); background: var(--white); box-shadow: var(--shadow-lg); }
        .form-card { padding: 28px; position: relative; overflow: hidden; }
        .form-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--emerald));
            opacity: 0;
            transition: opacity .3s ease;
        }
        .form-card:hover::before { opacity: 1; }

        .section-heading { display: flex; align-items: flex-start; gap: 15px; margin-bottom: 24px; }
        .section-icon {
            display: grid; width: 46px; height: 46px; flex: 0 0 46px; place-items: center;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--gold-light), #fef3c7);
            color: var(--gold-dark);
            font-size: 1rem;
        }
        .section-heading h2 { margin: 2px 0 5px; font-size: 1.12rem; font-weight: 800; letter-spacing: -.025em; }
        .section-heading p { margin-bottom: 0; color: var(--muted); font-size: .84rem; line-height: 1.55; }

        /* FIELDS */
        .field-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px 16px; }
        .field { min-width: 0; }
        .field-full { grid-column: 1 / -1; }
        .field label { display: block; margin-bottom: 8px; color: var(--ink-soft); font-size: .84rem; font-weight: 700; }
        .optional { color: var(--muted); font-size: .76rem; font-weight: 500; }
        .field-control {
            display: block;
            width: 100%;
            min-height: 48px;
            padding: 11px 14px;
            border: 1.5px solid var(--line);
            border-radius: 9px;
            background: var(--white);
            color: var(--ink);
            font: inherit;
            font-size: .9rem;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .field-control::placeholder { color: #94a3b8; }
        .field-control:hover { border-color: #cbd5e1; }
        .field-control:focus { border-color: var(--gold); outline: 0; box-shadow: 0 0 0 3px rgba(234, 179, 8, .18); }
        .field-control.is-invalid { border-color: var(--rose); }
        .field-hint { display: block; margin-top: 7px; color: var(--muted); font-size: .76rem; line-height: 1.5; }
        .field-error { display: block; margin-top: 6px; color: var(--rose); font-size: .78rem; font-weight: 600; }

        /* FILE INPUTS */
        input[type="file"].field-control { min-height: 50px; padding: 8px; color: var(--muted); font-size: .78rem; }
        input[type="file"]::file-selector-button {
            margin-right: 10px; padding: 9px 12px;
            border: 0;
            border-radius: 6px;
            background: linear-gradient(135deg, var(--gold-light), #fef3c7);
            color: var(--gold-dark);
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s ease;
        }
        input[type="file"]::file-selector-button:hover { background: linear-gradient(135deg, #fef3c7, #fde68a); }
        .upload-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 15px; }
        .upload-item {
            min-width: 0;
            padding: 16px;
            border: 1.5px dashed var(--line);
            border-radius: 10px;
            background: #f8fafc;
            transition: all .2s ease;
        }
        .upload-item:hover { border-color: var(--gold); background: var(--gold-light); }
        .upload-item label { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; color: var(--ink-soft); font-size: .81rem; font-weight: 700; }
        .upload-item label i { color: var(--gold-dark); }
        .upload-item .field-control { background: var(--white); }

        /* ALERTS */
        .alert {
            display: flex; align-items: flex-start; gap: 11px;
            margin-bottom: 20px; padding: 15px 17px;
            border: 1px solid;
            border-radius: 10px;
            font-size: .86rem;
            line-height: 1.55;
        }
        .alert-success { border-color: #a7f3d0; background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: var(--emerald-dark); }
        .alert-danger { border-color: #fecdd3; background: linear-gradient(135deg, #fff1f2, #ffe4e6); color: #be123c; }
        .alert i { margin-top: 2px; }
        .alert ul { margin: 8px 0 0; padding-left: 18px; }

        /* SIDEBAR */
        .side-column { position: sticky; top: 24px; display: grid; gap: 15px; }
        .side-card { padding: 24px; }
        .progress-title { margin-bottom: 18px; font-family: 'Manrope', sans-serif; font-size: .94rem; font-weight: 800; }
        .progress-list { display: grid; gap: 16px; margin: 0; padding: 0; list-style: none; }
        .progress-list li { display: flex; align-items: center; gap: 12px; color: var(--ink-soft); font-size: .82rem; font-weight: 600; }
        .progress-number {
            display: grid; width: 28px; height: 28px; flex: 0 0 28px; place-items: center;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold-light), #fef3c7);
            color: var(--gold-dark);
            font-size: .72rem;
            font-weight: 800;
            border: 1px solid #fde68a;
        }
        .help-card {
            border-color: transparent;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: var(--white);
            position: relative;
            overflow: hidden;
        }
        .help-card::after {
            position: absolute; right: -80px; bottom: -100px;
            width: 220px; height: 220px;
            border: 1px solid rgba(234, 179, 8, .2);
            border-radius: 50%;
            content: ''; pointer-events: none;
        }
        .help-icon {
            display: grid; width: 42px; height: 42px;
            margin-bottom: 15px; place-items: center;
            border-radius: 11px;
            background: rgba(234, 179, 8, .15);
            color: var(--gold);
            position: relative; z-index: 1;
        }
        .help-card h3 { margin-bottom: 8px; font-size: .98rem; position: relative; z-index: 1; }
        .help-card p { margin-bottom: 0; color: rgba(255,255,255,.72); font-size: .82rem; line-height: 1.65; position: relative; z-index: 1; }

        /* SUBMIT AREA */
        .submit-area {
            padding: 24px 28px;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--white);
            box-shadow: var(--shadow-lg);
        }
        .privacy-note { display: flex; align-items: flex-start; gap: 9px; margin-bottom: 18px; color: var(--muted); font-size: .77rem; line-height: 1.5; }
        .privacy-note i { margin-top: 2px; color: var(--emerald-dark); }
        .submit-button {
            display: flex; width: 100%; min-height: 54px;
            align-items: center; justify-content: center; gap: 10px;
            padding: 12px 20px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--gold) 0%, #f59e0b 100%);
            color: var(--ink);
            font: inherit;
            font-size: .92rem;
            font-weight: 800;
            cursor: pointer;
            transition: all .2s ease;
            box-shadow: 0 6px 16px rgba(234, 179, 8, .3);
        }
        .submit-button:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(234, 179, 8, .45); }
        .submit-button i { transition: transform .2s ease; }
        .submit-button:hover i { transform: translateX(3px); }

        .form-footer { display: flex; justify-content: center; padding-top: 22px; color: var(--muted); font-size: .78rem; }
        .form-footer a { color: var(--gold-dark); font-weight: 700; }
        .form-footer a:hover { color: var(--ink); }

        /* RESPONSIVE */
        @media (max-width: 850px) {
            .layout { grid-template-columns: 1fr; }
            .side-column { position: static; grid-row: 1; grid-template-columns: 1fr 1fr; }
            .progress-card { grid-column: 1 / -1; }
        }
        @media (max-width: 560px) {
            .topbar-inner, .page { width: calc(100% - 32px); }
            .topbar-inner { min-height: 66px; }
            .brand { font-size: .98rem; }
            .back-link { gap: 6px; font-size: .75rem; }
            .page { padding: 34px 0 50px; }
            .intro { margin-bottom: 23px; }
            .intro h1 { font-size: 2.15rem; }
            .side-column { grid-template-columns: 1fr; }
            .progress-card { grid-column: auto; }
            .form-card { padding: 21px 18px; }
            .section-heading { gap: 11px; margin-bottom: 20px; }
            .section-heading h2 { font-size: 1.02rem; }
            .field-grid, .upload-grid { grid-template-columns: 1fr; gap: 15px; }
            .field-full { grid-column: auto; }
            .submit-area { padding: 20px 18px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a class="brand" href="{{ route('welcome') }}" aria-label="VTCDrive, accueil">
                <img class="brand-logo" src="{{ asset('images/logo.png') }}" alt="">
                <span class="brand-name">VTC<span>Drive</span></span>
            </a>
            <a class="back-link" href="{{ route('welcome') }}"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour à l'accueil</a>
        </div>
    </header>

    <main class="page">
        <div class="intro">
            <p class="eyebrow">Rejoignez notre équipe</p>
            <h1>Votre candidature commence ici.</h1>
            <p>Complétez vos informations et joignez vos documents. Notre équipe étudiera votre dossier et vous contactera pour la suite.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success" role="status">
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                <div>
                    <strong>Quelques informations sont à vérifier :</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="layout">
            <form class="form-column" action="{{ route('candidature.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <section class="form-card" aria-labelledby="personal-title">
                    <div class="section-heading">
                        <span class="section-icon"><i class="fa-regular fa-user" aria-hidden="true"></i></span>
                        <div>
                            <h2 id="personal-title">Vos informations personnelles</h2>
                            <p>Comment pouvons-nous vous joindre ?</p>
                        </div>
                    </div>
                    <div class="field-grid">
                        <div class="field">
                            <label for="prenom">Prénom <span aria-hidden="true">*</span></label>
                            <input class="field-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" type="text" value="{{ old('prenom') }}" autocomplete="given-name" required>
                            @error('prenom')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label for="nom">Nom <span aria-hidden="true">*</span></label>
                            <input class="field-control @error('nom') is-invalid @enderror" id="nom" name="nom" type="text" value="{{ old('nom') }}" autocomplete="family-name" required>
                            @error('nom')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label for="telephone_whatsapp">Numéro WhatsApp <span aria-hidden="true">*</span></label>
                            <input class="field-control @error('telephone_whatsapp') is-invalid @enderror" id="telephone_whatsapp" name="telephone_whatsapp" type="tel" placeholder="+225 07 00 00 00 00" value="{{ old('telephone_whatsapp') }}" autocomplete="tel" required>
                            @error('telephone_whatsapp')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label for="ville">Commune <span aria-hidden="true">*</span></label>
                            <input class="field-control @error('ville') is-invalid @enderror" id="ville" name="ville" type="text" value="{{ old('ville') }}" autocomplete="address-level2" required>
                            @error('ville')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="field field-full">
                            <label for="email">Adresse e-mail <span class="optional">(facultatif)</span></label>
                            <input class="field-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" placeholder="vous@exemple.com">
                            @error('email')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </section>

                <section class="form-card" aria-labelledby="professional-title">
                    <div class="section-heading">
                        <span class="section-icon"><i class="fa-solid fa-id-card" aria-hidden="true"></i></span>
                        <div>
                            <h2 id="professional-title">Votre expérience professionnelle</h2>
                            <p>Les informations liées à votre expérience de conduite et à votre pièce d'identité.</p>
                        </div>
                    </div>
                    <div class="field-grid">
                        <div class="field">
                            <label for="numero_permis">Numéro de permis <span aria-hidden="true">*</span></label>
                            <input class="field-control @error('numero_permis') is-invalid @enderror" id="numero_permis" name="numero_permis" type="text" value="{{ old('numero_permis') }}" required>
                            @error('numero_permis')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label for="annees_permis">Années de détention du permis <span aria-hidden="true">*</span></label>
                            <input class="field-control @error('annees_permis') is-invalid @enderror" id="annees_permis" name="annees_permis" type="number" min="1" value="{{ old('annees_permis') }}" placeholder="Ex. 5" required>
                            @error('annees_permis')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="field field-full">
                            <label for="numero_piece_identite">Numéro de pièce d'identité <span aria-hidden="true">*</span></label>
                            <input class="field-control @error('numero_piece_identite') is-invalid @enderror" id="numero_piece_identite" name="numero_piece_identite" type="text" value="{{ old('numero_piece_identite') }}" autocomplete="off" required>
                            <span class="field-hint">Indiquez le numéro figurant sur votre CNI ou votre passeport.</span>
                            @error('numero_piece_identite')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </section>

                <section class="form-card" aria-labelledby="documents-title">
                    <div class="section-heading">
                        <span class="section-icon"><i class="fa-solid fa-folder-open" aria-hidden="true"></i></span>
                        <div>
                            <h2 id="documents-title">Vos pièces justificatives</h2>
                            <p>Ajoutez les deux faces de votre permis et une copie de votre pièce d'identité. Fichiers JPG, PNG ou PDF, 2 Mo maximum chacun.</p>
                        </div>
                    </div>
                    <div class="upload-grid">
                        <div class="upload-item">
                            <label for="permis_recto"><i class="fa-regular fa-id-card" aria-hidden="true"></i> Permis de conduire — recto <span aria-hidden="true">*</span></label>
                            <input class="field-control @error('permis_recto') is-invalid @enderror" id="permis_recto" name="permis_recto" type="file" accept=".jpeg,.png,.jpg,.pdf" required>
                            @error('permis_recto')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="upload-item">
                            <label for="permis_verso"><i class="fa-regular fa-id-card" aria-hidden="true"></i> Permis de conduire — verso <span aria-hidden="true">*</span></label>
                            <input class="field-control @error('permis_verso') is-invalid @enderror" id="permis_verso" name="permis_verso" type="file" accept=".jpeg,.png,.jpg,.pdf" required>
                            @error('permis_verso')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="upload-item">
                            <label for="piece_identite"><i class="fa-regular fa-address-card" aria-hidden="true"></i> Pièce d'identité — CNI ou passeport <span aria-hidden="true">*</span></label>
                            <input class="field-control @error('piece_identite') is-invalid @enderror" id="piece_identite" name="piece_identite" type="file" accept=".jpeg,.png,.jpg,.pdf" required>
                            @error('piece_identite')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </section>

                <div class="submit-area">
                    <p class="privacy-note"><i class="fa-solid fa-clipboard-check" aria-hidden="true"></i><span>Les informations et documents fournis servent à l'étude de votre candidature par notre équipe de recrutement.</span></p>
                    <button class="submit-button" type="submit">Envoyer ma candidature <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button>
                </div>
            </form>

            <aside class="side-column" aria-label="Aide à la candidature">
                <div class="side-card progress-card">
                    <h2 class="progress-title">Votre dossier en 3 étapes</h2>
                    <ol class="progress-list">
                        <li><span class="progress-number">1</span> Vos coordonnées</li>
                        <li><span class="progress-number">2</span> Votre expérience VTC</li>
                        <li><span class="progress-number">3</span> Vos justificatifs</li>
                    </ol>
                </div>
                <div class="side-card help-card">
                    <span class="help-icon"><i class="fa-regular fa-lightbulb" aria-hidden="true"></i></span>
                    <h3>Un conseil avant l'envoi</h3>
                    <p>Vérifiez que vos documents sont bien cadrés, lisibles et que toutes leurs informations sont visibles.</p>
                </div>
            </aside>
        </div>

        <footer class="form-footer">
            <span>Besoin de faire une pause ? <a href="{{ route('welcome') }}">Retourner à l'accueil</a></span>
        </footer>
    </main>
</body>
</html>