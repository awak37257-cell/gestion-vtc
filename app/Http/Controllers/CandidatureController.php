<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Chauffeur;
use App\Models\DocumentCandidature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Mail\CandidatureRejetee;
use Illuminate\Support\Facades\Mail;

class CandidatureController extends Controller
{
    // Affiche le formulaire public de candidature
    public function create()
{
    $dateLimite = Carbon::create(2026, 12, 31, 23, 59, 59, 'Africa/Abidjan');

    // Dès qu'un utilisateur essaie d'accéder au formulaire (/candidater)
    if (Carbon::now('Africa/Abidjan')->greaterThan($dateLimite)) {
        // On l'envoie directement sur la page "fermé"
        return view('candidatures.ferme', compact('dateLimite'));
    }

    return view('candidatures.create', compact('dateLimite'));
}

    // Traite et enregistre la candidature et ses documents
    public function store(Request $request)
    {
        $dateLimite = Carbon::create(2026, 12, 31, 23, 59, 59);

        // SECURITÉ : Empêcher l'enregistrement si la date limite est dépassée
        if (Carbon::now()->greaterThan($dateLimite)) {
            return view('candidatures.ferme', compact('dateLimite'));
        }

        // 1. Validation des données du formulaire et des fichiers joints
        $request->validate([
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'telephone_whatsapp' => 'required|string|max:20|unique:chauffeurs,telephone_whatsapp',
            'email' => 'nullable|email|max:255',
            'ville' => 'required|string|max:100',
            'numero_permis' => 'required|string|max:50',
            'annees_remis' => 'sometimes|integer', // correction éventuelle
            'annees_permis' => 'required|integer|min:1',
            'numero_piece_identite' => 'required|string|max:50',
            // Validation des fichiers (PDF ou Images acceptés, max 2Mo par fichier)
            'permis_recto' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'permis_verso' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'piece_identite' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        // Utilisation d'une transaction pour garantir l'intégrité des données
        DB::beginTransaction();

        try {
            // 2. Enregistrement ou récupération du chauffeur
            $chauffeur = Chauffeur::create([
                'prenom' => $request->prenom,
                'nom' => $request->nom,
                'telephone_whatsapp' => $request->telephone_whatsapp,
                'email' => $request->email,
                'ville' => $request->ville,
            ]);

            // 3. Création de la candidature associée
            $candidature = Candidature::create([
                'chauffeur_id' => $chauffeur->id,
                'numero_permis' => $request->numero_permis,
                'annees_permis' => $request->annees_permis,
                'numero_piece_identite' => $request->numero_piece_identite,
                'statut' => 'en_attente',
            ]);

            // 4. Gestion et stockage sécurisé des documents
            $typesDocuments = ['permis_recto', 'permis_verso', 'piece_identite'];

            foreach ($typesDocuments as $type) {
                if ($request->hasFile($type)) {
                    // Stockage dans le dossier 'storage/app/public/documents_candidats'
// Avant : ->store('documents_candidats', 'public')
$chemin = $request->file($type)->store('documents_candidats', 'local');
                    DocumentCandidature::create([
                        'candidature_id' => $candidature->id,
                        'type_document' => $type,
                        'chemin_fichier' => $chemin,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('candidature.create')
                ->with('success', 'Votre candidature a été soumise avec succès ! Nous l\'examinerons sous peu.');

       } catch (\Exception $e) {
    DB::rollBack();
    report($e);

    return back()->withInput()->with('error', 'Une erreur est survenue lors de l\'envoi de votre dossier. Veuillez réessayer.');
}
    }
    
}