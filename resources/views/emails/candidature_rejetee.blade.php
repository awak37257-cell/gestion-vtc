<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DM Sans', Arial, sans-serif; background: #f8fafc; color: #0f172a; padding: 20px; }
        .email-card { max-width: 500px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0; }
        h2 { color: #0f172a; font-size: 1.25rem; margin-top: 0; }
        p { color: #475569; line-height: 1.6; font-size: 0.95rem; }
        .footer { margin-top: 25px; font-size: 0.8rem; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="email-card">
        <h2>Bonjour {{ $candidature->chauffeur->prenom }},</h2>
        <p>Nous vous remercions pour l'intérêt que vous avez porté à <strong>TIHE'S Group</strong> et pour le temps consacré à votre dossier de candidature.</p>
       <p>Après un examen attentif de votre profil et de votre entretien, nous sommes au regret de vous informer que nous ne pourrons pas donner suite à votre candidature pour cette session.</p>
@if($candidature->motif_rejet)
    <p><strong>Motif :</strong> {{ $candidature->motif_rejet }}</p>
@endif
        <p>Nous vous souhaitons beaucoup de succès dans vos futurs projets professionnels.</p>
        <div class="footer">
            Cordialement,<br>
            <strong>L'équipe de recrutement TIHE'S Group</strong>
        </div>
    </div>
</body>
</html>