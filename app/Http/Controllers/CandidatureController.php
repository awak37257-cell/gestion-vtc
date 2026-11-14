<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Chauffeur;
use App\Models\DocumentCandidature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CandidatureController extends Controller
{
    // Affiche le formulaire public de candidature
    public function create()
    {
        $dateLimite = Carbon::create(2026, 12, 31, 23, 59, 59, 'Africa/Abidjan');

        // Dès qu'un utilisateur essaie d'accéder au formulaire (/candidater) après la date limite
        if (Carbon::now('Africa/Abidjan')->greaterThan($dateLimite)) {
            return view('candidatures.ferme', compact('dateLimite'));
        }

        return view('candidatures.create', compact('dateLimite'));
    }

    // Traite et enregistre la candidature et ses documents
    public function store(Request $request)
    {
        $dateLimite = Carbon::create(2026, 12, 31, 23, 59, 59, 'Africa/Abidjan');

        // SÉCURITÉ : Empêcher l'enregistrement si la date limite est dépassée
        if (Carbon::now('Africa/Abidjan')->greaterThan($dateLimite)) {
            return view('candidatures.ferme', compact('dateLimite'));
        }

        // 1. Validation des données du formulaire selon les critères officiels
        $request->validate([
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'telephone_whatsapp' => 'required|string|max:20|unique:chauffeurs,telephone_whatsapp',
            'email' => 'nullable|email|max:255',
            'ville' => 'required|string|max:100',
            
            // Critères VTC - Expérience et Permis
            'numero_permis' => 'required|string|max:50',
            'annees_permis' => 'required|integer|min:3', // Minimum 3 ans requis
            'numero_piece_identite' => 'required|string|max:50',
            
            'possede_cacr' => 'nullable|boolean', // CACR facultatif
            'statut_casier' => 'required|string|in:disponible,en_cours', // Casier judiciaire

            // Validation des fichiers justificatifs (PDF ou Images, max 2Mo)
            'permis_recto' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'permis_verso' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'piece_identite' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'cacr_fichier' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'casier_fichier' => 'required_if:statut_casier,disponible|nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ], [
            'annees_permis.min' => 'Une expérience pratique d’au moins 3 ans est exigée pour la conduite automobile.',
            'casier_fichier.required_if' => 'Le fichier du casier judiciaire est obligatoire si votre casier est disponible.',
        ]);

        // Utilisation d'une transaction pour garantir l'intégrité atomique des données
        DB::beginTransaction();

        try {
            // 2. Enregistrement du chauffeur
            $chauffeur = Chauffeur::create([
                'prenom' => $request->prenom,
                'nom' => $request->nom,
                'telephone_whatsapp' => $request->telephone_whatsapp,
                'email' => $request->email,
                'ville' => $request->ville,
            ]);

            // 3. Création de la candidature avec attribution automatique du Permis B
            $candidature = Candidature::create([
                'chauffeur_id' => $chauffeur->id,
                'numero_permis' => $request->numero_permis,
                'categorie_permis' => 'B', // Fixé à B car exigé par la plateforme VTC
'annees_experience' => $request->annees_permis,                 'numero_piece_identite' => $request->numero_piece_identite,
                'possede_cacr' => $request->has('possede_cacr'),
                'statut_casier' => $request->statut_casier,
                'statut' => 'en_attente',
            ]);

            // 4. Gestion et stockage sécurisé des documents avec libellés courts
            $typesDocuments = [
                'permis_recto' => 'p_recto',
                'permis_verso' => 'p_verso',
                'piece_identite' => 'cni',
                'cacr_fichier' => 'cacr',
                'casier_fichier' => 'casier',
            ];

            foreach ($typesDocuments as $formKey => $dbValue) {
                if ($request->hasFile($formKey)) {
                    $chemin = $request->file($formKey)->store('documents_candidats', 'local');
                    
                    DocumentCandidature::create([
                        'candidature_id' => $candidature->id,
                        'type_document' => $dbValue,
                        'chemin_fichier' => $chemin,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('candidature.create')
                ->with('success', 'Votre candidature a été soumise avec succès ! Nous l\'examinerons sous peu.');

        } catch (\Exception $e) {
            DB::rollBack();
           dd($e->getMessage());// Enregistre l'erreur dans les logs Laravel sans bloquer l'affichage

            return back()->withInput()->with('error', 'Une erreur est survenue lors de l\'envoi de votre dossier. Veuillez réessayer.');
        }
    }
}