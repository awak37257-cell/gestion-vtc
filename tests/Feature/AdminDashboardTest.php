<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\Chauffeur;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_the_admin_has_a_dashboard_with_status_counts_and_recent_applications(): void
    {
        $pendingDriver = Chauffeur::create([
            'prenom' => 'Awa',
            'nom' => 'Kone',
            'telephone_whatsapp' => '+2250700000010',
            'ville' => 'Abidjan',
        ]);
        Candidature::create([
            'chauffeur_id' => $pendingDriver->id,
            'numero_permis' => 'PERMIS-10',
            'annees_permis' => 5,
            'numero_carte_vtc' => null,
            'numero_piece_identite' => 'CNI-10',
            'statut' => 'en_attente',
        ]);

        $acceptedDriver = Chauffeur::create([
            'prenom' => 'Moussa',
            'nom' => 'Traore',
            'telephone_whatsapp' => '+2250700000011',
            'ville' => 'Abidjan',
        ]);
        Candidature::create([
            'chauffeur_id' => $acceptedDriver->id,
            'numero_permis' => 'PERMIS-11',
            'annees_permis' => 8,
            'numero_carte_vtc' => null,
            'numero_piece_identite' => 'CNI-11',
            'statut' => 'accepte',
        ]);

        $response = $this->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas('totalCandidatures', 2);
        $response->assertViewHas('statusCounts', function ($statusCounts) {
            return $statusCounts->get('en_attente') === 1
                && $statusCounts->get('accepte') === 1;
        });
        $response->assertSee('Bonjour, bienvenue sur votre dashboard.');
        $response->assertSee('src="'.asset('images/logo.png').'"', false);
        $response->assertSee('Awa Kone');
        $response->assertSee('Moussa Traore');
        $response->assertSee(route('admin.candidatures.index'));
    }
    protected function setUp(): void
{
    parent::setUp();

    $this->actingAs(User::factory()->admin()->create());
}
}
