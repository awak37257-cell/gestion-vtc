<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\Chauffeur;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminCandidatureSearchTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->admin()->create());
    }

    private function candidature(
        string $prenom,
        string $nom,
        string $telephone,
        string $ville,
        string $createdAt = '2026-11-10 09:00:00',
        string $statut = 'en_attente',
    ): Candidature {
        $chauffeur = Chauffeur::create([
            'prenom' => $prenom,
            'nom' => $nom,
            'telephone_whatsapp' => $telephone,
            'ville' => $ville,
        ]);

        return Candidature::create([
            'chauffeur_id' => $chauffeur->id,
            'numero_permis' => 'PERMIS-'.substr($telephone, -4),
            'annees_permis' => 5,
            'numero_piece_identite' => 'CNI-'.substr($telephone, -4),
            'statut' => $statut,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }

    private function jeuDeDonnees(): void
    {
        $this->candidature('Moussa', 'Traore', '+2250700001111', 'Abidjan');
        $this->candidature('Fatou', 'Diallo', '+2250700002222', 'Bouaké');
        $this->candidature('Awa', 'Kone', '+2250700003333', 'Yamoussoukro');
    }

    public function test_search_by_first_name(): void
    {
        $this->jeuDeDonnees();

        $this->get(route('admin.candidatures.index', ['q' => 'moussa']))
            ->assertOk()
            ->assertSee('Moussa Traore')
            ->assertDontSee('Fatou Diallo')
            ->assertDontSee('Awa Kone');
    }

    public function test_search_by_phone_number(): void
    {
        $this->jeuDeDonnees();

        $this->get(route('admin.candidatures.index', ['q' => '2222']))
            ->assertSee('Fatou Diallo')
            ->assertDontSee('Moussa Traore');
    }

    public function test_search_by_city(): void
    {
        $this->jeuDeDonnees();

        $this->get(route('admin.candidatures.index', ['q' => 'Bouaké']))
            ->assertSee('Fatou Diallo')
            ->assertDontSee('Awa Kone');
    }

    public function test_search_with_first_and_last_name_together(): void
    {
        $this->jeuDeDonnees();

        $this->get(route('admin.candidatures.index', ['q' => 'Fatou Diallo']))
            ->assertSee('Fatou Diallo')
            ->assertDontSee('Moussa Traore');

        // Chaque mot doit correspondre : prénom d'un candidat + nom d'un autre = aucun résultat
        $this->get(route('admin.candidatures.index', ['q' => 'Fatou Traore']))
            ->assertDontSee('Fatou Diallo')
            ->assertDontSee('Moussa Traore');
    }

    public function test_a_wildcard_character_is_searched_literally(): void
    {
        $this->jeuDeDonnees();

        $this->get(route('admin.candidatures.index', ['q' => '%']))
            ->assertDontSee('Moussa Traore')
            ->assertDontSee('Fatou Diallo')
            ->assertDontSee('Awa Kone');
    }

    public function test_search_can_be_combined_with_the_status_filter(): void
    {
        $this->candidature('Moussa', 'Traore', '+2250700001111', 'Abidjan', statut: 'accepte');
        $this->candidature('Moussa', 'Bamba', '+2250700004444', 'Abidjan', statut: 'rejete');

        $this->get(route('admin.candidatures.index', ['q' => 'Moussa', 'statut' => 'accepte']))
            ->assertSee('Moussa Traore')
            ->assertDontSee('Moussa Bamba');
    }

    public function test_filter_by_date_range(): void
    {
        $this->candidature('Moussa', 'Traore', '+2250700001111', 'Abidjan', '2026-11-05 10:00:00');
        $this->candidature('Fatou', 'Diallo', '+2250700002222', 'Abidjan', '2026-11-10 10:00:00');
        $this->candidature('Awa', 'Kone', '+2250700003333', 'Abidjan', '2026-11-12 10:00:00');

        $this->get(route('admin.candidatures.index', ['du' => '2026-11-10', 'au' => '2026-11-10']))
            ->assertSee('Fatou Diallo')
            ->assertDontSee('Moussa Traore')
            ->assertDontSee('Awa Kone');

        $this->get(route('admin.candidatures.index', ['du' => '2026-11-10']))
            ->assertSee('Fatou Diallo')
            ->assertSee('Awa Kone')
            ->assertDontSee('Moussa Traore');

        $this->get(route('admin.candidatures.index', ['au' => '2026-11-05']))
            ->assertSee('Moussa Traore')
            ->assertDontSee('Fatou Diallo');
    }

    public function test_an_invalid_date_is_ignored_instead_of_breaking_the_page(): void
    {
        $this->jeuDeDonnees();

        $this->get(route('admin.candidatures.index', ['du' => 'pas-une-date']))
            ->assertOk()
            ->assertSee('Moussa Traore')
            ->assertSee('Awa Kone');
    }

    public function test_pagination_links_keep_the_search_and_filters(): void
    {
        foreach (range(1, 12) as $i) {
            $this->candidature('Moussa', 'Nom'.$i, '+22507000'.str_pad((string) $i, 5, '0', STR_PAD_LEFT), 'Abidjan');
        }

        $reponse = $this->get(route('admin.candidatures.index', ['q' => 'Moussa']));

        $reponse->assertOk();
        $reponse->assertSee('q=Moussa', false);
        $reponse->assertSee('page=2', false);
    }
}