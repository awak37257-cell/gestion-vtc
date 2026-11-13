<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\Chauffeur;
use App\Models\DocumentCandidature;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use LazilyRefreshDatabase;

    private function candidature(): Candidature
    {
        $chauffeur = Chauffeur::create([
            'prenom' => 'Awa',
            'nom' => 'Kone',
            'telephone_whatsapp' => '+2250700000099',
            'ville' => 'Abidjan',
        ]);

        return Candidature::create([
            'chauffeur_id' => $chauffeur->id,
            'numero_permis' => 'PERMIS-99',
            'annees_permis' => 5,
            'numero_piece_identite' => 'CNI-99',
            'statut' => 'en_attente',
        ]);
    }

    public function test_guests_are_redirected_to_login_on_every_admin_route(): void
    {
        $candidature = $this->candidature();

        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.candidatures.index'))->assertRedirect(route('login'));
        $this->get(route('admin.candidatures.show', $candidature))->assertRedirect(route('login'));
        $this->delete(route('admin.candidatures.annulerEntretien', $candidature->id))->assertRedirect(route('login'));
    }

    public function test_non_admin_users_get_a_403_on_admin_routes(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_a_non_admin_cannot_log_in(): void
    {
        $user = User::factory()->create();

        $this->post(route('login.post'), ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_an_admin_can_log_in(): void
    {
        $user = User::factory()->admin()->create();

        $this->post(route('login.post'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_documents_are_only_served_to_admins_from_the_private_disk(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('documents_candidats/permis.jpg', 'contenu');

        $document = DocumentCandidature::create([
            'candidature_id' => $this->candidature()->id,
            'type_document' => 'permis_recto',
            'chemin_fichier' => 'documents_candidats/permis.jpg',
        ]);

        $this->get(route('admin.documents.show', $document))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('admin.documents.show', $document))->assertForbidden();

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.documents.show', $document))->assertOk();
    }

    public function test_uploaded_documents_are_stored_on_the_private_disk(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $this->post(route('candidature.store'), [
            'prenom' => 'Moussa',
            'nom' => 'Traore',
            'telephone_whatsapp' => '+2250700000077',
            'ville' => 'Abidjan',
            'numero_permis' => 'PERMIS-77',
            'annees_permis' => 4,
            'numero_piece_identite' => 'CNI-77',
            'permis_recto' => UploadedFile::fake()->image('recto.jpg'),
            'permis_verso' => UploadedFile::fake()->image('verso.jpg'),
            'piece_identite' => UploadedFile::fake()->image('cni.jpg'),
        ])->assertRedirect(route('candidature.create'));

        $this->assertCount(3, Storage::disk('local')->allFiles('documents_candidats'));
        $this->assertCount(0, Storage::disk('public')->allFiles());
    }
}