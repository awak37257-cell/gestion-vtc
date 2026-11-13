<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentCorrectionController extends Controller
{
    // Page publique (lien personnel) : le candidat renvoie uniquement les documents rejetés
    public function show(string $jeton): View|Response|RedirectResponse
    {
        $candidature = $this->candidatureDuJeton($jeton);

        if (! $candidature) {
            return response()->view('documents.correction-invalide', [], 404);
        }

        $rejetes = $candidature->documents()->where('est_valide', false)->orderBy('id')->get();

        if ($rejetes->isEmpty()) {
            return redirect()->route('documents.correction.merci');
        }

        return view('documents.correction', compact('candidature', 'rejetes', 'jeton'));
    }

    public function store(Request $request, string $jeton): RedirectResponse
    {
        $candidature = $this->candidatureDuJeton($jeton);

        abort_unless($candidature, 404);

        $request->validate([
            'documents' => ['required', 'array'],
            'documents.*' => ['file', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],
        ], [
            'documents.required' => 'Veuillez choisir au moins un document à renvoyer.',
            'documents.*.file' => 'Le fichier envoyé est invalide.',
            'documents.*.mimes' => 'Chaque document doit être une image (JPG, PNG) ou un PDF.',
            'documents.*.max' => 'Chaque document ne doit pas dépasser 2 Mo.',
            'documents.*.uploaded' => 'Le fichier n’a pas pu être envoyé (taille maximale : 2 Mo).',
        ]);

        // Seuls les documents actuellement rejetés peuvent être remplacés
        $rejetes = $candidature->documents()->where('est_valide', false)->get()->keyBy('id');
        $fichiers = array_intersect_key($request->file('documents') ?? [], $rejetes->all());

        if ($fichiers === []) {
            return redirect()->route('documents.correction', $jeton)
                ->withErrors(['documents' => 'Ces documents ne sont pas à renvoyer.']);
        }

        foreach ($fichiers as $id => $fichier) {
            $document = $rejetes[$id];
            $ancienChemin = $document->chemin_fichier;

            // Stockage privé, comme pour la candidature initiale
            $nouveauChemin = $fichier->store('documents_candidats', 'local');

            $document->update([
                'chemin_fichier' => $nouveauChemin,
                'est_valide' => null,
                'note_rejet' => null,
                'renvoye_le' => now(),
            ]);

            // L'ancien fichier rejeté n'est plus utile : on le supprime
            Storage::disk('local')->delete($ancienChemin);
        }

        $restants = $candidature->documents()->where('est_valide', false)->count();

        if ($restants === 0) {
            // Tout a été renvoyé : le lien ne sert plus
            $candidature->update(['jeton_correction' => null, 'jeton_correction_expire_le' => null]);

            return redirect()->route('documents.correction.merci');
        }

        return redirect()->route('documents.correction', $jeton)
            ->with('success', "Documents reçus. Il reste {$restants} document(s) à renvoyer.");
    }

    public function merci(): View
    {
        return view('documents.correction-merci');
    }

    // On compare l'empreinte du jeton : la base ne contient jamais le lien en clair
    private function candidatureDuJeton(string $jeton): ?Candidature
    {
        return Candidature::with('chauffeur')
            ->where('jeton_correction', hash('sha256', $jeton))
            ->where('jeton_correction_expire_le', '>', now())
            ->first();
    }
}