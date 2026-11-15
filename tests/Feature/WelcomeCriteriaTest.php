<?php

namespace Tests\Feature;

use Tests\TestCase;

class WelcomeCriteriaTest extends TestCase
{
    public function test_the_hero_link_points_to_the_criteria_section(): void
    {
        $this->get(route('welcome'))
            ->assertOk()
            ->assertSee('href="#parcours"', false)
            ->assertSee('id="parcours"', false);
    }

    public function test_the_three_types_of_criteria_are_displayed(): void
    {
        $this->get(route('welcome'))
            ->assertOk()
            ->assertSee('Quelles sont les conditions obligatoires ?')
            ->assertSee('Quelles compétences professionnelles et techniques faut-il avoir ?')
            ->assertSee('Quelles qualités humaines et comportementales attendez-vous ?')
            ->assertSee('6 critères')
            ->assertSee('7 critères')
            ->assertSee('8 critères');
    }

    public function test_each_type_lists_its_criteria(): void
    {
        $this->get(route('welcome'))
            ->assertSee('permis de conduire de catégorie B en cours de validité')
            ->assertSee('Justifier d’au moins 3 ans d’expérience pratique')
            ->assertSee('Maîtriser parfaitement le code de la route ivoirien')
            ->assertSee('Maîtriser l’utilisation du GPS')
            ->assertSee('Intégrité dans la gestion des recettes')
            ->assertSee('Hygiène personnelle et tenue professionnelle soignée');
    }

    public function test_the_quick_questions_are_displayed(): void
    {
        $this->get(route('welcome'))
            ->assertSee('Quel permis faut-il avoir ?')
            ->assertSee('Combien d’années d’expérience faut-il ?')
            ->assertSee('Le CACR est-il obligatoire ?')
            ->assertSee('Dois-je fournir un casier judiciaire ?');
    }

    public function test_the_first_type_is_open_by_default_and_there_are_seven_questions(): void
    {
        $html = $this->get(route('welcome'))->getContent();

        $this->assertSame(7, substr_count($html, '<details class="faq-item"'));
        $this->assertMatchesRegularExpression('/<details class="faq-item"\s+open/', $html);
        $this->assertSame(1, preg_match_all('/<details class="faq-item"\s+open/', $html));
    }

    public function test_the_main_element_is_closed_before_the_footer(): void
    {
        $html = $this->get(route('welcome'))->getContent();

        $this->assertSame(1, substr_count($html, '</main>'));
        $this->assertLessThan(strpos($html, '<footer'), strpos($html, '</main>'));
    }
}