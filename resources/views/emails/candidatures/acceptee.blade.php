<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 20px; }
        .card { max-width: 580px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 32px; border: 1px solid #e2e8f0; }
        .header { text-align: center; border-bottom: 2px solid #10b981; padding-bottom: 20px; margin-bottom: 24px; }
        .badge { display: inline-block; background-color: #d1fae5; color: #047857; padding: 6px 16px; border-radius: 20px; font-weight: bold; font-size: 14px; margin-bottom: 16px; }
        .details { background-color: #f1f5f9; padding: 16px; border-radius: 8px; margin: 20px 0; font-size: 14px; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #64748b; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="TIHE'S Group Logo" style="max-height: 60px; margin-bottom: 10px;">
            <h2 style="margin: 0;">TIHE'S Group</h2>
        </div>
        
        <span class="badge">Candidature Retenue</span>

        <p>Bonjour <strong>{{ $candidature->chauffeur->prenom }} {{ $candidature->chauffeur->nom }}</strong>,</p>

        <p>Nous avons le plaisir de vous informer que votre candidature a été sélectionnée avec succès suite à l'examen de votre dossier.</p>

        <div class="details">
            <p style="margin:0 0 8px 0;"><strong>Information importante :</strong></p>
            <p style="margin:0;">Notre équipe RH va vous contacter sous peu par téléphone/WhatsApp au <strong>{{ $candidature->chauffeur->telephone_whatsapp }}</strong> pour convenir des détails de votre entretien d'embauche.</p>
        </div>

        <p>Merci de veiller à rester joignable.</p>

        <p>Cordialement,<br><strong>L'équipe de recrutement TIHE'S Group</strong></p>

        <div class="footer">
            <p>Ceci est un message automatique, merci de ne pas y répondre directement.</p>
        </div>
    </div>
</body>
</html>