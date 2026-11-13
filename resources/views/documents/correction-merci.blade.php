<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Merci — TIHE'S Group</title>
    @include('documents.partials.styles')
</head>
<body>
    <main class="card center" aria-labelledby="merci-title">
        <header class="brand-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo TIHE'S Group" class="brand-logo">
            <span class="brand-name">TIHE'S<span>Group</span></span>
        </header>

        <div class="icon-badge icon-ok" aria-hidden="true"><i class="fa-solid fa-circle-check"></i></div>
        <h1 id="merci-title">Merci, c'est bien reçu</h1>
        <p class="lead">
            Il n'y a plus de document à envoyer pour le moment.
            Notre équipe va examiner vos documents et reviendra vers vous très bientôt.
        </p>
    </main>
</body>
</html>