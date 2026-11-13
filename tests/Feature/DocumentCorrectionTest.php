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

class DocumentCorrectionTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** Dossier avec 3 documents : recto rejeté, verso valide, pièce d'identité rejetée. */
    private function dossier(string $statut = 'en_cours_examen', string $telephone = '+225 07 00 00 00 66'): array
    {
        $chauffeur = Chauffeur::create([
            'prenom' => 'Awa',
            'nom' => 'Kone',
            'telephone_whatsapp' => $telephone,
            'ville' => 'Abidjan',
        ]);

        $candidature = Candidature::create([
            'chauffeur_id' => $chauffeur->id,
            'numero_permis' => 'PERMIS-66',
            'annees_permis' => 5,
            'numero_piece_identite' => 'CNI-66',
            'statut' => $statut,
        ]);

        $document = fn (string $type, ?bool $valide, ?string $motif = null) => DocumentCandidature::create([
            'candidature_id' => $candidature->id,
            'type_document' => $type,
            'chemin_fichier' => 'documents_candidats/'.$type.'-ancien.jpg',
            'est_valide' => $valide,
            'note_rejet' => $motif,
        ]);

        return [
            $candidature,
            $document('permis_recto', false, 'Photo floue'),
            $document('permis_verso', true),
            $document('piece_identite', false, 'Document expiré'),
        ];
    }

    private function lienActif(Candidature $candidature, string $jeton = 'jeton-de-test-123'): string
    {
        $candidature->update([
            'jeton_correction' => hash('sha256', $jeton),
            'jeton_correction_expire_le' => now()->addDays(7),
        ]);

        return $jeton;
    }

    private function image(string $nom = 'photo.jpg'): UploadedFile
    {
        return UploadedFile::fake()->image($nom);
    }

    // ---------- Côté admin : demande par WhatsApp ----------

    public function test_the_admin_gets_a_whatsapp_message_with_a_personal_link(): void
    {
        $this->travelTo('2026-11-14 09:00:00');
        $this->actingAs(User::factory()->admin()->create());
        [$candidature] = $this->dossier();

        $reponse = $this->post(route('admin.candidatures.demandeCorrection', $candidature));

        $reponse->assertRedirectContains('https://wa.me/2250700000066?text=');

        parse_str(parse_url($reponse->headers->get('Location'), PHP_URL_QUERY), $requete);
        $message = $requete['text'];

        $this->assertStringContainsString('Permis de conduire · recto (Photo floue)', $message);
        $this->assertStringContainsString('Pièce d’identité (Document expiré)', $message);
        $this->assertStringNotContainsString('verso', $message);

        preg_match('#/documents/corriger/([A-Za-z0-9]+)#', $message, $trouve);
        $this->assertNotEmpty($trouve, 'Le lien personnel est dans le message');

        $candidature = $candidature->fresh();
        $this->assertSame(hash('sha256', $trouve[1]), $candidature->jeton_correction);
        $this->assertNotSame($trouve[1], $candidature->jeton_correction);
        $this->assertSame('2026-11-21 09:00:00', $candidature->jeton_correction_expire_le->toDateTimeString());
    }

    public function test_no_link_is_created_when_no_document_is_rejected(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        [$candidature, $recto, , $piece] = $this->dossier();
        $recto->update(['est_valide' => true]);
        $piece->update(['est_valide' => null]);

        $this->post(route('admin.candidatures.demandeCorrection', $candidature))
            ->assertSessionHasErrors('correction');

        $this->assertNull($candidature->fresh()->jeton_correction);
    }

    public function test_no_link_is_created_for_a_refused_candidate(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        [$candidature] = $this->dossier('rejete');

        $this->post(route('admin.candidatures.demandeCorrection', $candidature))
            ->assertSessionHasErrors('correction');

        $this->assertNull($candidature->fresh()->jeton_correction);
    }

    public function test_only_admins_can_request_a_correction(): void
    {
        [$candidature] = $this->dossier();

        $this->post(route('admin.candidatures.demandeCorrection', $candidature))
            ->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->post(route('admin.candidatures.demandeCorrection', $candidature))
            ->assertForbidden();

        $this->assertNull($candidature->fresh()->jeton_correction);
    }

    public function test_the_dossier_page_offers_the_request_button_and_flags_resubmitted_documents(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        [$candidature, $recto] = $this->dossier();

        $this->get(route('admin.candidatures.show', $candidature))
            ->assertOk()
            ->assertSee('2 document(s) rejeté(s)')
            ->assertSee('Demander sur WhatsApp');

        $recto->update(['est_valide' => null, 'note_rejet' => null, 'renvoye_le' => '2026-11-14 10:15:00']);

        $this->get(route('admin.candidatures.show', $candidature))
            ->assertOk()
            ->assertSee('1 document(s) rejeté(s)')
            ->assertSee('Renvoyé le 14/11/2026 à 10:15');
    }

    // ---------- Côté candidat : page publique ----------

    public function test_the_page_lists_only_the_rejected_documents_with_their_reasons(): void
    {
        [$candidature] = $this->dossier();
        $jeton = $this->lienActif($candidature);

        $this->get(route('documents.correction', $jeton))
            ->assertOk()
            ->assertSee('Bonjour Awa')
            ->assertSee('Permis de conduire · recto')
            ->assertSee('Photo floue')
            ->assertSee('Pièce d’identité')
            ->assertSee('Document expiré')
            ->assertDontSee('Permis de conduire · verso');
    }

    public function test_an_unknown_link_is_refused(): void
    {
        $this->get(route('documents.correction', 'jeton-inconnu'))
            ->assertNotFound()
            ->assertSee('Ce lien n\'est plus valide', false);
    }

    public function test_an_expired_link_is_refused(): void
    {
        [$candidature] = $this->dossier();
        $jeton = $this->lienActif($candidature);
        $candidature->update(['jeton_correction_expire_le' => now()->subMinute()]);

        $this->get(route('documents.correction', $jeton))->assertNotFound();

        $this->post(route('documents.correction.store', $jeton), ['documents' => []])->assertNotFound();
    }

    public function test_replacing_all_rejected_documents_resets_them_and_closes_the_link(): void
    {
        Storage::fake('local');
        $this->travelTo('2026-11-14 11:00:00');
        [$candidature, $recto, $verso, $piece] = $this->dossier();
        Storage::disk('local')->put($recto->chemin_fichier, 'ancien recto');
        Storage::disk('local')->put($piece->chemin_fichier, 'ancienne piece');
        $ancienRecto = $recto->chemin_fichier;
        $jeton = $this->lienActif($candidature);

        $this->post(route('documents.correction.store', $jeton), [
            'documents' => [$recto->id => $this->image('recto.jpg'), $piece->id => $this->image('cni.jpg')],
        ])->assertRedirect(route('documents.correction.merci'));

        $recto = $recto->fresh();
        $this->assertNull($recto->est_valide);
        $this->assertNull($recto->note_rejet);
        $this->assertSame('2026-11-14 11:00:00', $recto->renvoye_le->toDateTimeString());
        $this->assertNotSame($ancienRecto, $recto->chemin_fichier);
        Storage::disk('local')->assertExists($recto->chemin_fichier);
        Storage::disk('local')->assertMissing($ancienRecto);

        $this->assertNull($piece->fresh()->est_valide);
        $this->assertTrue($verso->fresh()->est_valide);

        $candidature = $candidature->fresh();
        $this->assertNull($candidature->jeton_correction);
        $this->assertNull($candidature->jeton_correction_expire_le);

        $this->get(route('documents.correction', $jeton))->assertNotFound();
    }

    public function test_a_partial_upload_keeps_the_link_for_the_remaining_documents(): void
    {
        Storage::fake('local');
        [$candidature, $recto, , $piece] = $this->dossier();
        $jeton = $this->lienActif($candidature);

        $this->post(route('documents.correction.store', $jeton), [
            'documents' => [$recto->id => $this->image()],
        ])->assertRedirect(route('documents.correction', $jeton))
            ->assertSessionHas('success');

        $this->assertNull($recto->fresh()->est_valide);
        $this->assertFalse($piece->fresh()->est_valide);
        $this->assertNotNull($candidature->fresh()->jeton_correction);

        $this->get(route('documents.correction', $jeton))
            ->assertOk()
            ->assertSee('Document expiré')
            ->assertDontSee('Photo floue');
    }

    public function test_a_document_that_is_not_rejected_cannot_be_replaced(): void
    {
        Storage::fake('local');
        [$candidature, , $verso] = $this->dossier();
        $jeton = $this->lienActif($candidature);
        $ancienChemin = $verso->chemin_fichier;

        $this->post(route('documents.correction.store', $jeton), [
            'documents' => [$verso->id => $this->image()],
        ])->assertSessionHasErrors('documents');

        $verso = $verso->fresh();
        $this->assertTrue($verso->est_valide);
        $this->assertSame($ancienChemin, $verso->chemin_fichier);
    }

    public function test_a_document_of_another_candidate_cannot_be_replaced(): void
    {
        Storage::fake('local');
        [$candidature] = $this->dossier();
        [, $recto2] = $this->dossier('en_cours_examen', '+225 07 00 00 00 77');
        $jeton = $this->lienActif($candidature);
        $ancienChemin = $recto2->chemin_fichier;

        $this->post(route('documents.correction.store', $jeton), [
            'documents' => [$recto2->id => $this->image()],
        ])->assertSessionHasErrors('documents');

        $this->assertSame($ancienChemin, $recto2->fresh()->chemin_fichier);
    }

    public function test_only_images_and_pdf_files_under_2_mb_are_accepted(): void
    {
        Storage::fake('local');
        [$candidature, $recto] = $this->dossier();
        $jeton = $this->lienActif($candidature);

        $this->post(route('documents.correction.store', $jeton), [
            'documents' => [$recto->id => UploadedFile::fake()->create('virus.exe', 10, 'application/octet-stream')],
        ])->assertSessionHasErrors('documents.'.$recto->id);

        $this->post(route('documents.correction.store', $jeton), [
            'documents' => [$recto->id => UploadedFile::fake()->create('gros.pdf', 3000, 'application/pdf')],
        ])->assertSessionHasErrors('documents.'.$recto->id);

        $this->assertFalse($recto->fresh()->est_valide);

        $this->post(route('documents.correction.store', $jeton), [
            'documents' => [$recto->id => UploadedFile::fake()->create('permis.pdf', 500, 'application/pdf')],
        ])->assertSessionHasNoErrors();

        $this->assertNull($recto->fresh()->est_valide);
    }

    public function test_sending_nothing_asks_to_choose_a_document(): void
    {
        [$candidature] = $this->dossier();
        $jeton = $this->lienActif($candidature);

        $this->post(route('documents.correction.store', $jeton))
            ->assertSessionHasErrors(['documents' => 'Veuillez choisir au moins un document à renvoyer.']);
    }

    public function test_the_thank_you_page_is_public(): void
    {
        $this->get(route('documents.correction.merci'))
            ->assertOk()
            ->assertSee('c\'est bien reçu', false);
    }
}