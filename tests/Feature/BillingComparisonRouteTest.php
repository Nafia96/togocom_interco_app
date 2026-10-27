<?php

namespace Tests\Feature;

use Tests\TestCase;

class BillingComparisonRouteTest extends TestCase
{
    public function test_comparison_page_is_accessible_for_connected_user()
    {
        $this->withSession(['id' => 1])
            ->get('/billingp')
            ->assertOk()
            ->assertSee('Comparer réel / provisoire');

        $this->withSession(['id' => 1])
            ->get('/billing-comparison')
            ->assertOk()
            ->assertSee('Comparaison mesures provisoires / réelles')
            ->assertSee('Provisoire')
            ->assertSee('Réel')
            ->assertSee('/billing-comparison/export')
            ->assertSee('Mois de début')
            ->assertSee('Mois de fin');
    }

    public function test_comparison_export_route_returns_csv()
    {
        $this->withSession(['id' => 1])
            ->get('/billing-comparison/export?start_month=2025-01&end_month=2025-01')
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
