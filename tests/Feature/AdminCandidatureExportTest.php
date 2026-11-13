<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\Chauffeur;
use App\Models\DocumentCandidature;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminCandidatureExportTest extends TestCase
{
    use LazilyRefreshDatabase;

    private function candidature(
        string $prenom,
        string $nom,
        string $telephone,
        string $statut = 'accepte',
        ?bool $documentValide = null,
        ?string $motifRejet = null,
    ): Candidature {
        $chauffeur = Chauffeur::create([
            'prenom' => $prenom,
            'nom' => $nom,
            'telephone_whatsapp' => $telephone,
            'ville' => 'Abidjan',
        ]);

        $candidature = Candidature::create([
            'chauffeur_id' => $chauffeur->id,
            'numero_permis' => 'PERMIS-'.substr($telephone, -4),
            'annees_permis' => 5,
            'numero_piece_identite' => 'CNI-'.substr($telephone, -4),
            'statut' => $statut,
            'motif_rejet' => $motifRejet,
        ]);

        if ($documentValide !== null) {
            DocumentCandidature::create([
                'candidature_id' => $candidature->id,
                'type_document' => 'permis_recto',
                'chemin_fichier' => 'documents_candidats/permis.jpg',
                'est_valide' => $documentValide,
            ]);
        }

        return $candidature;
    }

    /** Lit le CSV téléchargé : retourne la liste des lignes (tableaux de cellules). */
    private function lignes($reponse): array
    {
        $contenu = $reponse->streamedContent();
        $contenu = preg_replace('/^\xEF\xBB\xBF/', '', $contenu);

        return array_map(
            fn (string $ligne) => str_getcsv($ligne, ';', '"', ''),
            array_filter(explode("\n", $contenu), fn ($l) => trim($l) !== '')
        );
    }

    private function admin(): void
    {
        $this->actingAs(User::factory()->admin()->create());
    }

    public function test_only_admins_can_export(): void
    {
        $this->get(route('admin.candidatures.export'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('admin.candidatures.export'))
            ->assertForbidden();
    }

    public function test_the_export_is_a_csv_download_readable_by_excel(): void
    {
        $this->admin();
        $this->travelTo('2026-11-12 10:00:00');
        $this->candidature('Moussa', 'Traore', '+2250700001111');

        $reponse = $this->get(route('admin.candidatures.export'));

        $reponse->assertOk()->assertDownload('candidatures-2026-11-12.csv');
        $this->assertStringStartsWith("\xEF\xBB\xBF", $reponse->streamedContent());

        $lignes = $this->lignes($reponse);
        $this->assertSame('Prénom', $lignes[0][0]);
        $this->assertSame('Statut', $lignes[0][7]);
        $this->assertSame('Moussa', $lignes[1][0]);
        $this->assertSame('Accepté', $lignes[1][7]);
    }

    public function test_the_export_follows_the_status_filter(): void
    {
        $this->admin();
        $this->candidature('Moussa', 'Traore', '+2250700001111', 'accepte');
        $this->candidature('Fatou', 'Diallo', '+2250700002222', 'rejete');

        $lignes = $this->lignes($this->get(route('admin.candidatures.export', ['statut' => 'accepte'])));

        $this->assertCount(2, $lignes); // en-tête + 1 candidat
        $this->assertSame('Moussa', $lignes[1][0]);
    }

    public function test_the_export_follows_the_search(): void
    {
        $this->admin();
        $this->candidature('Moussa', 'Traore', '+2250700001111');
        $this->candidature('Fatou', 'Diallo', '+2250700002222');

        $lignes = $this->lignes($this->get(route('admin.candidatures.export', ['q' => 'Diallo'])));

        $this->assertCount(2, $lignes);
        $this->assertSame('Fatou', $lignes[1][0]);
    }

    public function test_the_export_contains_all_rows_not_only_the_first_page(): void
    {
        $this->admin();
        foreach (range(1, 13) as $i) {
            $this->candidature('Moussa', 'Nom'.$i, '+22507'.str_pad((string) $i, 8, '0', STR_PAD_LEFT));
        }

        $lignes = $this->lignes($this->get(route('admin.candidatures.export')));

        $this->assertCount(14, $lignes); // en-tête + 13 candidats (la liste à l'écran n'en montre que 10)
    }

    public function test_phone_numbers_are_kept_as_text_for_excel(): void
    {
        $this->admin();
        $this->candidature('Moussa', 'Traore', '+225 07 00 00 11 11');

        $lignes = $this->lignes($this->get(route('admin.candidatures.export')));

        $this->assertSame('="+2250700001111"', $lignes[1][2]);
    }

    public function test_text_entered_by_candidates_cannot_run_as_an_excel_formula(): void
    {
        $this->admin();
        $this->candidature('=HYPERLINK("http://exemple.test")', 'Traore', '+2250700001111');

        $lignes = $this->lignes($this->get(route('admin.candidatures.export')));

        $this->assertSame('\'=HYPERLINK("http://exemple.test")', $lignes[1][0]);
    }

    public function test_documents_validated_and_rejection_reason_columns(): void
    {
        $this->admin();
        $this->candidature('Moussa', 'Traore', '+2250700001111', 'accepte', true);
        $this->candidature('Fatou', 'Diallo', '+2250700002222', 'rejete', false, 'Permis expiré');

        $lignes = collect($this->lignes($this->get(route('admin.candidatures.export'))))
            ->keyBy(fn ($ligne) => $ligne[0]);

        $this->assertSame('Oui', $lignes['Moussa'][9]);
        $this->assertSame('', $lignes['Moussa'][10]);
        $this->assertSame('Non', $lignes['Fatou'][9]);
        $this->assertSame('Permis expiré', $lignes['Fatou'][10]);
    }

    public function test_the_list_offers_an_export_button_that_keeps_the_filters(): void
    {
        $this->admin();
        $this->candidature('Moussa', 'Traore', '+2250700001111');

        $this->get(route('admin.candidatures.index', ['statut' => 'accepte', 'q' => 'Moussa']))
            ->assertOk()
            ->assertSee('Exporter')
            ->assertSee(e(route('admin.candidatures.export', ['statut' => 'accepte', 'q' => 'Moussa'])), false);
    }
}