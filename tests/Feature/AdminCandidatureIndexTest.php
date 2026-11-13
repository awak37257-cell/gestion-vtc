<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\Chauffeur;
use App\Models\Entretien;
use App\Models\DocumentCandidature;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminCandidatureIndexTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_the_admin_candidature_route_renders_the_dashboard_and_status_filter(): void
    {
        $response = $this->get(route('admin.candidatures.index', ['statut' => 'entretien_programme']));

        $response->assertOk();
        $response->assertViewIs('admin.candidatures.index');
        $response->assertSee('Suivi des candidatures');
        $response->assertSee('Entretien programmé');
        $response->assertSee('value="entretien_programme" selected', false);
        $response->assertSee('Aucune candidature pour le moment');
        $response->assertSee('Navigation recruteur');
        $response->assertSee('src="'.asset('images/logo.png').'"', false);
        $response->assertSee('Tableau de bord');
    }
    protected function setUp(): void
{
    parent::setUp();

    $this->actingAs(User::factory()->admin()->create());
}

    public function test_candidates_with_a_completed_interview_can_be_accepted_or_rejected_from_the_list(): void
    {
        $this->travelTo('2026-11-15 10:31:00');
        $candidature = $this->createCandidature('entretien_programme');
        DocumentCandidature::create([
    'candidature_id' => $candidature->id,
    'type_document' => 'permis_recto',
    'chemin_fichier' => 'documents_candidats/permis.jpg',
    'est_valide' => true,
]);
        Entretien::create([
            'candidature_id' => $candidature->id,
            'date_prevue' => '2026-11-15 10:30:00',
            'lieu_ou_type' => 'Appel vidéo',
            'notes' => null,
            'statut_entretien' => 'programme',
        ]);

        $response = $this->get(route('admin.candidatures.index'));

        $response->assertOk();
        $response->assertSee('Accepter');
        $response->assertSee('Refuser');
        $response->assertDontSee('Examiner');
        $response->assertDontSee('Voir le rendez-vous');

        $decisionResponse = $this->from(route('admin.candidatures.index'))
            ->post(route('admin.candidatures.decisionEntretien', $candidature), [
                'decision' => 'valide',
            ]);

        $decisionResponse->assertRedirect(route('admin.candidatures.index'));
        $this->assertDatabaseHas('candidatures', [
            'id' => $candidature->id,
            'statut' => 'accepte',
        ]);
    }

    public function test_a_future_interview_shows_no_review_or_decision_actions(): void
    {
        $this->travelTo('2026-11-15 10:29:00');
        $candidature = $this->createCandidature('entretien_programme');
        Entretien::create([
            'candidature_id' => $candidature->id,
            'date_prevue' => '2026-11-15 10:30:00',
            'lieu_ou_type' => 'Appel vidéo',
            'notes' => null,
            'statut_entretien' => 'programme',
        ]);

        $response = $this->get(route('admin.candidatures.index'));

        $response->assertOk();
        $response->assertSee('Voir le rendez-vous');
        $response->assertDontSee('Examiner');
        $response->assertDontSee('Accepter');
        $response->assertDontSee('Refuser');
    }

    public function test_all_applications_remain_visible_in_the_main_list_and_status_filters(): void
    {
        $this->createCandidature('en_cours_examen', 'Awa', '+2250700000020');
        $this->createCandidature('accepte', 'Moussa', '+2250700000021');
        $this->createCandidature('rejete', 'Fatou', '+2250700000022');

        $mainList = $this->get(route('admin.candidatures.index'));

        $mainList->assertOk();
        $mainList->assertSee('Awa Kone');
        $mainList->assertSee('Moussa Kone');
        $mainList->assertSee('Fatou Kone');
        $mainList->assertSee('3</strong> dossier(s) correspondant(s)', false);

        $acceptedList = $this->get(route('admin.candidatures.index', ['statut' => 'accepte']));

        $acceptedList->assertOk();
        $acceptedList->assertSee('Moussa Kone');
        $acceptedList->assertSee('Voir le dossier');
        $acceptedList->assertDontSee('Fatou Kone');

        $rejectedList = $this->get(route('admin.candidatures.index', ['statut' => 'rejete']));

        $rejectedList->assertOk();
        $rejectedList->assertSee('Fatou Kone');
        $rejectedList->assertDontSee('Moussa Kone');
    }

    private function createCandidature(
        string $statut,
        string $prenom = 'Awa',
        string $telephone = '+2250700000020',
    ): Candidature {
        $chauffeur = Chauffeur::create([
            'prenom' => $prenom,
            'nom' => 'Kone',
            'telephone_whatsapp' => $telephone,
            'ville' => 'Abidjan',
        ]);

        return Candidature::create([
            'chauffeur_id' => $chauffeur->id,
            'numero_permis' => 'PERMIS-20',
            'annees_permis' => 5,
            'numero_carte_vtc' => null,
            'numero_piece_identite' => 'CNI-20',
            'statut' => $statut,
        ]);
    }
}
