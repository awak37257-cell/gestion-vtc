<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\Chauffeur;
use App\Models\User;
use App\Models\DocumentCandidature;
use App\Models\Entretien;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminCandidatureShowTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_a_candidature_without_an_interview_renders_the_interview_form(): void
    {
        $candidature = $this->createCandidature();

        $response = $this->get(route('admin.candidatures.show', $candidature));

        $response->assertOk();
        $response->assertViewIs('admin.show');
        $response->assertSee('src="'.asset('images/logo.png').'"', false);
        $response->assertSee('name="date_prevue"', false);
        $response->assertSee('Pièces justificatives');
        $response->assertSee('target="_blank"', false);
        $response->assertDontSee('name="decision"', false);
        $response->assertDontSee('Rejeter le dossier');
        $response->assertDontSee('https://wa.me/', false);
        $response->assertDontSee('name="statut"', false);
        $this->assertDatabaseHas('candidatures', [
            'id' => $candidature->id,
            'statut' => 'en_cours_examen',
        ]);
    }
    protected function setUp(): void
{
    parent::setUp();

    $this->actingAs(User::factory()->admin()->create());
}

    public function test_the_latest_interview_details_prefill_the_interview_form(): void
    {
        $candidature = $this->createCandidature();
        Entretien::create([
            'candidature_id' => $candidature->id,
            'date_prevue' => '2026-11-15 10:30:00',
            'lieu_ou_type' => 'Appel vidéo',
            'notes' => 'Préparer les documents',
            'statut_entretien' => 'programme',
        ]);

        $response = $this->get(route('admin.candidatures.show', $candidature));

        $response->assertOk();
        $response->assertViewIs('admin.show');
        $response->assertSee('value="2026-11-15T10:30"', false);
        $response->assertSee('value="Appel vidéo"', false);
        $response->assertSee('Préparer les documents');
    }

    public function test_saving_an_interview_displays_a_prefilled_whatsapp_invitation(): void
    {
        $this->travelTo('2026-11-10 08:00:00');
        $candidature = $this->createCandidature();

        $response = $this->from(route('admin.candidatures.show', $candidature))
            ->post(route('admin.candidatures.entretien', $candidature), [
                'date_prevue' => '2026-11-15T10:30',
                'lieu_ou_type' => 'Appel vidéo',
                'notes' => 'Préparer les documents',
            ]);

        $response->assertRedirect(
            'https://wa.me/2250700000020?text='.urlencode('Bonjour Awa, votre dossier VTC a retenu notre attention. Nous vous invitons à un entretien le 15/11/2026 à 10:30 via Appel vidéo. Merci de confirmer votre présence.')
        );
        $this->assertDatabaseHas('entretiens', [
            'candidature_id' => $candidature->id,
            'date_prevue' => '2026-11-15T10:30',
            'lieu_ou_type' => 'Appel vidéo',
            'notes' => 'Préparer les documents',
        ]);
        $this->assertDatabaseHas('candidatures', [
            'id' => $candidature->id,
            'statut' => 'entretien_programme',
        ]);

        $showResponse = $this->get(route('admin.candidatures.show', $candidature));

        $showResponse->assertOk();
        $showResponse->assertDontSee('name="decision"', false);
        $showResponse->assertDontSee('Valider — Accepter');
        $showResponse->assertDontSee('Rejeter le dossier');
        $showResponse->assertSee(
            'https://wa.me/2250700000020?text='.urlencode('Bonjour Awa, votre dossier VTC a retenu notre attention. Nous vous invitons à un entretien le 15/11/2026 à 10:30 via Appel vidéo. Merci de confirmer votre présence.'),
            false,
        );
    }

    public function test_interview_decision_is_unavailable_until_the_scheduled_time(): void
    {
        $this->travelTo('2026-11-15 10:29:00');
        $candidature = $this->createCandidature();
        $candidature->update(['statut' => 'entretien_programme']);
        $entretien = $this->createEntretien($candidature, '2026-11-15 10:30:00');

        $response = $this->get(route('admin.candidatures.show', $candidature));

        $response->assertOk();
        $response->assertDontSee('name="decision"', false);
        $response->assertDontSee('Valider — Accepter');
        $response->assertDontSee('Rejeter le dossier');

        $decisionResponse = $this->from(route('admin.candidatures.show', $candidature))
            ->post(route('admin.candidatures.decisionEntretien', $candidature), [
                'decision' => 'valide',
            ]);

        $decisionResponse->assertRedirect(route('admin.candidatures.show', $candidature));
        $decisionResponse->assertSessionHasErrors([
            'decision' => 'La décision sera disponible après la date et l’heure de l’entretien programmé.',
        ]);
        $this->assertDatabaseHas('entretiens', [
            'id' => $entretien->id,
            'statut_entretien' => 'programme',
        ]);
        $this->assertDatabaseHas('candidatures', [
            'id' => $candidature->id,
            'statut' => 'entretien_programme',
        ]);
    }

    public function test_a_validated_interview_automatically_accepts_the_candidate(): void
    {
        $this->travelTo('2026-11-15 10:31:00');
        $candidature = $this->createCandidature();
        DocumentCandidature::create([
    'candidature_id' => $candidature->id,
    'type_document' => 'permis_recto',
    'chemin_fichier' => 'documents_candidats/permis.jpg',
    'est_valide' => true,
]);
        $candidature->update(['statut' => 'entretien_programme']);
        $entretien = $this->createEntretien($candidature, '2026-11-15 10:30:00');

        $response = $this->from(route('admin.candidatures.show', $candidature))
            ->post(route('admin.candidatures.decisionEntretien', $candidature), [
                'decision' => 'valide',
            ]);

        $response->assertRedirect(route('admin.candidatures.show', $candidature));
        $response->assertSessionHas('success', 'Entretien validé : la candidature est automatiquement acceptée.');
        $this->assertDatabaseHas('candidatures', [
            'id' => $candidature->id,
            'statut' => 'accepte',
        ]);
        $this->assertDatabaseHas('entretiens', [
            'id' => $entretien->id,
            'statut_entretien' => 'termine',
        ]);

        $showResponse = $this->get(route('admin.candidatures.show', $candidature));

        $showResponse->assertOk();
        $showResponse->assertSee('Accepté');
        $showResponse->assertSee('Candidature validée');
        $showResponse->assertSee('Cette candidature a été acceptée.');
        $showResponse->assertDontSee('Gestion Entretien');
        $showResponse->assertDontSee('name="date_prevue"', false);
        $showResponse->assertDontSee('Ouvrir WhatsApp avec l’invitation');
        $showResponse->assertDontSee('15/11/2026');
        $showResponse->assertDontSee('name="decision"', false);
        $showResponse->assertDontSee('Décision du dossier');
    }

    public function test_a_nonvalidated_interview_automatically_rejects_the_candidate(): void
    {
        $this->travelTo('2026-11-15 10:31:00');
        $candidature = $this->createCandidature();
        $candidature->update(['statut' => 'entretien_programme']);
        $entretien = $this->createEntretien($candidature, '2026-11-15 10:30:00');

        $response = $this->from(route('admin.candidatures.show', $candidature))
            ->post(route('admin.candidatures.decisionEntretien', $candidature), [
                'decision' => 'non_valide',
            ]);

        $response->assertRedirect(route('admin.candidatures.show', $candidature));
        $response->assertSessionHas('success', 'Entretien non validé : la candidature est automatiquement rejetée.');
        $this->assertDatabaseHas('candidatures', [
            'id' => $candidature->id,
            'statut' => 'rejete',
        ]);
        $this->assertDatabaseHas('entretiens', [
            'id' => $entretien->id,
            'statut_entretien' => 'termine',
        ]);
    }

    private function createEntretien(Candidature $candidature, string $datePrevue): Entretien
    {
        return Entretien::create([
            'candidature_id' => $candidature->id,
            'date_prevue' => $datePrevue,
            'lieu_ou_type' => 'Appel vidéo',
            'notes' => 'Préparer les documents',
            'statut_entretien' => 'programme',
        ]);
    }

    private function createCandidature(): Candidature
    {
        $chauffeur = Chauffeur::create([
            'prenom' => 'Awa',
            'nom' => 'Kone',
            'telephone_whatsapp' => '+2250700000020',
            'ville' => 'Abidjan',
        ]);

        return Candidature::create([
            'chauffeur_id' => $chauffeur->id,
            'numero_permis' => 'PERMIS-20',
            'annees_permis' => 5,
            'numero_carte_vtc' => null,
            'numero_piece_identite' => 'CNI-20',
            'statut' => 'en_attente',
        ]);
    }
}
