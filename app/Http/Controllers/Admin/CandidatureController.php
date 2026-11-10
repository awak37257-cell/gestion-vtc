<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Entretien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CandidatureController extends Controller
{
    // Affiche la liste des candidatures avec filtres par statut
    public function index(Request $request)
    {
        $query = Candidature::with(['chauffeur', 'documents', 'entretiens']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $candidatures = $query->latest()->paginate(10);

        $currentTime = now();

        return view('admin.candidatures.index', compact('candidatures', 'currentTime'));
    }

    // Affiche le détail d'une candidature et ses documents
    public function show(Candidature $candidature): View
    {
        if ($candidature->statut === 'en_attente') {
            $candidature->update(['statut' => 'en_cours_examen']);
        }

        $candidature->load(['chauffeur', 'documents', 'entretiens']);
        $dernierEntretien = $candidature->entretiens->sortByDesc('cree_le')->first();
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

        // Enregistrement ou mise à jour de l'entretien associé
        $entretien = Entretien::updateOrCreate(
            ['candidature_id' => $candidature->id],
            [
                'date_prevue' => $request->date_prevue,
                'lieu_ou_type' => $request->lieu_ou_type ?? 'Messagerie instantanée',
                'notes' => $request->notes,
                'statut_entretien' => 'programme',
            ]
        );

        // Mise à jour automatique du statut global de la candidature
        $candidature->statut = 'entretien_programme';
        $candidature->save();

        $telephone = preg_replace('/[^0-9]/', '', $candidature->chauffeur->telephone_whatsapp);
        $dateFormatee = date('d/m/Y à H:i', strtotime($entretien->date_prevue));
        $lieuOuType = $entretien->lieu_ou_type;
        $message = "Bonjour {$candidature->chauffeur->prenom}, votre dossier VTC a retenu notre attention. Nous vous invitons à un entretien le {$dateFormatee} via {$lieuOuType}. Merci de confirmer votre présence.";

        return redirect()->away('https://wa.me/'.$telephone.'?text='.urlencode($message));
    }

    public function deciderApresEntretien(Request $request, Candidature $candidature): RedirectResponse
    {
        $validated = $request->validate([
            'decision' => 'required|in:valide,non_valide',
        ]);
        $entretien = $candidature->entretiens()->latest('id')->first();

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

        DB::transaction(function () use ($candidature, $entretien, $validated): void {
            $entretien->update(['statut_entretien' => 'termine']);
            $candidature->update([
                'statut' => $validated['decision'] === 'valide' ? 'accepte' : 'rejete',
            ]);
        });

        $message = $validated['decision'] === 'valide'
            ? 'Entretien validé : la candidature est automatiquement acceptée.'
            : 'Entretien non validé : la candidature est automatiquement rejetée.';

        return back()->with('success', $message);
    }
}
