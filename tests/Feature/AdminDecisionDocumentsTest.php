<?php

namespace Tests\Feature;

use App\Mail\CandidatureRejetee;
use App\Models\Candidature;
use App\Models\Chauffeur;
use App\Models\DocumentCandidature;
use App\Models\Entretien;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminDecisionDocumentsTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->admin()->create());
        $this->travelTo('2026-11-15 10:31:00');
    }

    private function candidatureApresEntretien(?bool $documentValide): Candidature
    {
        $chauffeur = Chauffeur::create([
            'prenom' => 'Awa',
            'nom' => 'Kone',
            'telephone_whatsapp' => '+2250700000033',
            'ville' => 'Abidjan',
        ]);

        $candidature = Candidature::create([
            'chauffeur_id' => $chauffeur->id,
            'numero_permis' => 'PERMIS-33',
            'annees_permis' => 5,
            'numero_piece_identite' => 'CNI-33',
            'statut' => 'entretien_programme',
        ]);

        DocumentCandidature::create([
            'candidature_id' => $candidature->id,
            'type_document' => 'permis_recto',
            'chemin_fichier' => 'documents_candidats/permis.jpg',
            'est_valide' => $documentValide,
        ]);

        Entretien::create([
            'candidature_id' => $candidature->id,
            'date_prevue' => '2026-11-15 10:30:00',
            'lieu_ou_type' => 'Appel vidéo',
            'statut_entretien' => 'programme',
        ]);

        return $candidature;
    }

    public function test_a_candidate_cannot_be_accepted_with_unvalidated_documents(): void
    {
        $candidature = $this->candidatureApresEntretien(null);

        $this->post(route('admin.candidatures.decisionEntretien', $candidature), ['decision' => 'valide'])
            ->assertSessionHasErrors('decision');

        $this->assertSame('entretien_programme', $candidature->fresh()->statut);
    }

    public function test_a_candidate_cannot_be_accepted_with_a_rejected_document(): void
    {
        $candidature = $this->candidatureApresEntretien(false);

        $this->post(route('admin.candidatures.decisionEntretien', $candidature), ['decision' => 'valide'])
            ->assertSessionHasErrors('decision');

        $this->assertSame('entretien_programme', $candidature->fresh()->statut);
    }

    public function test_a_candidate_with_all_documents_validated_can_be_accepted(): void
    {
        $candidature = $this->candidatureApresEntretien(true);

        $this->post(route('admin.candidatures.decisionEntretien', $candidature), ['decision' => 'valide'])
            ->assertSessionHasNoErrors();

        $this->assertSame('accepte', $candidature->fresh()->statut);
    }

    public function test_a_rejection_can_be_made_even_with_unvalidated_documents_and_stores_the_reason(): void
    {
        $candidature = $this->candidatureApresEntretien(null);

        $this->post(route('admin.candidatures.decisionEntretien', $candidature), [
            'decision' => 'non_valide',
            'motif_rejet' => 'Permis expiré',
        ])->assertSessionHasNoErrors();

        $candidature = $candidature->fresh();
        $this->assertSame('rejete', $candidature->statut);
        $this->assertSame('Permis expiré', $candidature->motif_rejet);
    }

    public function test_the_rejection_email_contains_the_reason(): void
    {
        $candidature = $this->candidatureApresEntretien(null);
        $candidature->update(['motif_rejet' => 'Permis expiré']);

        $html = (new CandidatureRejetee($candidature->fresh()->load('chauffeur')))->render();

        $this->assertStringContainsString('Permis expiré', $html);
        $this->assertStringContainsString('nous sommes au regret', $html);
    }

    public function test_the_list_asks_to_validate_documents_before_offering_acceptance(): void
    {
        $this->candidatureApresEntretien(null);

        $this->get(route('admin.candidatures.index'))
            ->assertOk()
            ->assertSee('Valider les documents')
            ->assertDontSee('Accepter');
    }
}