@php
    $telephone = preg_replace('/[^0-9]/', '', $candidature->chauffeur->telephone_whatsapp);
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
    $documentLabels = [
        'permis_recto' => 'Permis de conduire · recto',
        'permis_verso' => 'Permis de conduire · verso',
        'carte_vtc_recto' => 'Carte VTC · recto',
        'carte_vtc_verso' => 'Carte VTC · verso',
        'piece_identite' => 'Pièce d’identité',
    ];
    $dernierEntretien = $candidature->entretiens()->latest('id')->first();
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <title>Dossier de {{ $candidature->chauffeur->prenom }} {{ $candidature->chauffeur->nom }} — TIHE'S Group Admin</title>
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
        body { margin: 0; background: var(--paper); color: var(--ink); font-family: 'DM Sans', sans-serif; -webkit-font-smoothing: antialiased; }
        a { color: inherit; text-decoration: none; }
        a:focus-visible, button:focus-visible, input:focus-visible, textarea:focus-visible {
            outline: 3px solid var(--gold);
            outline-offset: 3px;
        }
        h1, h2, h3, p { margin: 0; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }

        .page { width: min(1400px, calc(100% - 48px)); margin: 0 auto; padding: 30px 0 60px; }

        /* BACK LINK */
        .back-link {
            display: inline-flex; align-items: center; gap: 8px;
            margin-bottom: 20px;
            padding: 8px 12px;
            border-radius: 8px;
            color: var(--muted);
            font-size: .85rem;
            font-weight: 600;
            transition: all .2s ease;
        }
        .back-link:hover { color: var(--gold-dark); background: var(--gold-light); }

        /* FLASH MESSAGES */
        .flash {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 20px; padding: 14px 18px;
            border-radius: 10px;
            font-size: .85rem;
            font-weight: 500;
        }
        .flash-success { background: linear-gradient(135deg, #ecfdf5, #d1fae5); border: 1px solid #a7f3d0; color: var(--emerald-dark); }
        .flash-error, .validation-errors { background: linear-gradient(135deg, #fff1f2, #ffe4e6); border: 1px solid #fecdd3; color: #be123c; }
        .validation-errors ul { margin: 6px 0 0 20px; padding: 0; }

        /* PROFILE HERO */
        .profile-hero {
            display: flex; align-items: center; justify-content: space-between; gap: 20px;
            margin-bottom: 26px; padding: 32px;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }
        .profile-hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--emerald), var(--gold));
        }
        .profile-hero::after {
            content: '';
            position: absolute;
            top: -140px; right: -140px;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(234, 179, 8, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .profile-main { display: flex; align-items: center; gap: 22px; position: relative; z-index: 1; }
        .avatar {
            display: grid; width: 76px; height: 76px; flex: 0 0 76px; place-items: center;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--gold-light), #fef3c7);
            color: var(--gold-dark);
            font-family: 'Manrope', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            border: 2px solid var(--white);
            box-shadow: 0 8px 20px rgba(234, 179, 8, 0.2);
        }
        .eyebrow {
            display: inline-flex; align-items: center; gap: 7px;
            margin-bottom: 7px;
            color: var(--gold-dark);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        .eyebrow::before { width: 18px; height: 2px; background: linear-gradient(90deg, var(--gold), var(--emerald)); border-radius: 2px; content: ''; }
        .profile-hero h1 {
            font-size: clamp(1.5rem, 2.5vw, 2rem);
            font-weight: 800;
            letter-spacing: -.03em;
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--ink) 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .profile-meta { display: flex; flex-wrap: wrap; gap: 16px; color: var(--muted); font-size: .85rem; }
        .profile-meta span { display: inline-flex; align-items: center; gap: 6px; font-weight: 500; }
        .profile-meta i { color: var(--gold-dark); }
        .profile-meta .fa-whatsapp { color: #25d366; }

        /* STATUS BADGES */
        .status {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 15px;
            border-radius: 99px;
            font-size: .78rem;
            font-weight: 700;
            white-space: nowrap;
            position: relative; z-index: 1;
        }
        .status::before { width: 7px; height: 7px; border-radius: 50%; background: currentColor; content: ''; box-shadow: 0 0 0 3px rgba(255,255,255,0.8); }
        .status-pending { border: 1px solid #fde68a; background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #b45309; }
        .status-review { border: 1px solid #bae6fd; background: linear-gradient(135deg, #f0f9ff, #e0f2fe); color: #0369a1; }
        .status-interview { border: 1px solid #ddd6fe; background: linear-gradient(135deg, #f5f3ff, #ede9fe); color: #6d28d9; }
        .status-accepted { border: 1px solid #a7f3d0; background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #047857; }
        .status-rejected { border: 1px solid #fecdd3; background: linear-gradient(135deg, #fff1f2, #ffe4e6); color: #be123c; }

        /* CONTENT GRID */
        .content-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(340px, .9fr);
            gap: 24px;
            align-items: start;
        }
        .documents-column, .sidebar-column { display: grid; gap: 24px; }

        /* PANELS */
        .panel {
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--white);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }
        .panel-heading {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--line);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }
        .panel-title-group { display: flex; align-items: center; gap: 13px; }
        .panel-icon {
            display: grid; width: 42px; height: 42px; place-items: center;
            border-radius: 11px;
            background: linear-gradient(135deg, var(--gold-light), #fef3c7);
            color: var(--gold-dark);
            font-size: 1rem;
        }
        .panel-heading h2 { font-size: 1.02rem; font-weight: 800; letter-spacing: -.02em; margin-bottom: 3px; }
        .panel-heading p { color: var(--muted); font-size: .78rem; }
        .panel-body { padding: 24px; }
        .badge-count {
            display: inline-flex; align-items: center; justify-content: center;
            min-height: 28px; padding: 0 12px;
            border-radius: 99px;
            background: linear-gradient(135deg, var(--emerald-light), #a7f3d0);
            color: var(--emerald-dark);
            font-size: .74rem;
            font-weight: 800;
            border: 1px solid #a7f3d0;
        }

        /* DOCUMENT GRID */
        .document-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 16px;
            padding: 22px;
        }
        .document-card {
            border: 1px solid var(--line);
            border-radius: 12px;
            background: var(--white);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all .25s ease;
        }
        .document-card:hover {
            transform: translateY(-3px);
            border-color: var(--gold);
            box-shadow: 0 12px 24px rgba(234, 179, 8, 0.15);
        }
        .document-preview {
            position: relative;
            height: 190px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            display: grid;
            place-items: center;
            overflow: hidden;
        }
        .document-preview img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
        .document-card:hover .document-preview img { transform: scale(1.04); }
        .pdf-preview { display: grid; gap: 8px; justify-items: center; color: var(--rose); font-size: .85rem; font-weight: 700; }
        .pdf-preview i { font-size: 2.4rem; }
        .document-info {
            display: flex; align-items: center; justify-content: space-between;
            gap: 10px;
            padding: 14px 16px;
            background: var(--white);
            border-top: 1px solid var(--line);
        }
        .document-name { font-size: .82rem; font-weight: 600; color: var(--ink); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .document-link {
            font-size: .76rem; font-weight: 700;
            color: var(--gold-dark);
            display: inline-flex; align-items: center; gap: 5px;
            white-space: nowrap;
            transition: color .2s ease;
        }
        .document-link:hover { color: var(--ink); text-decoration: underline; }

        /* EMPTY DOCS */
        .empty-docs {
            grid-column: 1 / -1;
            padding: 40px 20px;
            text-align: center;
            color: var(--muted);
        }
        .empty-docs i { font-size: 2rem; color: #cbd5e1; margin-bottom: 10px; display: block; }

        /* INFO LIST */
        .info-list { display: grid; gap: 12px; margin: 0; }
        .info-row {
            display: flex; justify-content: space-between;
            padding-bottom: 11px;
            border-bottom: 1px solid #f1f5f9;
            font-size: .85rem;
        }
        .info-row:last-child { border-bottom: 0; padding-bottom: 0; }
        .info-row dt { color: var(--muted); font-weight: 500; }
        .info-row dd { color: var(--ink); font-weight: 700; margin: 0; text-align: right; }

        .divider { height: 1px; background: var(--line); margin: 18px 0; }

        /* FORMS */
        .form-field { margin-bottom: 16px; }
        .form-field label { display: block; margin-bottom: 7px; color: var(--ink-soft); font-size: .8rem; font-weight: 700; }
        .control {
            display: block; width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--line);
            border-radius: 9px;
            background: var(--white);
            color: var(--ink-soft);
            font: inherit;
            font-size: .85rem;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .control:hover { border-color: #cbd5e1; }
        .control:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(234, 179, 8, 0.15); outline: 0; }
        textarea.control { resize: vertical; min-height: 80px; }
        .form-error { display: block; margin-top: 5px; color: var(--rose); font-size: .78rem; font-weight: 600; }

        /* BUTTONS */
        .button {
            display: flex; width: 100%; min-height: 46px;
            align-items: center; justify-content: center; gap: 10px;
            padding: 12px 16px;
            border: 0;
            border-radius: 10px;
            font: inherit;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s ease;
        }
        .button:hover { transform: translateY(-1px); }
        .button-whatsapp {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
        }
        .button-whatsapp:hover { box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4); }
        .button-accent {
            background: linear-gradient(135deg, var(--gold), #f59e0b);
            color: var(--ink);
            box-shadow: 0 6px 16px rgba(234, 179, 8, 0.3);
        }
        .button-accent:hover { box-shadow: 0 8px 20px rgba(234, 179, 8, 0.45); }
        .button-primary {
            background: linear-gradient(135deg, var(--ink), #334155);
            color: #fff;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.2);
        }
        .button-primary:hover { box-shadow: 0 8px 20px rgba(15, 23, 42, 0.3); }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .content-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 600px) {
            .page { width: calc(100% - 32px); padding: 20px 0 40px; }
            .profile-hero { flex-direction: column; align-items: flex-start; padding: 24px 20px; }
            .profile-main { gap: 16px; }
            .avatar { width: 60px; height: 60px; flex-basis: 60px; font-size: 1.2rem; border-radius: 14px; }
            .profile-hero h1 { font-size: 1.4rem; }
            .panel-heading { padding: 16px 18px; }
            .panel-body { padding: 18px; }
            .document-grid { padding: 16px; grid-template-columns: 1fr; }
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
                <a class="back-link" href="{{ route('admin.candidatures.index') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour à la liste des candidatures
                </a>

                @if(session('success'))
                    <div class="flash flash-success" role="status"><i class="fa-solid fa-circle-check" aria-hidden="true"></i>{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="flash flash-error" role="alert"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="validation-errors" role="alert">
                        <strong>Vérifiez les informations saisies :</strong>
                        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <!-- En-tête profil -->
                <section class="profile-hero" aria-labelledby="candidate-name">
                    <div class="profile-main">
                        <span class="avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($candidature->chauffeur->prenom, 0, 1).mb_substr($candidature->chauffeur->nom, 0, 1)) }}</span>
                        <div>
                            <p class="eyebrow">Dossier Candidat</p>
                            <h1 id="candidate-name">{{ $candidature->chauffeur->prenom }} {{ $candidature->chauffeur->nom }}</h1>
                            <div class="profile-meta">
                                <span><i class="fa-solid fa-location-dot" aria-hidden="true"></i> {{ $candidature->chauffeur->ville }}</span>
                                <span><i class="fa-brands fa-whatsapp" aria-hidden="true"></i> {{ $candidature->chauffeur->telephone_whatsapp }}</span>
                            </div>
                        </div>
                    </div>
                    <span class="status {{ $statusClasses[$candidature->statut] ?? 'status-review' }}">
                        {{ $statusLabels[$candidature->statut] ?? ucfirst(str_replace('_', ' ', $candidature->statut)) }}
                    </span>
                </section>

                <div class="content-grid">
                    <!-- COLONNE GAUCHE : Pièces justificatives -->
                    <div class="documents-column">
                        <section class="panel" aria-labelledby="documents-title">
                            <div class="panel-heading">
                                <div class="panel-title-group">
                                    <span class="panel-icon"><i class="fa-regular fa-folder-open" aria-hidden="true"></i></span>
                                    <div>
                                        <h2 id="documents-title">Pièces justificatives</h2>
                                        <p>Vérifiez les photos des documents transmis</p>
                                    </div>
                                </div>
                                <span class="badge-count">{{ $candidature->documents->count() }} fichier(s)</span>
                            </div>
                            <div class="document-grid">
                                @forelse($candidature->documents as $doc)
                                    @php
                                        $urlFichier = asset('storage/'.$doc->chemin_fichier);
                                        $extension = strtolower(pathinfo($doc->chemin_fichier, PATHINFO_EXTENSION));
                                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png'], true);
                                    @endphp
                                    <article class="document-card">
                                        <div class="document-preview">
                                            @if($isImage)
                                                <a href="{{ $urlFichier }}" target="_blank" rel="noopener noreferrer">
                                                    <img src="{{ $urlFichier }}" alt="{{ $documentLabels[$doc->type_document] ?? 'Document' }}">
                                                </a>
                                            @else
                                                <div class="pdf-preview">
                                                    <i class="fa-regular fa-file-pdf" aria-hidden="true"></i>
                                                    <span>Document PDF</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="document-info">
                                            <span class="document-name">{{ $documentLabels[$doc->type_document] ?? ucfirst(str_replace('_', ' ', $doc->type_document)) }}</span>
                                            <a class="document-link" href="{{ $urlFichier }}" target="_blank" rel="noopener noreferrer">
                                                Agrandir <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </article>
                                @empty
                                    <div class="empty-docs">
                                        <i class="fa-regular fa-file-lines" aria-hidden="true"></i>
                                        <p>Aucun document n'a été joint à ce dossier.</p>
                                    </div>
                                @endforelse
                            </div>
                        </section>
                    </div>

                    <!-- COLONNE DROITE : Infos & Entretien -->
                    <div class="sidebar-column">
                        <!-- Infos candidat -->
                        <section class="panel" aria-labelledby="info-title">
                            <div class="panel-heading">
                                <div class="panel-title-group">
                                    <span class="panel-icon"><i class="fa-regular fa-user" aria-hidden="true"></i></span>
                                    <div>
                                        <h2 id="info-title">Informations clés</h2>
                                        <p>Permis et profil</p>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <dl class="info-list">
                                    <div class="info-row"><dt>E-mail</dt><dd>{{ $candidature->chauffeur->email ?: 'Non renseigné' }}</dd></div>
                                    <div class="info-row"><dt>N° Permis</dt><dd>{{ $candidature->numero_permis }}</dd></div>
                                    <div class="info-row"><dt>Expérience</dt><dd>{{ $candidature->annees_permis }} ans</dd></div>
                                    <div class="info-row"><dt>N° Pièce d'identité</dt><dd>{{ $candidature->numero_piece_identite ?: 'Non renseigné' }}</dd></div>
                                </dl>
                            </div>
                        </section>

                        @if($candidature->statut === 'accepte')
                            <section class="panel" aria-labelledby="accepted-title">
                                <div class="panel-heading">
                                    <div class="panel-title-group">
                                        <span class="panel-icon"><i class="fa-solid fa-circle-check" aria-hidden="true"></i></span>
                                        <div>
                                            <h2 id="accepted-title">Candidature validée</h2>
                                            <p>Le candidat a été retenu</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="flash flash-success" role="status" style="margin-bottom: 0;">
                                        <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                                        Cette candidature a été acceptée. La planification d’un entretien n’est plus nécessaire.
                                    </div>
                                </div>
                            </section>
@elseif($dernierEntretien)
    <!-- RÉCAPITULATIF DE L'ENTRETIEN AVEC OPTIONS DE MODIFICATION ET D'ANNULATION -->
    <section class="panel" aria-labelledby="interview-summary-title">
        <div class="panel-heading">
            <div class="panel-title-group">
                <span class="panel-icon"><i class="fa-regular fa-calendar-check" aria-hidden="true"></i></span>
                <div>
                    <h2 id="interview-summary-title">Entretien programmé</h2>
                    <p>Détails du rendez-vous fixé</p>
                </div>
            </div>
        </div>
        <div class="panel-body" id="view-mode">
            <dl class="info-list">
                <div class="info-row">
                    <dt>Date et heure</dt>
                    <dd>{{ date('d/m/Y à H:i', strtotime($dernierEntretien->date_prevue)) }}</dd>
                </div>
                <div class="info-row">
                    <dt>Lieu / Canal</dt>
                    <dd>{{ $dernierEntretien->lieu_ou_type ?: 'Non renseigné' }}</dd>
                </div>
                <div class="info-row" style="flex-direction: column; align-items: flex-start; gap: 4px;">
                    <dt>Notes internes</dt>
                    <dd style="text-align: left; font-weight: 500; color: var(--muted);">
                        {{ $dernierEntretien->notes ?: 'Aucune note enregistrée.' }}
                    </dd>
                </div>
            </dl>

            <div class="divider"></div>

            <!-- BOUTONS MODIFIER ET ANNULER CÔTE À CÔTE -->
            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                <button type="button" class="button button-accent" onclick="toggleEditMode()" style="flex: 1;">
                    <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> Modifier
                </button>

                <form action="{{ route('admin.candidatures.annulerEntretien', $candidature->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cet entretien ? Le dossier repassera en cours d\'examen.');" style="flex: 1;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="button" style="background: linear-gradient(135deg, var(--rose), #be123c); color: #fff; width: 100%;">
                        <i class="fa-solid fa-trash-can" aria-hidden="true"></i> Annuler
                    </button>
                </form>
            </div>

            @php
                $messageEntretien = "Bonjour {$candidature->chauffeur->prenom}, votre dossier VTC a retenu notre attention. Nous vous invitons à un entretien le ".date('d/m/Y à H:i', strtotime($dernierEntretien->date_prevue))." via {$dernierEntretien->lieu_ou_type}. Merci de confirmer votre présence.";
            @endphp
            <a class="button button-whatsapp" href="https://wa.me/{{ $telephone }}?text={{ urlencode($messageEntretien) }}" target="_blank" rel="noopener noreferrer">
                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Ouvrir WhatsApp avec l'invitation
            </a>
        </div>

        <!-- FORMULAIRE DE MODIFICATION (Caché par défaut) -->
        <div class="panel-body" id="edit-mode" style="display: none;">
            <form action="{{ route('admin.candidatures.modifierEntretien', $candidature->id) }}" method="POST">
                @csrf
                <div class="form-field">
                    <label for="date_prevue_mod">Date et heure du rendez-vous</label>
                    <input class="control" id="date_prevue_mod" type="datetime-local" name="date_prevue" value="{{ date('Y-m-d\TH:i', strtotime($dernierEntretien->date_prevue)) }}" required>
                </div>
                <div class="form-field">
                    <label for="lieu_ou_type_mod">Lieu ou canal</label>
                    <input class="control" id="lieu_ou_type_mod" type="text" name="lieu_ou_type" value="{{ $dernierEntretien->lieu_ou_type }}" placeholder="Ex. Bureau ou visio" required>
                </div>
                <div class="form-field">
                    <label for="notes_mod">Notes internes</label>
                    <textarea class="control" id="notes_mod" name="notes" rows="2" placeholder="Commentaires...">{{ $dernierEntretien->notes }}</textarea>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button class="button button-accent" type="submit" style="flex: 1;">
                        <i class="fa-solid fa-check" aria-hidden="true"></i> Enregistrer
                    </button>
                    <button class="button" type="button" onclick="toggleEditMode()" style="flex: 1; background: var(--line); color: var(--ink);">
                        Retour
                    </button>
                </div>
            </form>
        </div>
    </section>
    <script>
        function toggleEditMode() {
            const viewMode = document.getElementById('view-mode');
            const editMode = document.getElementById('edit-mode');
            if (viewMode.style.display === 'none') {
                viewMode.style.display = 'block';
                editMode.style.display = 'none';
            } else {
                viewMode.style.display = 'none';
                editMode.style.display = 'block';
            }
        }
    </script>
                        @else
                            <!-- FORMULAIRE DE PLANIFICATION DE L'ENTRETIEN -->
                            <section class="panel" aria-labelledby="interview-title">
                                <div class="panel-heading">
                                    <div class="panel-title-group">
                                        <span class="panel-icon"><i class="fa-regular fa-calendar-check" aria-hidden="true"></i></span>
                                        <div>
                                            <h2 id="interview-title">Gestion Entretien</h2>
                                            <p>Planifier et inviter sur WhatsApp</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <form action="{{ route('admin.candidatures.entretien', $candidature->id) }}" method="POST" target="_blank" data-return-url="{{ route('admin.candidatures.index') }}">
                                        @csrf
                                        <div class="form-field">
                                            <label for="date_prevue">Date et heure du rendez-vous</label>
                                            <input class="control" id="date_prevue" type="datetime-local" name="date_prevue" value="{{ old('date_prevue') }}" required>
                                            @error('date_prevue')<span class="form-error">{{ $message }}</span>@enderror
                                        </div>
                                        <div class="form-field">
                                            <label for="lieu_ou_type">Lieu ou canal</label>
                                            <input class="control" id="lieu_ou_type" type="text" name="lieu_ou_type" value="{{ old('lieu_ou_type', 'Messagerie instantanée') }}" placeholder="Ex. Bureau ou visio">
                                        </div>
                                        <div class="form-field">
                                            <label for="notes">Notes internes</label>
                                            <textarea class="control" id="notes" name="notes" rows="2" placeholder="Commentaires...">{{ old('notes') }}</textarea>
                                        </div>
                                        <button class="button button-accent" type="submit">
                                            <i class="fa-solid fa-calendar-plus" aria-hidden="true"></i> Enregistrer l'entretien
                                        </button>
                                    </form>

                                    @if($dernierEntretien)
                                        @php
                                            $messageEntretien = "Bonjour {$candidature->chauffeur->prenom}, votre dossier VTC a retenu notre attention. Nous vous invitons à un entretien le ".date('d/m/Y à H:i', strtotime($dernierEntretien->date_prevue))." via {$dernierEntretien->lieu_ou_type}. Merci de confirmer votre présence.";
                                        @endphp
                                        <div class="divider"></div>
                                        <a class="button button-whatsapp" href="https://wa.me/{{ $telephone }}?text={{ urlencode($messageEntretien) }}" target="_blank" rel="noopener noreferrer">
                                            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Ouvrir WhatsApp avec l'invitation
                                        </a>
                                    @endif
                                </div>
                            </section>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        document.querySelectorAll('form[data-return-url]').forEach((form) => {
            form.addEventListener('submit', () => {
                window.setTimeout(() => {
                    window.location.assign(form.dataset.returnUrl);
                }, 100);
            });
        });
    </script>
</body>
</html>