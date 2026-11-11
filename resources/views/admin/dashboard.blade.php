<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <title>Dashboard recruteur — TIHE'S Group</title>
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
            --rose: #f43f5e;
            --rose-light: #ffe4e6;
            --violet: #8b5cf6;
            --violet-light: #ede9fe;
            --sky: #0ea5e9;
            --sky-light: #e0f2fe;
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
        a:focus-visible, button:focus-visible { outline: 3px solid var(--gold); outline-offset: 3px; }
        h1, h2, h3, p { margin-top: 0; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }

        /* TOPBAR */
        .topbar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: var(--white);
            border-bottom: 1px solid rgba(234, 179, 8, 0.15);
        }
        .topbar-inner { display: flex; width: min(1180px, calc(100% - 48px)); min-height: 76px; align-items: center; justify-content: space-between; gap: 20px; margin: 0 auto; }
        .brand { display: inline-flex; align-items: center; gap: 10px; font-family: 'Manrope', sans-serif; font-size: 1.08rem; font-weight: 800; letter-spacing: -.04em; }
        .brand-mark {
            display: grid; width: 38px; height: 38px; place-items: center;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--gold) 0%, #f59e0b 100%);
            color: var(--ink);
            box-shadow: 0 4px 12px rgba(234, 179, 8, 0.4);
        }
        .brand-name span { color: var(--gold); }
        .topbar-actions { display: flex; align-items: center; gap: 10px; }
        .topbar-link, .logout-button {
            display: inline-flex; min-height: 39px; align-items: center; justify-content: center; gap: 8px;
            padding: 0 15px;
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 8px;
            background: rgba(255,255,255,.05);
            color: rgba(255,255,255,.85);
            font: inherit;
            font-size: .79rem;
            font-weight: 600;
            transition: all .2s ease;
            cursor: pointer;
        }
        .topbar-link:hover { border-color: var(--gold); color: var(--gold); background: rgba(234, 179, 8, 0.1); }
        .logout-button { border-color: rgba(244, 63, 94, .25); }
        .logout-button:hover { border-color: var(--rose); background: rgba(244, 63, 94, .12); color: #fda4af; }

        /* PAGE */
        .page { width: min(1180px, calc(100% - 48px)); margin: 0 auto; padding: 40px 0 70px; }

        /* WELCOME PANEL */
        .welcome-panel {
            position: relative;
            display: flex; min-height: 240px;
            align-items: center; justify-content: space-between; gap: 28px;
            padding: 40px 46px;
            overflow: hidden;
            border-radius: var(--radius);
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: var(--white);
            box-shadow: var(--shadow-lg);
        }
        .welcome-panel::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--emerald), var(--gold));
        }
        .welcome-panel::after {
            position: absolute; top: -190px; right: 90px;
            width: 430px; height: 430px;
            border: 1px solid rgba(234, 179, 8, .18);
            border-radius: 50%;
            box-shadow: 0 0 0 45px rgba(234, 179, 8, .04), 0 0 0 90px rgba(16, 185, 129, .025);
            content: ''; pointer-events: none;
        }
        .welcome-copy, .welcome-action { position: relative; z-index: 1; }
        .eyebrow {
            display: inline-flex; align-items: center; gap: 9px;
            margin-bottom: 12px;
            color: var(--gold);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .13em;
            text-transform: uppercase;
        }
        .eyebrow::before { width: 22px; height: 2px; background: linear-gradient(90deg, var(--gold), var(--emerald)); border-radius: 2px; content: ''; }
        .welcome-panel h1 { max-width: 640px; margin-bottom: 12px; font-size: clamp(1.9rem, 4vw, 2.7rem); font-weight: 800; letter-spacing: -.06em; line-height: 1.12; }
        .welcome-panel p { max-width: 600px; margin-bottom: 0; color: rgba(255,255,255,.7); font-size: .9rem; line-height: 1.7; }
        .primary-link {
            display: inline-flex; min-height: 48px; align-items: center; justify-content: center; gap: 9px;
            padding: 0 20px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--gold) 0%, #f59e0b 100%);
            color: var(--ink);
            font-size: .82rem;
            font-weight: 800;
            white-space: nowrap;
            transition: all .2s ease;
            box-shadow: 0 6px 20px rgba(234, 179, 8, 0.35);
        }
        .primary-link:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(234, 179, 8, 0.5); }

        /* SECTION HEADING */
        .section-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 18px; margin: 38px 0 18px; }
        .section-heading h2 { margin: 0 0 5px; font-size: 1.15rem; font-weight: 800; letter-spacing: -.03em; }
        .section-heading p { margin: 0; color: var(--muted); font-size: .81rem; }
        .subtle-link { display: inline-flex; align-items: center; gap: 7px; color: var(--gold-dark); font-size: .8rem; font-weight: 700; white-space: nowrap; transition: color .2s ease; }
        .subtle-link:hover { color: var(--ink); }

        /* STATS GRID */
        .stats-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 15px; }
        .stat-card {
            display: block; min-height: 145px;
            padding: 20px;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--white);
            box-shadow: var(--shadow);
            transition: all .25s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--emerald));
            opacity: 0;
            transition: opacity .25s ease;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); border-color: var(--gold); }
        .stat-card:hover::before { opacity: 1; }
        .stat-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
        .stat-label { color: var(--muted); font-size: .75rem; font-weight: 600; }
        .stat-icon {
            display: grid; width: 36px; height: 36px; place-items: center;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--gold-light), #fef3c7);
            color: var(--gold-dark);
            font-size: .85rem;
        }
        .stat-number {
            display: block; margin-top: 16px;
            color: var(--ink);
            font-family: 'Manrope', sans-serif;
            font-size: 1.95rem;
            font-weight: 800;
            letter-spacing: -.05em;
            line-height: 1;
        }
        .stat-hint { display: block; margin-top: 8px; color: #94a3b8; font-size: .7rem; }
        .stat-card.pending .stat-icon { background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #b45309; }
        .stat-card.review .stat-icon { background: linear-gradient(135deg, #f0f9ff, #e0f2fe); color: #0369a1; }
        .stat-card.interview .stat-icon { background: linear-gradient(135deg, #f5f3ff, #ede9fe); color: #6d28d9; }
        .stat-card.accepted .stat-icon { background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #047857; }
        .stat-card.rejected .stat-icon { background: linear-gradient(135deg, #fff1f2, #ffe4e6); color: #be123c; }

        /* CONTENT GRID */
        .content-grid { display: grid; grid-template-columns: minmax(0, 1.5fr) minmax(260px, .7fr); align-items: start; gap: 20px; margin-top: 30px; }
        .panel {
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--white);
            box-shadow: var(--shadow-lg);
        }
        .panel-heading {
            display: flex; align-items: center; justify-content: space-between; gap: 14px;
            padding: 20px 22px;
            border-bottom: 1px solid var(--line);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }
        .panel-heading h2 { margin: 0 0 4px; font-size: .96rem; font-weight: 800; letter-spacing: -.02em; }
        .panel-heading p { margin: 0; color: var(--muted); font-size: .76rem; }

        /* RECENT LIST */
        .recent-list { margin: 0; padding: 0; list-style: none; }
        .recent-item + .recent-item { border-top: 1px solid #f1f5f9; }
        .recent-link { display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 15px 22px; transition: background .15s ease; }
        .recent-link:hover { background: #f8fafc; }
        .recent-person { display: flex; min-width: 0; align-items: center; gap: 12px; }
        .avatar {
            display: grid; width: 38px; height: 38px; flex: 0 0 38px; place-items: center;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--emerald-light), #a7f3d0);
            color: var(--emerald-dark);
            font-family: 'Manrope', sans-serif;
            font-size: .74rem;
            font-weight: 800;
            border: 2px solid var(--white);
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
        }
        .candidate-name { display: block; overflow: hidden; color: var(--ink); font-size: .82rem; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
        .candidate-meta { display: block; margin-top: 4px; color: var(--muted); font-size: .71rem; }

        /* STATUS BADGES */
        .status {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 10px;
            border: 1px solid transparent;
            border-radius: 99px;
            font-size: .68rem;
            font-weight: 700;
            white-space: nowrap;
        }
        .status::before { width: 6px; height: 6px; border-radius: 50%; background: currentColor; content: ''; box-shadow: 0 0 0 3px rgba(255,255,255,0.8); }
        .status-pending { border-color: #fde68a; background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #b45309; }
        .status-review { border-color: #bae6fd; background: linear-gradient(135deg, #f0f9ff, #e0f2fe); color: #0369a1; }
        .status-interview { border-color: #ddd6fe; background: linear-gradient(135deg, #f5f3ff, #ede9fe); color: #6d28d9; }
        .status-accepted { border-color: #a7f3d0; background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #047857; }
        .status-rejected { border-color: #fecdd3; background: linear-gradient(135deg, #fff1f2, #ffe4e6); color: #be123c; }

        /* EMPTY STATE */
        .empty-state { padding: 40px 20px; text-align: center; }
        .empty-icon {
            display: grid; width: 48px; height: 48px; margin: 0 auto 14px; place-items: center;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--gold-light), #fef3c7);
            color: var(--gold-dark);
        }
        .empty-state h3 { margin-bottom: 6px; font-size: .9rem; }
        .empty-state p { margin: 0; color: var(--muted); font-size: .78rem; }

        /* SHORTCUTS */
        .shortcuts { display: grid; gap: 10px; padding: 18px; }
        .shortcut {
            display: flex; align-items: center; gap: 13px;
            padding: 14px;
            border: 1px solid var(--line);
            border-radius: 10px;
            transition: all .2s ease;
        }
        .shortcut:hover { border-color: var(--gold); background: var(--gold-light); transform: translateX(2px); }
        .shortcut-icon {
            display: grid; width: 40px; height: 40px; flex: 0 0 40px; place-items: center;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--gold-light), #fef3c7);
            color: var(--gold-dark);
            transition: all .2s ease;
        }
        .shortcut:hover .shortcut-icon {
            background: linear-gradient(135deg, var(--gold), #f59e0b);
            color: var(--ink);
        }
        .shortcut strong { display: block; margin-bottom: 3px; color: var(--ink); font-size: .79rem; }
        .shortcut span { display: block; color: var(--muted); font-size: .71rem; line-height: 1.4; }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .content-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 600px) {
            .topbar-inner, .page { width: calc(100% - 32px); }
            .topbar-inner { min-height: 68px; }
            .brand { font-size: .98rem; }
            .topbar-actions { gap: 7px; }
            .topbar-link, .logout-button { min-height: 36px; padding: 0 10px; font-size: .72rem; }
            .topbar-link span, .logout-button span { display: none; }
            .page { padding: 28px 0 48px; }
            .welcome-panel { align-items: flex-start; flex-direction: column; padding: 28px 24px; }
            .welcome-panel::after { right: -200px; }
            .welcome-panel h1 { font-size: 1.9rem; }
            .section-heading { align-items: flex-start; flex-direction: column; margin-top: 29px; }
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
            .stat-card { min-height: 128px; padding: 15px; }
            .stat-number { font-size: 1.65rem; }
            .panel-heading { padding: 16px; }
            .recent-link { align-items: flex-start; padding: 13px 16px; }
            .status { max-width: 112px; overflow: hidden; text-overflow: ellipsis; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
    @include('admin.partials.sidebar-styles')
</head>
<body>
    <div class="admin-shell">
        @include('admin.partials.sidebar')
        <div class="admin-main">
            <main class="page">
        <section class="welcome-panel" aria-labelledby="dashboard-title">
            <div class="welcome-copy">
                <p class="eyebrow">Espace recruteur</p>
                <h1 id="dashboard-title">Bonjour, bienvenue sur votre dashboard.</h1>
                <p>Retrouvez en un coup d'œil l'activité de recrutement et accédez rapidement aux dossiers à traiter.</p>
            </div>
            <a class="primary-link welcome-action" href="{{ route('admin.candidatures.index') }}">Voir toutes les candidatures <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </section>

        @php
            $stats = [
                ['label' => 'En attente', 'status' => 'en_attente', 'icon' => 'fa-hourglass-half', 'class' => 'pending'],
                ['label' => 'En cours d’examen', 'status' => 'en_cours_examen', 'icon' => 'fa-magnifying-glass', 'class' => 'review'],
                ['label' => 'Entretiens programmés', 'status' => 'entretien_programme', 'icon' => 'fa-calendar-check', 'class' => 'interview'],
                ['label' => 'Acceptés', 'status' => 'accepte', 'icon' => 'fa-circle-check', 'class' => 'accepted'],
                ['label' => 'Rejetés', 'status' => 'rejete', 'icon' => 'fa-circle-xmark', 'class' => 'rejected'],
            ];
            $statusLabels = [
                'en_attente' => 'En attente',
                'en_cours_examen' => 'En cours d’examen',
                'entretien_programme' => 'Entretien programmé',
                'accepte' => 'Accepté',
                'rejete' => 'Rejeté',
            ];
            $statusClasses = [
                'en_attente' => 'status-pending',
                'en_cours_examen' => 'status-review',
                'entretien_programme' => 'status-interview',
                'accepte' => 'status-accepted',
                'rejete' => 'status-rejected',
            ];
        @endphp

        <section aria-labelledby="stats-title">
            <div class="section-heading">
                <div>
                    <h2 id="stats-title">Vue d'ensemble</h2>
                    <p>État actuel des candidatures reçues.</p>
                </div>
            </div>
            <div class="stats-grid">
                @foreach($stats as $stat)
                    <a class="stat-card {{ $stat['class'] }}" href="{{ $stat['status'] ? route('admin.candidatures.index', ['statut' => $stat['status']]) : route('admin.candidatures.index') }}">
                        <span class="stat-top">
                            <span class="stat-label">{{ $stat['label'] }}</span>
                            <span class="stat-icon"><i class="fa-solid {{ $stat['icon'] }}" aria-hidden="true"></i></span>
                        </span>
                        <strong class="stat-number">{{ $stat['status'] ? $statusCounts->get($stat['status'], 0) : $totalCandidatures }}</strong>
                        <span class="stat-hint">Cliquer pour consulter</span>
                    </a>
                @endforeach
            </div>
        </section>

        <div class="content-grid">
            <section class="panel" aria-labelledby="recent-title">
                <div class="panel-heading">
                    <div>
                        <h2 id="recent-title">Candidatures récentes</h2>
                        <p>Les cinq derniers dossiers reçus.</p>
                    </div>
                    <a class="subtle-link" href="{{ route('admin.candidatures.index') }}">Tout voir <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                </div>
                @if($candidaturesRecentes->isNotEmpty())
                    <ul class="recent-list">
                        @foreach($candidaturesRecentes as $candidature)
                            <li class="recent-item">
                                <a class="recent-link" href="{{ route('admin.candidatures.show', $candidature->id) }}">
                                    <span class="recent-person">
                                        <span class="avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($candidature->chauffeur->prenom, 0, 1).mb_substr($candidature->chauffeur->nom, 0, 1)) }}</span>
                                        <span>
                                            <span class="candidate-name">{{ $candidature->chauffeur->prenom }} {{ $candidature->chauffeur->nom }}</span>
                                            <span class="candidate-meta">{{ $candidature->chauffeur->ville }} · {{ $candidature->created_at->format('d/m/Y') }}</span>
                                        </span>
                                    </span>
                                    <span class="status {{ $statusClasses[$candidature->statut] ?? 'status-review' }}">{{ $statusLabels[$candidature->statut] ?? ucfirst(str_replace('_', ' ', $candidature->statut)) }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">
                        <span class="empty-icon"><i class="fa-regular fa-folder-open" aria-hidden="true"></i></span>
                        <h3>Aucun dossier reçu</h3>
                        <p>Les candidatures envoyées apparaîtront ici.</p>
                    </div>
                @endif
            </section>

            <aside class="panel" aria-labelledby="shortcuts-title">
                <div class="panel-heading">
                    <div>
                        <h2 id="shortcuts-title">Accès rapides</h2>
                        <p>Les outils de votre espace recruteur.</p>
                    </div>
                </div>
                <div class="shortcuts">
                    <a class="shortcut" href="{{ route('admin.candidatures.index', ['statut' => 'en_attente']) }}">
                        <span class="shortcut-icon"><i class="fa-solid fa-hourglass-half" aria-hidden="true"></i></span>
                        <span><strong>Dossiers à examiner</strong><span>{{ $statusCounts->get('en_attente', 0) }} candidature(s) en attente</span></span>
                    </a>
                    <a class="shortcut" href="{{ route('admin.candidatures.index', ['statut' => 'entretien_programme']) }}">
                        <span class="shortcut-icon"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></span>
                        <span><strong>Entretiens programmés</strong><span>Consulter les candidats à rencontrer</span></span>
                    </a>
                    <a class="shortcut" href="{{ route('candidature.create') }}" target="_blank" rel="noopener">
                        <span class="shortcut-icon"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></span>
                        <span><strong>Formulaire de candidature</strong><span>Ouvrir la page publique de recrutement</span></span>
                    </a>
                </div>
            </aside>
        </div>
            </main>
        </div>
    </div>
</body>
</html>