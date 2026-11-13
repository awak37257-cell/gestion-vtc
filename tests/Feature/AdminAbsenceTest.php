<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\Chauffeur;
use App\Models\Entretien;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminAbsenceTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->admin()->create());
    }

    private function candidatureAvecEntretien(string $statutEntretien = 'programme', string $statut = 'entretien_programme'): Candidature
    {
        $chauffeur = Chauffeur::create([
            'prenom' => 'Awa',
            'nom' => 'Kone',
            'telephone_whatsapp' => '+2250700000044',
            'ville' => 'Abidjan',
        ]);

        $candidature = Candidature::create([
            'chauffeur_id' => $chauffeur->id,
            'numero_permis' => 'PERMIS-44',
            'annees_permis' => 5,
            'numero_piece_identite' => 'CNI-44',
            'statut' => $statut,
        ]);

        Entretien::create([
            'candidature_id' => $candidature->id,
            'date_prevue' => '2026-11-15 10:30:00',
            'lieu_ou_type' => 'Appel vidéo',
            'statut_entretien' => $statutEntretien,
        ]);

        return $candidature;
    }

    public function test_a_candidate_cannot_be_marked_absent_before_the_interview(): void
    {
        $this->travelTo('2026-11-15 10:00:00');
        $candidature = $this->candidatureAvecEntretien();

        $this->post(route('admin.candidatures.absent', $candidature))
            ->assertSessionHasErrors('absent');

        $this->assertSame('entretien_programme', $candidature->fresh()->statut);
        $this->assertSame('programme', $candidature->entretiens()->first()->statut_entretien);
    }

    public function test_a_candidate_can_be_marked_absent_after_the_interview_time(): void
    {
        $this->travelTo('2026-11-15 10:31:00');
        $candidature = $this->candidatureAvecEntretien();

        $this->post(route('admin.candidatures.absent', $candidature))
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this->assertSame('en_cours_examen', $candidature->fresh()->statut);
        $this->assertSame('absent', $candidature->entretiens()->first()->statut_entretien);
    }

    public function test_marking_absent_requires_a_scheduled_interview(): void
    {
        $this->travelTo('2026-11-15 10:31:00');
        $candidature = $this->candidatureAvecEntretien('programme', 'en_cours_examen');

        $this->post(route('admin.candidatures.absent', $candidature))
            ->assertSessionHasErrors('absent');

        $this->assertSame('en_cours_examen', $candidature->fresh()->statut);
    }

    public function test_the_list_offers_the_absent_button_only_once_the_interview_time_has_passed(): void
    {
        $candidature = $this->candidatureAvecEntretien();
        $lien = e(route('admin.candidatures.absent', $candidature));

        $this->travelTo('2026-11-15 10:00:00');
        $this->get(route('admin.candidatures.index'))->assertOk()->assertDontSee($lien, false);

        $this->travelTo('2026-11-15 10:31:00');
        $this->get(route('admin.candidatures.index'))->assertOk()->assertSee($lien, false);
    }

    public function test_an_absent_candidate_is_flagged_in_the_list_with_reschedule_and_refuse_actions(): void
    {
        $this->travelTo('2026-11-15 10:31:00');
        $candidature = $this->candidatureAvecEntretien('absent', 'en_cours_examen');

        $this->get(route('admin.candidatures.index'))
            ->assertOk()
            ->assertSee('Absent à l’entretien du 15/11/2026')
            ->assertSee('Reprogrammer')
            ->assertSee(e(route('admin.candidatures.refusAbsence', $candidature)), false)
            ->assertDontSee('Accepter');
    }

    public function test_the_dossier_page_of_an_absent_candidate_offers_a_new_interview(): void
    {
        $this->travelTo('2026-11-15 10:31:00');
        $candidature = $this->candidatureAvecEntretien('absent', 'en_cours_examen');

        $this->get(route('admin.candidatures.show', $candidature))
            ->assertOk()
            ->assertSee('était absent à l’entretien du 15/11/2026 à 10:30')
            ->assertSee('Gestion Entretien')
            ->assertSee('name="date_prevue"', false)
            ->assertDontSee('Ouvrir WhatsApp avec l’invitation');
    }

    public function test_rescheduling_after_an_absence_keeps_the_history(): void
    {
        $this->travelTo('2026-11-15 10:31:00');
        $candidature = $this->candidatureAvecEntretien('absent', 'en_cours_examen');

        $this->post(route('admin.candidatures.entretien', $candidature), [
            'date_prevue' => '2026-11-20 14:00:00',
            'lieu_ou_type' => 'Appel vidéo',
        ])->assertRedirectContains('wa.me');

        $entretiens = $candidature->entretiens()->orderBy('id')->get();

        $this->assertCount(2, $entretiens);
        $this->assertSame('absent', $entretiens[0]->statut_entretien);
        $this->assertSame('programme', $entretiens[1]->statut_entretien);
        $this->assertSame('entretien_programme', $candidature->fresh()->statut);
    }

    public function test_an_absent_candidate_can_be_refused_with_a_default_reason(): void
    {
        $this->travelTo('2026-11-15 10:31:00');
        $candidature = $this->candidatureAvecEntretien('absent', 'en_cours_examen');

        $this->post(route('admin.candidatures.refusAbsence', $candidature))
            ->assertSessionHas('success');

        $candidature = $candidature->fresh();
        $this->assertSame('rejete', $candidature->statut);
        $this->assertSame('Absence à l’entretien programmé.', $candidature->motif_rejet);
        $this->assertSame('absent', $candidature->entretiens()->first()->statut_entretien);
    }

    public function test_an_absent_candidate_can_be_refused_with_a_custom_reason(): void
    {
        $this->travelTo('2026-11-15 10:31:00');
        $candidature = $this->candidatureAvecEntretien('absent', 'en_cours_examen');

        $this->post(route('admin.candidatures.refusAbsence', $candidature), ['motif_rejet' => 'Injoignable'])
            ->assertSessionHasNoErrors();

        $this->assertSame('Injoignable', $candidature->fresh()->motif_rejet);
    }

    public function test_only_an_absent_candidate_can_be_refused_through_this_action(): void
    {
        $this->travelTo('2026-11-15 10:31:00');
        $candidature = $this->candidatureAvecEntretien('programme', 'entretien_programme');

        $this->post(route('admin.candidatures.refusAbsence', $candidature))
            ->assertSessionHasErrors('absent');

        $this->assertSame('entretien_programme', $candidature->fresh()->statut);
    }

    public function test_only_admins_can_use_the_absence_actions(): void
    {
        $this->travelTo('2026-11-15 10:31:00');
        $candidature = $this->candidatureAvecEntretien();

        auth()->logout();
        $this->post(route('admin.candidatures.absent', $candidature))->assertRedirect(route('login'));
        $this->post(route('admin.candidatures.refusAbsence', $candidature))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create());
        $this->post(route('admin.candidatures.absent', $candidature))->assertForbidden();
        $this->post(route('admin.candidatures.refusAbsence', $candidature))->assertForbidden();

        $this->assertSame('entretien_programme', $candidature->fresh()->statut);
    }
}