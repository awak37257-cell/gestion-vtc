<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CandidatureIdentityNumberTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_public_pages_display_the_company_logo(): void
    {
        foreach (['welcome', 'candidature.create', 'login'] as $routeName) {
            $response = $this->get(route($routeName));

            $response->assertOk();
            $response->assertSee('src="'.asset('images/logo.png').'"', false);
        }
    }

    public function test_a_candidate_can_apply_with_an_identity_number(): void
    {
        Storage::fake('public');

        $response = $this->post(route('candidature.store'), [
            'prenom' => 'Aminata',
            'nom' => 'Kone',
            'telephone_whatsapp' => '+2250700000000',
            'ville' => 'Abidjan',
            'numero_permis' => 'PERMIS-123',
            'annees_permis' => 5,
            'numero_piece_identite' => 'CNI-123',
            'permis_recto' => UploadedFile::fake()->image('permis-recto.jpg'),
            'permis_verso' => UploadedFile::fake()->image('permis-verso.jpg'),
            'piece_identite' => UploadedFile::fake()->image('identite.jpg'),
        ]);

        $response->assertRedirect(route('candidature.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('candidatures', [
            'numero_permis' => 'PERMIS-123',
            'numero_piece_identite' => 'CNI-123',
        ]);
        $this->assertDatabaseCount('documents_candidature', 3);
    }

    public function test_identity_number_is_required_for_a_candidate_application(): void
    {
        Storage::fake('public');

        $response = $this->post(route('candidature.store'), [
            'prenom' => 'Aminata',
            'nom' => 'Kone',
            'telephone_whatsapp' => '+2250700000002',
            'ville' => 'Abidjan',
            'numero_permis' => 'PERMIS-456',
            'annees_permis' => 5,
            'permis_recto' => UploadedFile::fake()->image('permis-recto.jpg'),
            'permis_verso' => UploadedFile::fake()->image('permis-verso.jpg'),
            'piece_identite' => UploadedFile::fake()->image('identite.jpg'),
        ]);

        $response->assertSessionHasErrors('numero_piece_identite');
        $this->assertDatabaseMissing('chauffeurs', [
            'telephone_whatsapp' => '+2250700000002',
        ]);
    }
}
