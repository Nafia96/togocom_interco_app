<?php

namespace Tests\Feature;

use Tests\TestCase;

class BillingComparisonRouteTest extends TestCase
{
    public function test_comparison_page_is_accessible_for_connected_user()
    {
        $previousMonth = now()->subMonth()->format('Y-m');

        $this->withSession(['id' => 1])
            ->get('/billingp')
            ->assertOk()
            ->assertSee('COMPARAISON FACTURES')
            ->assertDontSee('Comparer réel / provisoire')
            ->assertSee('KPI Roming')
            ->assertSee('btn-outline-warning border-warning', false)
            ->assertDontSee('btn-outline-warning text-warning', false);

        $this->withSession(['id' => 1])
            ->get('/billing-comparison')
            ->assertOk()
            ->assertSee('Comparaison mesures provisoires / réelles')
            ->assertSee('Volume YAS')
            ->assertSee('Volume partenaire')
            ->assertSee('Charge YAS')
            ->assertSee('Charge partenaire')
            ->assertSee('Écart volume')
            ->assertSee('Écart charge')
            ->assertDontSee('Partenaire - YAS')
            ->assertDontSee('vs YAS')
            ->assertSee('VOLUMES (MINUTES)')
            ->assertSee('MONTANTS (XOF)')
            ->assertSee('table.billing-comparison-table tbody td.kpi-good', false)
            ->assertSee('table.billing-comparison-table tbody td.kpi-bad', false)
            ->assertSee('border-left: 4px solid #ffc107', false)
            ->assertSee('<td class="text-center">', false)
            ->assertSee('(XOF)')
            ->assertSee('name="start_month" value="' . $previousMonth . '"', false)
            ->assertSee('name="end_month" value="' . $previousMonth . '"', false)
            ->assertSee('value="" selected>Tous les opérateurs</option>', false)
            ->assertSee('name="start_month"', false)
            ->assertSee('name="end_month"', false)
            ->assertSee('name="operator"', false)
            ->assertSee('onchange="this.form.submit()"', false)
            ->assertDontSee('<tr class="filter-row">', false)
            ->assertSeeInOrder(['Période de début', 'Période de fin', 'Opérateur', 'Tous les opérateurs', 'Appliquer'])
            ->assertSee('Période de facturation')
            ->assertSee('/billing-comparison/export')
            ->assertSee('Période de début')
            ->assertSee('Période de fin')
            ->assertSeeInOrder([
                'Volume partenaire',
                'Volume YAS',
                'Écart volume',
                'Charge partenaire',
                'Charge YAS',
                'Écart charge',
            ]);
    }

    public function test_comparison_export_route_returns_csv()
    {
        $this->withSession(['id' => 1])
            ->get('/billing-comparison/export?start_month=2025-01&end_month=2025-01')
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
