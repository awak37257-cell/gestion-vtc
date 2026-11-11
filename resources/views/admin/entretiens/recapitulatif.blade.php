<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <title>Récapitulatif des entretiens — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Inclusion des styles de la sidebar -->
    @include('admin.partials.sidebar-styles')

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
            --rose-dark: #be123c;
            --rose-light: #ffe4e6;
            --paper: #f8fafc;
            --white: #ffffff;
            --line: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.05), 0 2px 4px -2px rgba(15, 23, 42, 0.05);
            --shadow-lg: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            --radius: 14px;
        }

        * { box-sizing: border-box; }
        
        /* MISE EN PAGE GLOBALE ADMIN (Sidebar à gauche + Contenu à droite) */
        body {
            margin: 0;
            background: var(--paper);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            display: flex;
            min-height: 100vh;
        }

        /* Conteneur principal à droite de la sidebar */
        .admin-main-wrapper {
            flex: 1;
            margin-left: 260px; /* Espace réservé pour la sidebar fixe */
            padding: 40px;
            min-width: 0;
        }

        a { color: inherit; text-decoration: none; }
        h1, h2, h3, h4, h5, p { margin-top: 0; }
        h1, h2, h3, h4, h5 { font-family: 'Manrope', sans-serif; }

        /* HEADER */
        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .page-header-text { min-width: 0; }
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 11px;
            color: var(--gold-dark);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .13em;
            text-transform: uppercase;
        }
        .eyebrow::before {
            width: 22px; height: 2px;
            background: linear-gradient(90deg, var(--gold), var(--emerald));
            border-radius: 2px;
            content: '';
        }
        .page-header h1 {
            margin-bottom: 8px;
            font-size: clamp(1.6rem, 3.5vw, 2.2rem);
            font-weight: 800;
            letter-spacing: -.05em;
            line-height: 1.15;
            background: linear-gradient(135deg, var(--ink) 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .page-header p {
            margin: 0;
            color: var(--muted);
            font-size: .9rem;
        }
        
        /* FORMULAIRE DE FILTRE PAR DATE */
        .date-filter-form {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--white);
            padding: 8px 14px;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            box-shadow: var(--shadow);
        }
        .date-filter-form label {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--muted);
            white-space: nowrap;
        }
        .date-filter-input {
            border: none;
            background: transparent;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--ink);
            outline: none;
            cursor: pointer;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            min-height: 44px;
            padding: 0 18px;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            background: var(--white);
            color: var(--ink-soft);
            font-size: .82rem;
            font-weight: 700;
            transition: all .2s ease;
            box-shadow: var(--shadow);
            white-space: nowrap;
        }
        .back-button:hover {
            border-color: var(--gold);
            background: var(--gold-light);
            color: var(--gold-dark);
            transform: translateY(-1px);
        }

        /* FLASH */
        .flash-success {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
            padding: 14px 18px;
            border: 1px solid #a7f3d0;
            border-radius: 10px;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            color: var(--emerald-dark);
            font-size: .86rem;
            font-weight: 500;
        }

        /* GRID */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        /* CARD */
        .summary-card {
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--white);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .summary-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 20px 22px;
            border-bottom: 1px solid var(--line);
            position: relative;
        }
        .summary-card-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }
        .summary-card.accepted .summary-card-header::before {
            background: linear-gradient(90deg, var(--emerald), #34d399, var(--emerald));
        }
        .summary-card.rejected .summary-card-header::before {
            background: linear-gradient(90deg, var(--rose), #fb7185, var(--rose));
        }
        .summary-title-group {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
        }
        .summary-icon {
            display: grid;
            width: 42px; height: 42px;
            flex: 0 0 42px;
            place-items: center;
            border-radius: 11px;
            font-size: 1rem;
        }
        .summary-card.accepted .summary-icon {
            background: linear-gradient(135deg, var(--emerald-light), #a7f3d0);
            color: var(--emerald-dark);
        }
        .summary-card.rejected .summary-icon {
            background: linear-gradient(135deg, var(--rose-light), #fecdd3);
            color: var(--rose-dark);
        }
        .summary-card-header h2 {
            margin: 0;
            font-size: .98rem;
            font-weight: 800;
        }
        .count-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 30px;
            padding: 0 12px;
            border-radius: 99px;
            font-family: 'Manrope', sans-serif;
            font-size: .82rem;
            font-weight: 800;
        }
        .summary-card.accepted .count-badge {
            background: linear-gradient(135deg, var(--emerald-light), #a7f3d0);
            color: var(--emerald-dark);
            border: 1px solid #a7f3d0;
        }
        .summary-card.rejected .count-badge {
            background: linear-gradient(135deg, var(--rose-light), #fecdd3);
            color: var(--rose-dark);
            border: 1px solid #fecdd3;
        }

        /* LIST */
        .candidate-list {
            list-style: none;
            margin: 0;
            padding: 0;
            flex: 1;
        }
        .candidate-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 16px 22px;
            border-bottom: 1px solid #f1f5f9;
        }
        .candidate-item:last-child { border-bottom: 0; }
        .candidate-info {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }
        .candidate-avatar {
            display: grid;
            width: 40px; height: 40px;
            flex: 0 0 40px;
            place-items: center;
            border-radius: 50%;
            font-family: 'Manrope', sans-serif;
            font-size: .74rem;
            font-weight: 800;
            border: 2px solid var(--white);
        }
        .summary-card.accepted .candidate-avatar {
            background: linear-gradient(135deg, var(--emerald-light), #a7f3d0);
            color: var(--emerald-dark);
        }
        .summary-card.rejected .candidate-avatar {
            background: linear-gradient(135deg, var(--rose-light), #fecdd3);
            color: var(--rose-dark);
        }
        .candidate-name {
            display: block;
            color: var(--ink);
            font-size: .84rem;
            font-weight: 700;
        }
        .candidate-meta {
            display: block;
            margin-top: 3px;
            color: var(--muted);
            font-size: .73rem;
        }

        /* STATUS BADGE */
        .status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 11px;
            border-radius: 99px;
            font-size: .68rem;
            font-weight: 700;
        }
        .status-accepted {
            border: 1px solid #a7f3d0;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            color: var(--emerald-dark);
        }
        .status-rejected {
            border: 1px solid #fecdd3;
            background: linear-gradient(135deg, #fff1f2, #ffe4e6);
            color: var(--rose-dark);
        }

        /* EMPTY */
        .empty-list {
            padding: 42px 24px;
            text-align: center;
            color: var(--muted);
        }
        .empty-list i {
            display: block;
            font-size: 1.8rem;
            color: #cbd5e1;
            margin-bottom: 10px;
        }
        .empty-list p { margin: 0; font-size: .84rem; }

        /* SEND SECTION */
        .send-panel {
            position: relative;
            padding: 36px 40px;
            border-radius: var(--radius);
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: var(--white);
            box-shadow: var(--shadow-lg);
            text-align: center;
        }
        .send-icon {
            display: grid;
            width: 60px; height: 60px;
            margin: 0 auto 20px;
            place-items: center;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(234, 179, 8, 0.2), rgba(16, 185, 129, 0.15));
            color: var(--gold);
            font-size: 1.4rem;
            border: 1px solid rgba(234, 179, 8, 0.3);
        }
        .send-panel h2 {
            margin-bottom: 10px;
            font-size: 1.45rem;
            font-weight: 800;
        }
        .send-panel p {
            max-width: 620px;
            margin: 0 auto 26px;
            color: rgba(255, 255, 255, .72);
            font-size: .9rem;
        }
        .send-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 54px;
            padding: 0 28px;
            border: 0;
            border-radius: 11px;
            background: linear-gradient(135deg, var(--gold) 0%, #f59e0b 100%);
            color: var(--ink);
            font-size: .89rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(234, 179, 8, 0.4);
        }
        .send-button:hover:not(:disabled) {
            transform: translateY(-2px);
        }
        .send-button:disabled {
            opacity: .45;
            cursor: not-allowed;
        }

        @media (max-width: 800px) {
            .summary-grid { grid-template-columns: 1fr; }
            .admin-main-wrapper { margin-left: 0; padding: 20px; }
            .page-header { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

    <!-- Inclusion de la barre latérale -->
    @include('admin.partials.sidebar')

    <!-- Contenu principal encapsulé pour s'aligner avec la sidebar -->
    <div class="admin-main-wrapper">
        <main class="page">

            <!-- HEADER AVEC FILTRE DE DATE -->
            <div class="page-header">
                <div class="page-header-text">
                    <p class="eyebrow">Bilan quotidien</p>
                    <h1>Récapitulatif des entretiens</h1>
                    <p>Affichage pour la date du : <strong>{{ \Carbon\Carbon::parse($dateCible)->format('d/m/Y') }}</strong></p>
                </div>

                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <!-- FORMULAIRE DE SÉLECTION DE DATE -->
                    <form method="GET" action="{{ route('admin.entretiens.recapitulatif') }}" class="date-filter-form">
                        <label for="date-filtre"><i class="fa-regular fa-calendar" aria-hidden="true"></i> Date :</label>
                        <input type="date" id="date-filtre" name="date" value="{{ $dateCible }}" class="date-filter-input" onchange="this.form.submit()">
                    </form>

                </div>
            </div>

            @if(session('success'))
                <div class="flash-success" role="status">
                    <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- GRID ACCEPTÉS / REFUSÉS -->
            <div class="summary-grid">

                <!-- CANDIDATS ACCEPTÉS -->
                <section class="summary-card accepted" aria-labelledby="accepted-title">
                    <div class="summary-card-header">
                        <div class="summary-title-group">
                            <span class="summary-icon"><i class="fa-solid fa-circle-check" aria-hidden="true"></i></span>
                            <h2 id="accepted-title">Candidats acceptés</h2>
                        </div>
                        <span class="count-badge">{{ $acceptes->count() }}</span>
                    </div>
                    <ul class="candidate-list">
                        @forelse($acceptes as $c)
                            <li class="candidate-item">
                                <div class="candidate-info">
                                    <span class="candidate-avatar" aria-hidden="true">
                                        {{ mb_strtoupper(mb_substr($c->chauffeur->prenom, 0, 1).mb_substr($c->chauffeur->nom, 0, 1)) }}
                                    </span>
                                    <div>
                                        <span class="candidate-name">{{ $c->chauffeur->prenom }} {{ $c->chauffeur->nom }}</span>
                                        <span class="candidate-meta">{{ $c->chauffeur->telephone_whatsapp }}</span>
                                    </div>
                                </div>
                                <span class="status status-accepted">Accepté</span>
                            </li>
                        @empty
                            <li class="empty-list">
                                <i class="fa-regular fa-circle-check" aria-hidden="true"></i>
                                <p>Aucun candidat accepté pour cette date.</p>
                            </li>
                        @endforelse
                    </ul>
                </section>

                <!-- CANDIDATS REFUSÉS -->
                <section class="summary-card rejected" aria-labelledby="rejected-title">
                    <div class="summary-card-header">
                        <div class="summary-title-group">
                            <span class="summary-icon"><i class="fa-solid fa-circle-xmark" aria-hidden="true"></i></span>
                            <h2 id="rejected-title">Candidats refusés</h2>
                        </div>
                        <span class="count-badge">{{ $refuses->count() }}</span>
                    </div>
                    <ul class="candidate-list">
                        @forelse($refuses as $c)
                            <li class="candidate-item">
                                <div class="candidate-info">
                                    <span class="candidate-avatar" aria-hidden="true">
                                        {{ mb_strtoupper(mb_substr($c->chauffeur->prenom, 0, 1).mb_substr($c->chauffeur->nom, 0, 1)) }}
                                    </span>
                                    <div>
                                        <span class="candidate-name">{{ $c->chauffeur->prenom }} {{ $c->chauffeur->nom }}</span>
                                        <span class="candidate-meta">{{ $c->chauffeur->email ?: "Pas d'email" }}</span>
                                    </div>
                                </div>
                                <span class="status status-rejected">Refusé</span>
                            </li>
                        @empty
                            <li class="empty-list">
                                <i class="fa-regular fa-circle-xmark" aria-hidden="true"></i>
                                <p>Aucun candidat refusé pour cette date.</p>
                            </li>
                        @endforelse
                    </ul>
                </section>

            </div>

            <!-- ZONE D'ENVOI -->
<section class="send-panel" aria-labelledby="send-title">
    <span class="send-icon"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i></span>
    <h2 id="send-title">Envoyer la notification groupée</h2>
    <p>Un e-mail de confirmation sera envoyé aux candidats retenus, et un message de refus aux non retenus pour la date du {{ \Carbon\Carbon::parse($dateCible)->format('d/m/Y') }}.</p>

    <form action="{{ route('admin.candidatures.envoyerGroupesDuJour') }}" method="POST" onsubmit="return confirm('Confirmer l\'envoi des messages pour la date du {{ \Carbon\Carbon::parse($dateCible)->format('d/m/Y') }} ?');">
        @csrf
        <!-- Transmettre la date filtrée au contrôleur -->
        <input type="hidden" name="date" value="{{ $dateCible }}">

        <button type="submit" class="send-button" {{ ($acceptes->count() == 0 && $refuses->count() == 0) ? 'disabled' : '' }}>
            <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
            Lancer l'envoi des messages groupés
        </button>
    </form>
</section>

        </main>
    </div>

</body>
</html>