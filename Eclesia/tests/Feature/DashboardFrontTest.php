<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardFrontTest extends TestCase
{
    /**
     * Le tableau de bord répond 200 pour un visiteur.
     */
    public function test_dashboard_returns_successful_response(): void
    {
        $this->get('/')->assertStatus(200);
    }

    /**
     * Le tableau de bord affiche les 3 graphiques restants (le 2e a été retiré),
     * chacun occupant un conteneur à hauteur fixe.
     * Une hauteur fixe garantit que les graphiques ne s'étendent pas à l'infini
     * (Chart.js a besoin d'un parent dimensionné en maintainAspectRatio: false).
     */
    public function test_charts_are_wrapped_in_fixed_height_containers(): void
    {
        $html = $this->get('/')->getContent();

        foreach (['sparklineChart', 'sectorChart', 'doughnutChart'] as $chartId) {
            $this->assertStringContainsString("<canvas id=\"$chartId\"", $html, "Le canvas $chartId doit être présent.");
        }
        $this->assertStringNotContainsString('histogramChart', $html, 'Le graphique histogramme doit être supprimé.');

        $this->assertSame(3, substr_count($html, 'class="chart-box"'), 'Chaque canvas doit être entouré d\'un .chart-box.');

        // La hauteur fixe est définie dans la feuille de style dédiée (resources/css/app.css),
        // compilée par Vite dans public/build. Le manifest indique le fichier CSS à vérifier.
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        $cssFile = $manifest['resources/css/app.css']['file'] ?? null;

        $this->assertNotNull($cssFile, 'La feuille de styles app.css doit exister dans le manifest Vite.');
        $this->assertStringContainsString(
            '--chart-height',
            file_get_contents(public_path('build/'.$cssFile)),
            'La hauteur fixe des graphiques doit être définie via la variable CSS --chart-height.'
        );
    }

    /**
     * La sidebar ne doit plus flotter au-dessus de la page (position-fixed supprimée).
     * Sur bureau elle devient sticky dans le flux, sur mobile un offcanvas.
     */
    public function test_sidebar_is_sticky_and_does_not_float_over_content(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('offcanvas-lg', $html, 'La sidebar doit être un offcanvas responsive.');
        $this->assertStringContainsString('position: sticky', $html, 'La sidebar doit être sticky sur bureau.');
        $this->assertStringNotContainsString('min-height: 100vh', $html, 'La sidebar ne doit plus forcer une hauteur pleine par inline.');
        $this->assertStringNotContainsString('z-index:100', $html, 'La sidebar ne doit plus passer au-dessus du conteneur.');
    }
}
