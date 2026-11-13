<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\DocumentCandidature;
use App\Models\Entretien;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Mail\CandidatureAcceptee;
use App\Mail\CandidatureRejetee;

class CandidatureController extends Controller
{
    // Affiche la liste des candidatures avec recherche et filtres (statut, dates)
    public function index(Request $request)
    {
        // withQueryString : garde la recherche et les filtres en changeant de page
        $candidatures = $this->candidaturesFiltrees($request)->paginate(10)->withQueryString();

        $currentTime = now();

        return view('admin.candidatures.index', compact('candidatures', 'currentTime'));
    }

    // Exporte en CSV (ouvrable dans Excel) la liste telle qu'elle est filtrée à l'écran
    public function export(Request $request): StreamedResponse
    {
        $candidatures = $this->candidaturesFiltrees($request)->get();

        $statuts = [
            'en_attente' => 'En attente',
            'en_cours_examen' => 'En cours d\'examen',
            'entretien_programme' => 'Entretien programmé',
            'accepte' => 'Accepté',
            'rejete' => 'Rejeté',
        ];

        $nomFichier = 'candidatures-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($candidatures, $statuts): void {
            $sortie = fopen('php://output', 'w');

            // BOM UTF-8 + séparateur « ; » : Excel (version française) affiche bien les accents et les colonnes
            fwrite($sortie, "\xEF\xBB\xBF");

            fputcsv($sortie, [
                'Prénom', 'Nom', 'Téléphone WhatsApp', 'Ville', 'N° permis', 'Années de permis',
                'N° carte VTC', 'Statut', 'Reçue le', 'Documents validés', 'Motif de refus',
            ], ';', '"', '');

            foreach ($candidatures as $candidature) {
                $chauffeur = $candidature->chauffeur;

                fputcsv($sortie, [
                    $this->celluleSure($chauffeur?->prenom),
                    $this->celluleSure($chauffeur?->nom),
                    $this->celluleTelephone($chauffeur?->telephone_whatsapp),
                    $this->celluleSure($chauffeur?->ville),
                    $this->celluleSure($candidature->numero_permis),
                    $candidature->annees_permis,
                    $this->celluleSure($candidature->numero_carte_vtc),
                    $statuts[$candidature->statut] ?? $candidature->statut,
                    $candidature->created_at?->format('d/m/Y H:i'),
                    $this->documentsTousValides($candidature) ? 'Oui' : 'Non',
                    $this->celluleSure($candidature->motif_rejet),
                ], ';', '"', '');
            }

            fclose($sortie);
        }, $nomFichier, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    // Requête commune à la liste et à l'export : recherche, statut et dates
    private function candidaturesFiltrees(Request $request): Builder
    {
        // Seuls les critères valides sont appliqués (un critère invalide est simplement ignoré)
        $filtres = Validator::make($request->all(), [
            'q' => ['nullable', 'string', 'max:100'],
            'du' => ['nullable', 'date'],
            'au' => ['nullable', 'date', 'after_or_equal:du'],
        ])->valid();

        $query = Candidature::with(['chauffeur', 'documents', 'entretiens']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Chaque mot saisi doit correspondre au prénom, nom, téléphone, ville ou numéro de permis
        foreach (preg_split('/\s+/', trim($filtres['q'] ?? ''), -1, PREG_SPLIT_NO_EMPTY) as $mot) {
            $like = '%'.addcslashes($mot, '%_\\').'%';

            $query->where(function ($recherche) use ($like): void {
                $recherche->where('numero_permis', 'like', $like)
                    ->orWhereHas('chauffeur', function ($chauffeur) use ($like): void {
                        $chauffeur->where('prenom', 'like', $like)
                            ->orWhere('nom', 'like', $like)
                            ->orWhere('telephone_whatsapp', 'like', $like)
                            ->orWhere('ville', 'like', $like);
                    });
            });
        }

        if (! empty($filtres['du'])) {
            $query->whereDate('created_at', '>=', $filtres['du']);
        }

        if (! empty($filtres['au'])) {
            $query->whereDate('created_at', '<=', $filtres['au']);
        }

        return $query->latest()->latest('id');
    }

    // Empêche Excel d'exécuter comme une formule un texte saisi par un candidat (=, +, -, @)
    private function celluleSure(?string $valeur): string
    {
        $valeur = (string) $valeur;

        return preg_match('/^[=+\-@\t\r]/', $valeur) ? "'".$valeur : $valeur;
    }

    // Garde le téléphone lisible dans Excel (sinon +225… devient 2,25E+12)
    private function celluleTelephone(?string $telephone): string
    {
        $chiffres = preg_replace('/[^0-9+]/', '', (string) $telephone);

        return $chiffres === '' ? '' : '="'.$chiffres.'"';
    }

    // Sert un document du dossier depuis le disque privé (réservé aux admins)
    public function document(DocumentCandidature $document): StreamedResponse
    {
        $disk = Storage::disk('local');

        abort_unless($disk->exists($document->chemin_fichier), 404);

        return $disk->response($document->chemin_fichier);
    }

    // Valide ou rejette un document (avec motif obligatoire en cas de rejet)
    public function validerDocument(Request $request, DocumentCandidature $document): RedirectResponse
    {
        $data = $request->validate([
            'verdict' => ['required', 'in:valide,rejete'],
            'note_rejet' => ['required_if:verdict,rejete', 'nullable', 'string', 'max:255'],
        ], [
            'note_rejet.required_if' => 'Indiquez le motif du rejet du document.',
        ]);

        $estValide = $data['verdict'] === 'valide';

        $document->update([
            'est_valide' => $estValide,
            'note_rejet' => $estValide ? null : $data['note_rejet'],
        ]);

        return back()->with('success', $estValide ? 'Document validé.' : 'Document rejeté.');
    }

    // Génère un lien personnel (valable 7 jours) et ouvre WhatsApp pour demander les documents rejetés
public function demanderCorrection(Candidature $candidature): RedirectResponse
{
    $rejetes = $candidature->documents()->where('est_valide', false)->orderBy('id')->get();

    if ($rejetes->isEmpty() || $candidature->statut === 'rejete') {
        return back()->withErrors([
            'correction' => 'Aucun document rejeté à redemander pour ce dossier.',
        ]);
    }

    $jeton = Str::random(48);

    $candidature->update([
        'jeton_correction' => hash('sha256', $jeton),
        'jeton_correction_expire_le' => now()->addDays(7),
    ]);

    $liste = $rejetes
        ->map(fn ($document) => '- '.$document->libelle().($document->note_rejet ? ' ('.$document->note_rejet.')' : ''))
        ->implode("\n");

    $message = "Bonjour {$candidature->chauffeur->prenom}, certains documents de votre dossier VTC doivent être renvoyés :\n"
        .$liste."\n"
        .'Merci de les envoyer depuis ce lien personnel, valable 7 jours : '.route('documents.correction', $jeton);

    $telephone = preg_replace('/[^0-9]/', '', $candidature->chauffeur->telephone_whatsapp);

    return redirect()->away('https://wa.me/'.$telephone.'?text='.urlencode($message));
}
    // Affiche le détail d'une candidature et ses documents
    public function show(Candidature $candidature): View
    {
        if ($candidature->statut === 'en_attente') {
            $candidature->update(['statut' => 'en_cours_examen']);
        }

        $candidature->load(['chauffeur', 'documents', 'entretiens']);
        $dernierEntretien = $candidature->entretiens->sortByDesc('id')->first();
        $entretienTermine = $dernierEntretien
            && now()->greaterThanOrEqualTo($dernierEntretien->date_prevue);

        return view('admin.show', compact('candidature', 'dernierEntretien', 'entretienTermine'));
    }

    public function planifierEntretien(Request $request, Candidature $candidature): RedirectResponse
    {
        // Validation des informations de rendez-vous
        $request->validate([
            'date_prevue' => 'required|date',
            'lieu_ou_type' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $donnees = [
            'date_prevue' => $request->date_prevue,
            'lieu_ou_type' => $request->lieu_ou_type ?? 'Messagerie instantanée',
            'notes' => $request->notes,
            'statut_entretien' => 'programme',
        ];

        // On met à jour l'entretien en cours ; après une absence, on en crée un nouveau (l'historique est conservé)
        $entretien = $candidature->entretiens()->where('statut_entretien', 'programme')->latest('id')->first();

        if ($entretien) {
            $entretien->update($donnees);
        } else {
            $entretien = Entretien::create($donnees + ['candidature_id' => $candidature->id]);
        }

        // Mise à jour automatique du statut global de la candidature
        $candidature->statut = 'entretien_programme';
        $candidature->save();

        $telephone = preg_replace('/[^0-9]/', '', $candidature->chauffeur->telephone_whatsapp);
        $dateFormatee = date('d/m/Y à H:i', strtotime($entretien->date_prevue));
        $lieuOuType = $entretien->lieu_ou_type;
        $message = "Bonjour {$candidature->chauffeur->prenom}, votre dossier VTC a retenu notre attention. Nous vous invitons à un entretien le {$dateFormatee} via {$lieuOuType}. Merci de confirmer votre présence.";

        return redirect()->away('https://wa.me/'.$telephone.'?text='.urlencode($message));
    }

    public function modifierEntretien(Request $request, $id)
    {
        $request->validate([
            'date_prevue' => 'required|date',
            'lieu_ou_type' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $candidature = \App\Models\Candidature::findOrFail($id);
        $entretien = $candidature->entretiens()->latest('id')->first();

        if ($entretien) {
            $entretien->update([
                'date_prevue' => $request->date_prevue,
                'lieu_ou_type' => $request->lieu_ou_type,
                'notes' => $request->notes,
            ]);
        }

        return back()->with('success', "Les informations de l'entretien ont été mises à jour avec succès.");
    }

    public function deciderApresEntretien(Request $request, Candidature $candidature): RedirectResponse
    {
        $validated = $request->validate([
            'decision' => 'required|in:valide,non_valide',
            'motif_rejet' => 'nullable|string|max:255',
        ]);
        $entretien = $candidature->entretiens()->latest('id')->first();

        // 1) D'abord : la date et l'heure de l'entretien
        if (
            ! $entretien
            || $entretien->statut_entretien !== 'programme'
            || now()->lt($entretien->date_prevue)
            || $candidature->statut !== 'entretien_programme'
        ) {
            return back()->withErrors([
                'decision' => 'La décision sera disponible après la date et l’heure de l’entretien programmé.',
            ]);
        }

        // 2) Ensuite : on n'accepte un candidat que si tous ses documents ont été validés
        if ($validated['decision'] === 'valide' && ! $this->documentsTousValides($candidature)) {
            return back()->withErrors([
                'decision' => 'Impossible d\'accepter ce candidat : tous ses documents doivent d\'abord être validés.',
            ]);
        }

        // 3) Enfin : l'enregistrement de la décision
        DB::transaction(function () use ($candidature, $entretien, $validated): void {
            $entretien->update(['statut_entretien' => 'termine']);
            $candidature->update([
                'statut' => $validated['decision'] === 'valide' ? 'accepte' : 'rejete',
                'motif_rejet' => $validated['decision'] === 'non_valide'
                    ? ($validated['motif_rejet'] ?? null)
                    : null,
            ]);
        });

        $message = $validated['decision'] === 'valide'
            ? 'Entretien validé : la candidature est automatiquement acceptée.'
            : 'Entretien non validé : la candidature est automatiquement rejetée.';

        return back()->with('success', $message);
    }

    // Candidat absent à son entretien : le dossier repasse « en cours d'examen » (à reprogrammer ou à refuser)
    public function marquerAbsent(Candidature $candidature): RedirectResponse
    {
        $entretien = $candidature->entretiens()->latest('id')->first();

        if (
            ! $entretien
            || $entretien->statut_entretien !== 'programme'
            || now()->lt($entretien->date_prevue)
            || $candidature->statut !== 'entretien_programme'
        ) {
            return back()->withErrors([
                'absent' => 'Vous pourrez marquer le candidat absent après la date et l’heure de l’entretien programmé.',
            ]);
        }

        DB::transaction(function () use ($candidature, $entretien): void {
            $entretien->update(['statut_entretien' => 'absent']);
            $candidature->update(['statut' => 'en_cours_examen']);
        });

        return back()->with('success', 'Candidat marqué absent. Vous pouvez programmer un nouvel entretien ou refuser le dossier.');
    }

    // Refuse un candidat resté absent à son entretien
    public function refuserApresAbsence(Request $request, Candidature $candidature): RedirectResponse
    {
        $validated = $request->validate([
            'motif_rejet' => 'nullable|string|max:255',
        ]);

        $entretien = $candidature->entretiens()->latest('id')->first();

        if (! $entretien || $entretien->statut_entretien !== 'absent' || $candidature->statut !== 'en_cours_examen') {
            return back()->withErrors([
                'absent' => 'Seul un candidat marqué absent à son entretien peut être refusé ici.',
            ]);
        }

        $candidature->update([
            'statut' => 'rejete',
            'motif_rejet' => ($validated['motif_rejet'] ?? null) ?: 'Absence à l’entretien programmé.',
        ]);

        return back()->with('success', 'Candidature refusée : le candidat était absent à l’entretien.');
    }

    // Vrai si le dossier contient des documents et qu'ils sont tous validés
    private function documentsTousValides(Candidature $candidature): bool
    {
        $documents = $candidature->documents;

        return $documents->isNotEmpty()
            && $documents->every(fn ($document) => $document->est_valide === true);
    }

    // Bilan et récapitulatif des entretiens du jour
    public function recapitulatifJournee(Request $request)
    {
        // Récupère la date choisie par l'admin, ou prend la date du jour par défaut
        $dateCible = $request->input('date', Carbon::today()->toDateString());

        $acceptes = \App\Models\Candidature::with('chauffeur')
            ->where('statut', 'accepte')
            ->whereDate('updated_at', $dateCible)
            ->get();

        $refuses = \App\Models\Candidature::with('chauffeur')
            ->where('statut', 'rejete')
            ->whereDate('updated_at', $dateCible)
            ->get();

        return view('admin.entretiens.recapitulatif', compact('acceptes', 'refuses', 'dateCible'));
    }

    // Envoi groupé des notifications d'acceptation et de refus
    public function envoyerGroupesDuJour(Request $request)
    {
        // Récupérer la date du formulaire ou prendre la date d'aujourd'hui par défaut
        $dateCible = $request->input('date', Carbon::today()->toDateString());

        // 1. Traitement des candidats ACCEPTÉS pour la date choisie
        $acceptes = Candidature::with('chauffeur')
            ->where('statut', 'accepte')
            ->whereDate('updated_at', $dateCible)
            ->get();

        $nbAcceptes = 0;
        foreach ($acceptes as $candidature) {
            if (! empty($candidature->chauffeur->email)) {
                Mail::to($candidature->chauffeur->email)->send(new CandidatureAcceptee($candidature));
                $nbAcceptes++;
            }
        }

        // 2. Traitement des candidats REFUSÉS pour la date choisie
        $refuses = Candidature::with('chauffeur')
            ->where('statut', 'rejete')
            ->whereDate('updated_at', $dateCible)
            ->get();

        $nbRefuses = 0;
        foreach ($refuses as $candidature) {
            if (! empty($candidature->chauffeur->email)) {
                Mail::to($candidature->chauffeur->email)->send(new CandidatureRejetee($candidature));
                $nbRefuses++;
            }
        }

        $total = $nbAcceptes + $nbRefuses;
        $dateFormatee = Carbon::parse($dateCible)->format('d/m/Y');

        return back()->with('success', "Notifications envoyées pour la journée du $dateFormatee ($total au total : $nbAcceptes acceptation(s), $nbRefuses refus) !");
    }

    public function annulerEntretien($id)
    {
        $candidature = \App\Models\Candidature::findOrFail($id);

        // Récupérer et supprimer le dernier entretien
        $entretien = $candidature->entretiens()->latest('id')->first();
        if ($entretien) {
            $entretien->delete();
        }

        // Repasser la candidature en "En cours d'examen"
        $candidature->update([
            'statut' => 'en_cours_examen',
        ]);

        return back()->with('success', "L'entretien a été annulé avec succès et le dossier est repassé en cours d'examen.");
    }
}