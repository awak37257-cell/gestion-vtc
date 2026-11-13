<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <title>Tableau de bord — TIHE'S Group</title>
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
        body {
            min-width: 320px;
            margin: 0;
            background: var(--paper);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }
        a:focus-visible, button:focus-visible, select:focus-visible {
            outline: 3px solid var(--gold);
            outline-offset: 3px;
        }
        .sr-only { position: absolute; left: 0; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
        h1, h2, p { margin-top: 0; }
        h1, h2 { font-family: 'Manrope', sans-serif; }

        /* TOPBAR */
        .topbar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: var(--white);
            border-bottom: 1px solid rgba(234, 179, 8, 0.15);
        }
        .topbar-inner {
            display: flex;
            width: min(1240px, calc(100% - 48px));
            min-height: 76px;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin: 0 auto;
        }
        .brand { display: inline-flex; align-items: center; gap: 10px; font-family: 'Manrope', sans-serif; font-size: 1.08rem; font-weight: 800; letter-spacing: -.04em; }
        .brand-mark {
            display: grid; width: 38px; height: 38px; place-items: center;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--gold) 0%, #f59e0b 100%);
            color: var(--ink);
            box-shadow: 0 4px 12px rgba(234, 179, 8, 0.4);
        }
        .brand-name span { color: var(--gold); }
        .topbar-actions { display: flex; align-items: center; gap: 11px; }
        .public-link, .logout-button {
            display: inline-flex;
            min-height: 39px;
            align-items: center;
            justify-content: center;
            gap: 8px;
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
        .public-link:hover { border-color: var(--gold); color: var(--gold); background: rgba(234, 179, 8, 0.1); }
        .logout-button { border-color: rgba(244, 63, 94, .25); }
        .logout-button:hover { border-color: var(--rose); background: rgba(244, 63, 94, .12); color: #fda4af; }

        /* PAGE */
        .page { width: min(1240px, calc(100% - 48px)); margin: 0 auto; padding: 48px 0 70px; }
        .page-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 25px; margin-bottom: 28px; }
        .eyebrow { display: inline-flex; align-items: center; gap: 9px; margin-bottom: 11px; color: var(--gold-dark); font-size: .72rem; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; }
        .eyebrow::before { width: 22px; height: 2px; background: linear-gradient(90deg, var(--gold), var(--emerald)); border-radius: 2px; content: ''; }
        .page-heading h1 {
            margin-bottom: 9px;
            font-size: clamp(1.9rem, 4vw, 2.7rem);
            font-weight: 800;
            letter-spacing: -.06em;
            line-height: 1.1;
            background: linear-gradient(135deg, var(--ink) 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .page-heading p { max-width: 620px; margin-bottom: 0; color: var(--muted); font-size: .91rem; line-height: 1.65; }
        .results-count {
            display: inline-flex; flex: 0 0 auto; align-items: center; gap: 9px;
            padding: 12px 16px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: var(--white);
            color: var(--ink-soft);
            font-size: .8rem;
            font-weight: 600;
            box-shadow: var(--shadow);
        }
        .results-count i { color: var(--gold-dark); }
        .results-count strong {
            font-size: 1.05rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--gold-dark), var(--emerald-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .success-alert {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 20px; padding: 14px 16px;
            border: 1px solid #a7f3d0;
            border-radius: 10px;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            color: var(--emerald-dark);
            font-size: .85rem;
            font-weight: 500;
        }
        .success-alert i { font-size: 1rem; }

        /* FILTER CARD */
        .filter-card {
            display: flex; align-items: center; justify-content: space-between; gap: 20px;
            margin-bottom: 20px; padding: 20px 22px;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--white);
            box-shadow: var(--shadow);
        }
        .filter-intro { display: flex; align-items: center; gap: 13px; }
        .filter-icon {
            display: grid; width: 44px; height: 44px; flex: 0 0 44px; place-items: center;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--gold-light), #fef3c7);
            color: var(--gold-dark);
            font-size: 1rem;
        }
        .filter-intro h2 { margin: 0 0 3px; font-size: .92rem; font-weight: 800; }
        .filter-intro p { margin: 0; color: var(--muted); font-size: .77rem; }
        .filter-form { display: flex; flex-wrap: wrap; align-items: center; justify-content: flex-end; gap: 9px; }
        .filter-input {
            min-width: 220px; min-height: 44px;
            padding: 0 14px;
            border: 1.5px solid var(--line);
            border-radius: 8px;
            background: var(--white);
            color: var(--ink-soft);
            font: inherit;
            font-size: .82rem;
            font-weight: 500;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .filter-input:hover { border-color: #cbd5e1; }
        .filter-input:focus { outline: 0; border-color: var(--gold); box-shadow: 0 0 0 3px rgba(234, 179, 8, 0.15); }
        .filter-input-date { min-width: 150px; }
        .filter-select {
            min-width: 220px; min-height: 44px;
            padding: 0 36px 0 14px;
            border: 1.5px solid var(--line);
            border-radius: 8px;
            background: var(--white);
            color: var(--ink-soft);
            font: inherit;
            font-size: .82rem;
            font-weight: 500;
            transition: border-color .2s ease, box-shadow .2s ease;
            cursor: pointer;
        }
        .filter-select:hover { border-color: #cbd5e1; }
        .filter-select:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(234, 179, 8, 0.15); }
        .filter-button {
            display: inline-flex; min-height: 44px; align-items: center; justify-content: center; gap: 8px;
            padding: 0 20px;
            border: 0;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--ink) 0%, #334155 100%);
            color: var(--white);
            font: inherit;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s ease;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        }
        .filter-button:hover {
            background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.25);
            transform: translateY(-1px);
        }
        .clear-filter {
            padding: 10px 6px;
            color: var(--muted);
            font-size: .78rem;
            font-weight: 600;
            transition: color .2s ease;
        }
        .clear-filter:hover { color: var(--rose); }
        .export-link {
            display: inline-flex; min-height: 44px; align-items: center; gap: 8px;
            padding: 0 16px;
            border: 1.5px solid var(--line);
            border-radius: 8px;
            background: var(--white);
            color: var(--ink-soft);
            font-size: .82rem;
            font-weight: 700;
            transition: all .2s ease;
        }
        .export-link:hover { border-color: var(--gold); background: var(--gold-light); color: var(--gold-dark); }

        /* TABLE CARD */
        .table-card {
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--white);
            box-shadow: var(--shadow-lg);
        }
        .table-header {
            display: flex; align-items: center; justify-content: space-between; gap: 15px;
            padding: 20px 22px;
            border-bottom: 1px solid var(--line);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }
        .table-header h2 { margin: 0; font-size: .98rem; font-weight: 800; letter-spacing: -.02em; }
        .table-header p { margin: 0; color: var(--muted); font-size: .77rem; }
        .table-scroll { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        thead { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); }
        th {
            padding: 13px 16px;
            color: #94a3b8;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            white-space: nowrap;
        }
        td {
            padding: 16px;
            border-top: 1px solid #f1f5f9;
            color: var(--ink-soft);
            font-size: .81rem;
            vertical-align: middle;
        }
        tbody tr { transition: background .15s ease; }
        tbody tr:hover { background: #f8fafc; }
        tbody tr:nth-child(even) { background: #fcfcfd; }
        tbody tr:nth-child(even):hover { background: #f8fafc; }

        .candidate-cell { display: flex; min-width: 175px; align-items: center; gap: 12px; }
        .avatar {
            display: grid; width: 40px; height: 40px; flex: 0 0 40px; place-items: center;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--emerald-light), #a7f3d0);
            color: var(--emerald-dark);
            font-family: 'Manrope', sans-serif;
            font-size: .78rem;
            font-weight: 800;
            border: 2px solid var(--white);
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
        }
        .candidate-name { display: block; color: var(--ink); font-size: .83rem; font-weight: 700; }
        .candidate-date { display: block; margin-top: 3px; color: var(--muted); font-size: .7rem; }
        .contact-cell { min-width: 155px; color: var(--muted); white-space: nowrap; font-variant-numeric: tabular-nums; }
        .city-cell { min-width: 100px; font-weight: 500; }
        .documents-cell { min-width: 160px; }
        .document-line { display: block; max-width: 180px; overflow: hidden; color: var(--ink-soft); font-size: .74rem; text-overflow: ellipsis; white-space: nowrap; }
        .document-line + .document-line { margin-top: 4px; color: var(--muted); }

        /* STATUS BADGES */
        .status {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 6px 11px;
            border: 1px solid transparent;
            border-radius: 99px;
            font-size: .7rem;
            font-weight: 700;
            white-space: nowrap;
        }
        .status::before { width: 7px; height: 7px; border-radius: 50%; background: currentColor; content: ''; box-shadow: 0 0 0 3px rgba(255,255,255,0.8); }
        .status-pending { border-color: #fde68a; background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #b45309; }
        .status-review { border-color: #bae6fd; background: linear-gradient(135deg, #f0f9ff, #e0f2fe); color: #0369a1; }
        .status-interview { border-color: #ddd6fe; background: linear-gradient(135deg, #f5f3ff, #ede9fe); color: #6d28d9; }
        .status-accepted { border-color: #a7f3d0; background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #047857; }
        .status-rejected { border-color: #fecdd3; background: linear-gradient(135deg, #fff1f2, #ffe4e6); color: #be123c; }

        /* ACTION LINKS & BUTTONS */
        .action-link {
            display: inline-flex; min-height: 36px; align-items: center; justify-content: center; gap: 7px;
            padding: 0 13px;
            border: 1.5px solid var(--line);
            border-radius: 8px;
            color: var(--ink-soft);
            font-size: .74rem;
            font-weight: 700;
            white-space: nowrap;
            transition: all .2s ease;
        }
        .action-link:hover {
            border-color: var(--gold);
            background: var(--gold-light);
            color: var(--gold-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(234, 179, 8, 0.15);
        }
        .decision-actions { display: grid; min-width: 120px; gap: 6px; }
        .decision-button {
            min-height: 34px; padding: 0 10px;
            border: 1.5px solid transparent;
            border-radius: 7px;
            font: inherit;
            font-size: .7rem;
            font-weight: 700;
            white-space: nowrap;
            cursor: pointer;
            transition: all .2s ease;
        }
        .decision-accept { border-color: #a7f3d0; background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #047857; }
        .decision-accept:hover { background: linear-gradient(135deg, #d1fae5, #a7f3d0); transform: translateY(-1px); box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2); }
        .decision-reject { border-color: #fecdd3; background: linear-gradient(135deg, #fff1f2, #ffe4e6); color: #be123c; }
        .decision-reject:hover { background: linear-gradient(135deg, #ffe4e6, #fecdd3); transform: translateY(-1px); box-shadow: 0 4px 10px rgba(244, 63, 94, 0.2); }
        .decision-absent { border-color: #cbd5e1; background: #f8fafc; color: #475569; }
        .decision-absent:hover { background: #f1f5f9; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(71, 85, 105, 0.15); }
        .absent-note { display: block; margin-top: 5px; color: #be123c; font-size: .72rem; font-weight: 600; }
        .error-alert {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 20px; padding: 14px 16px;
            border: 1px solid #fecdd3; border-radius: 10px;
            background: linear-gradient(135deg, #fff1f2, #ffe4e6);
            color: #be123c; font-size: .85rem; font-weight: 600;
        }
        .action-pending { display: inline-block; max-width: 150px; color: var(--muted); font-size: .72rem; line-height: 1.4; }

        /* EMPTY STATE */
        .empty-state { padding: 60px 20px; text-align: center; }
        .empty-icon {
            display: grid; width: 56px; height: 56px; margin: 0 auto 16px; place-items: center;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--gold-light), #fef3c7);
            color: var(--gold-dark);
            font-size: 1.2rem;
        }
        .empty-state h3 { margin: 0 0 7px; font-family: 'Manrope', sans-serif; font-size: 1rem; }
        .empty-state p { margin: 0; color: var(--muted); font-size: .83rem; }

        /* PAGINATION */
        .pagination-wrap {
            display: flex; align-items: center; justify-content: space-between; gap: 15px;
            padding: 16px 22px;
            border-top: 1px solid var(--line);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }
        .pagination-summary { color: var(--muted); font-size: .76rem; }
        .pagination-nav { display: flex; align-items: center; gap: 9px; }
        .pagination-nav a {
            display: inline-flex; min-height: 36px; align-items: center; gap: 7px;
            padding: 0 12px;
            border: 1.5px solid var(--line);
            border-radius: 7px;
            background: var(--white);
            color: var(--ink-soft);
            font-size: .76rem;
            font-weight: 600;
            transition: all .2s ease;
        }
        .pagination-nav a:hover { border-color: var(--gold); background: var(--gold-light); color: var(--gold-dark); }
        .pagination-page { color: var(--muted); font-size: .76rem; font-weight: 500; }

        /* RESPONSIVE */
        @media (max-width: 760px) {
            .page { width: calc(100% - 32px); padding: 34px 0 50px; }
            .page-heading { align-items: flex-start; flex-direction: column; gap: 16px; }
            .filter-card { align-items: flex-start; flex-direction: column; }
            .filter-form { width: 100%; }
            .filter-select { min-width: 0; flex: 1; }
            .table-header { align-items: flex-start; flex-direction: column; }
        }
        @media (max-width: 480px) {
            .page-heading h1 { font-size: 1.9rem; }
            .filter-form { flex-wrap: wrap; }
            .filter-select { flex-basis: calc(100% - 100px); }
            .filter-button { flex: 1; }
            .clear-filter { margin-left: auto; }
            .pagination-wrap { justify-content: center; padding: 14px 10px; }
            .pagination-wrap nav { align-items: flex-end; flex-direction: column; }
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
        <div class="page-heading">
            <div>
                <p class="eyebrow">Espace recruteur</p>
                <h1>Suivi des candidatures</h1>
                <p>Consultez les profils, vérifiez les dossiers et accompagnez chaque candidat dans son parcours.</p>
            </div>
            <div class="results-count"><i class="fa-solid fa-folder-open" aria-hidden="true"></i><strong>{{ $candidatures->total() }}</strong> dossier(s) correspondant(s)</div>
        </div>

        @if(session('success'))
            <div class="success-alert" role="status"><i class="fa-solid fa-circle-check" aria-hidden="true"></i>{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="error-alert" role="alert"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>{{ $errors->first() }}</div>
        @endif

        <section class="filter-card" aria-labelledby="filter-title">
            <div class="filter-intro">
                <span class="filter-icon"><i class="fa-solid fa-sliders" aria-hidden="true"></i></span>
                <div>
                    <h2 id="filter-title">Filtrer les dossiers</h2>
                    <p>Recherchez par nom, téléphone, ville ou permis, puis affinez par statut ou par date.</p>
                </div>
            </div>
            <form method="GET" action="{{ route('admin.candidatures.index') }}" class="filter-form">
                <label for="q" class="sr-only">Rechercher un candidat</label>
                <input id="q" type="search" name="q" value="{{ request('q') }}" maxlength="100" class="filter-input" placeholder="Nom, téléphone, ville, permis…">
                <label for="statut" class="sr-only">Statut de la candidature</label>
                <select id="statut" name="statut" class="filter-select">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="en_cours_examen" {{ request('statut') === 'en_cours_examen' ? 'selected' : '' }}>En cours d'examen</option>
                    <option value="entretien_programme" {{ request('statut') === 'entretien_programme' ? 'selected' : '' }}>Entretien programmé</option>
                    <option value="accepte" {{ request('statut') === 'accepte' ? 'selected' : '' }}>Accepté</option>
                    <option value="rejete" {{ request('statut') === 'rejete' ? 'selected' : '' }}>Rejeté</option>
                </select>
                <label for="du" class="sr-only">Reçu à partir du</label>
                <input id="du" type="date" name="du" value="{{ request('du') }}" class="filter-input filter-input-date" title="Reçu à partir du">
                <label for="au" class="sr-only">Reçu jusqu'au</label>
                <input id="au" type="date" name="au" value="{{ request('au') }}" class="filter-input filter-input-date" title="Reçu jusqu'au">
                <button class="filter-button" type="submit"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Filtrer</button>
                <a class="export-link" href="{{ route('admin.candidatures.export', request()->except('page')) }}" title="Télécharge la liste actuellement filtrée, ouvrable dans Excel"><i class="fa-solid fa-file-csv" aria-hidden="true"></i> Exporter</a>
                @if(request()->anyFilled(['q', 'statut', 'du', 'au']))
                    <a class="clear-filter" href="{{ route('admin.candidatures.index') }}">Réinitialiser</a>
                @endif
            </form>
        </section>

        <section class="table-card" aria-labelledby="applications-title">
            <div class="table-header">
                <h2 id="applications-title">Candidatures reçues</h2>
                <p>Les dossiers les plus récents s'affichent en premier.</p>
            </div>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">Candidat</th>
                            <th scope="col">Contact WhatsApp</th>
                            <th scope="col">Ville</th>
                            <th scope="col">Permis / Pièce ID</th>
                            <th scope="col">Statut</th>
                            <th scope="col"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($candidatures as $candidature)
                            @php
                                $dernierEntretien = $candidature->entretiens->sortByDesc('id')->first();
                                $estAbsent = $candidature->statut === 'en_cours_examen'
                                    && $dernierEntretien
                                    && $dernierEntretien->statut_entretien === 'absent';
                                $decisionDisponible = $candidature->statut === 'entretien_programme'
                                    && $dernierEntretien
                                    && strtotime($dernierEntretien->date_prevue) <= $currentTime->timestamp;
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
                            <tr>
                                <td>
                                    <div class="candidate-cell">
                                        <span class="avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($candidature->chauffeur->prenom, 0, 1).mb_substr($candidature->chauffeur->nom, 0, 1)) }}</span>
                                        <span>
                                            <span class="candidate-name">{{ $candidature->chauffeur->prenom }} {{ $candidature->chauffeur->nom }}</span>
                                            <span class="candidate-date">Dossier {{ $candidature->id }}</span>
                                        </span>
                                    </div>
                                </td>
                                <td class="contact-cell">{{ $candidature->chauffeur->telephone_whatsapp }}</td>
                                <td class="city-cell">{{ $candidature->chauffeur->ville }}</td>
                                <td class="documents-cell">
                                    <span class="document-line">Permis : {{ $candidature->numero_permis }}</span>
                                    <span class="document-line">Pièce ID : {{ $candidature->numero_piece_identite ?: 'Non renseigné' }}</span>
                                </td>
                                <td>
                                    <span class="status {{ $statusClasses[$candidature->statut] ?? 'status-review' }}">
                                        {{ $statusLabels[$candidature->statut] ?? ucfirst(str_replace('_', ' ', $candidature->statut)) }}
                                    </span>
                                    @if($estAbsent)
                                        <span class="absent-note">Absent à l’entretien du {{ date('d/m/Y', strtotime($dernierEntretien->date_prevue)) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($estAbsent)
                                        <div class="decision-actions">
                                            <a class="action-link" href="{{ route('admin.candidatures.show', $candidature) }}">Reprogrammer</a>
                                            <form action="{{ route('admin.candidatures.refusAbsence', $candidature) }}" method="POST">
                                                @csrf
                                                <input type="text" name="motif_rejet" maxlength="255" placeholder="Motif (facultatif)" style="width: 150px; padding: 6px 8px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: .78rem;">
                                                <button class="decision-button decision-reject" type="submit">Refuser</button>
                                            </form>
                                        </div>
                                    @elseif(in_array($candidature->statut, ['en_attente', 'en_cours_examen'], true))
                                        <a class="action-link" href="{{ route('admin.candidatures.show', $candidature->id) }}">
                                            Examiner <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                        </a>
                                    @elseif($decisionDisponible)
                                        <div class="decision-actions">
                                            @php
                                                $documentsValides = $candidature->documents->isNotEmpty()
                                                    && $candidature->documents->every(fn ($document) => $document->est_valide === true);
                                            @endphp
                                            @if($documentsValides)
                                                <form action="{{ route('admin.candidatures.decisionEntretien', $candidature) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="decision" value="valide">
                                                    <button class="decision-button decision-accept" type="submit">Accepter</button>
                                                </form>
                                            @else
                                                <a class="action-link" href="{{ route('admin.candidatures.show', $candidature) }}">Valider les documents</a>
                                            @endif
                                            <form action="{{ route('admin.candidatures.decisionEntretien', $candidature) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="decision" value="non_valide">
                                                <input type="text" name="motif_rejet" maxlength="255" placeholder="Motif (facultatif)" style="width: 150px; padding: 6px 8px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: .78rem;">
                                                <button class="decision-button decision-reject" type="submit">Refuser</button>
                                            </form>
                                            <form action="{{ route('admin.candidatures.absent', $candidature) }}" method="POST" onsubmit="return confirm('Marquer ce candidat comme absent à l\'entretien ?');">
                                                @csrf
                                                <button class="decision-button decision-absent" type="submit">Absent</button>
                                            </form>
                                        </div>
                                    @elseif($candidature->statut === 'entretien_programme')
                                        <a class="action-link" href="{{ route('admin.candidatures.show', $candidature) }}">Voir le rendez-vous</a>
                                    @else
                                        <a class="action-link" href="{{ route('admin.candidatures.show', $candidature) }}">Voir le dossier</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <span class="empty-icon"><i class="fa-regular fa-folder-open" aria-hidden="true"></i></span>
                                        <h3>Aucune candidature pour le moment</h3>
                                        <p>Les nouveaux dossiers apparaîtront ici dès leur envoi.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($candidatures->hasPages())
                <div class="pagination-wrap">
                    <span class="pagination-summary">{{ $candidatures->firstItem() }}–{{ $candidatures->lastItem() }} sur {{ $candidatures->total() }} dossiers</span>
                    <nav class="pagination-nav" aria-label="Pagination">
                        @if($candidatures->previousPageUrl())
                            <a href="{{ $candidatures->previousPageUrl() }}"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Précédent</a>
                        @endif
                        <span class="pagination-page">Page {{ $candidatures->currentPage() }} / {{ $candidatures->lastPage() }}</span>
                        @if($candidatures->nextPageUrl())
                            <a href="{{ $candidatures->nextPageUrl() }}">Suivant <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                        @endif
                    </nav>
                </div>
            @endif
        </section>
            </main>
        </div>
    </div>
</body>
</html>