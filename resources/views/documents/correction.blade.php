<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Renvoyer des documents — TIHE'S Group</title>
    @include('documents.partials.styles')
</head>
<body>
    <main class="card" aria-labelledby="correction-title">
        <header class="brand-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo TIHE'S Group" class="brand-logo">
            <span class="brand-name">TIHE'S<span>Group</span></span>
        </header>

        <span class="eyebrow">Documents à renvoyer</span>
        <h1 id="correction-title">Bonjour {{ $candidature->chauffeur->prenom }}</h1>
        <p class="lead">
            Certains documents de votre dossier n'ont pas pu être validés. Merci de les renvoyer ci-dessous,
            avec une photo bien nette et lisible.
        </p>

        @if(session('success'))
            <div class="alert alert-success" role="status"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>{{ session('success') }}</span></div>
        @endif

        @if($errors->any())
            <div class="alert alert-error" role="alert">
                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                <ul>@foreach($errors->all() as $erreur)<li>{{ $erreur }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('documents.correction.store', $jeton) }}" enctype="multipart/form-data">
            @csrf
            @foreach($rejetes as $document)
                <div class="doc-field">
                    <label for="document-{{ $document->id }}">{{ $document->libelle() }}</label>
                    @if($document->note_rejet)
                        <p class="reason"><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i><span>Motif : {{ $document->note_rejet }}</span></p>
                    @endif
                    <input id="document-{{ $document->id }}" type="file" name="documents[{{ $document->id }}]" accept=".jpg,.jpeg,.png,.pdf">
                </div>
            @endforeach

            <p class="hint">Formats acceptés : JPG, PNG ou PDF, 2 Mo maximum par document. Vous pouvez aussi envoyer les documents un par un.</p>

            <button class="button" type="submit"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Envoyer les documents</button>
        </form>

        <p class="note">
            <i class="fa-solid fa-lock" aria-hidden="true"></i>
            Ce lien est personnel et valable jusqu'au {{ $candidature->jeton_correction_expire_le->format('d/m/Y') }}. Ne le partagez pas.
        </p>
    </main>
</body>
</html>