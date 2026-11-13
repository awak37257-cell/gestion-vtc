<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Lien invalide — TIHE'S Group</title>
    @include('documents.partials.styles')
</head>
<body>
    <main class="card center" aria-labelledby="invalide-title">
        <header class="brand-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo TIHE'S Group" class="brand-logo">
            <span class="brand-name">TIHE'S<span>Group</span></span>
        </header>

        <div class="icon-badge icon-warn" aria-hidden="true"><i class="fa-solid fa-link-slash"></i></div>
        <h1 id="invalide-title">Ce lien n'est plus valide</h1>
        <p class="lead">
            Le lien a expiré ou a déjà été utilisé. Contactez TIHE'S Group sur WhatsApp
            pour recevoir un nouveau lien.
        </p>
    </main>
</body>
</html>