<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\Chauffeur;
use App\Models\DocumentCandidature;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminDocumentValidationTest extends TestCase
{
    use LazilyRefreshDatabase;

    private function document(): DocumentCandidature
    {
        $chauffeur = Chauffeur::create([
            'prenom' => 'Awa',
            'nom' => 'Kone',
            'telephone_whatsapp' => '+2250700000055',
            'ville' => 'Abidjan',
        ]);

        $candidature = Candidature::create([
            'chauffeur_id' => $chauffeur->id,
            'numero_permis' => 'PERMIS-55',
            'annees_permis' => 5,
            'numero_piece_identite' => 'CNI-55',
            'statut' => 'en_attente',
        ]);

        return DocumentCandidature::create([
            'candidature_id' => $candidature->id,
            'type_document' => 'permis_recto',
            'chemin_fichier' => 'documents_candidats/permis.jpg',
        ]);
    }

    public function test_an_admin_can_validate_a_document(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $document = $this->document();

        $this->post(route('admin.documents.validation', $document), ['verdict' => 'valide'])
            ->assertSessionHas('success');

        $this->assertTrue($document->fresh()->est_valide);
        $this->assertNull($document->fresh()->note_rejet);
    }

    public function test_rejecting_a_document_requires_a_reason(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $document = $this->document();

        $this->post(route('admin.documents.validation', $document), ['verdict' => 'rejete'])
            ->assertSessionHasErrors('note_rejet');

        $this->assertNull($document->fresh()->est_valide);
    }

    public function test_an_admin_can_reject_a_document_with_a_reason_and_it_is_displayed(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $document = $this->document();

        $this->post(route('admin.documents.validation', $document), [
            'verdict' => 'rejete',
            'note_rejet' => 'Photo floue',
        ]);

        $this->assertFalse($document->fresh()->est_valide);

        $this->get(route('admin.candidatures.show', $document->candidature_id))
            ->assertOk()
            ->assertSee('Photo floue');
    }

    public function test_only_admins_can_review_documents(): void
    {
        $document = $this->document();

        $this->post(route('admin.documents.validation', $document), ['verdict' => 'valide'])
            ->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->post(route('admin.documents.validation', $document), ['verdict' => 'valide'])
            ->assertForbidden();

        $this->assertNull($document->fresh()->est_valide);
    }
}