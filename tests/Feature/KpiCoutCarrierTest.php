<?php

namespace Tests\Feature;

use Tests\TestCase;

class KpiCoutCarrierTest extends TestCase
{
    public function test_cost_carrier_redirects_guest_to_home()
    {
        $this->get('/kpi/cout-carrier')
            ->assertRedirect(route('home'));
    }

    public function test_cost_carrier_validates_date_format()
    {
        $this->withSession(['id' => 1])
            ->get('/kpi/cout-carrier?start_date=2026-8-01')
            ->assertSessionHasErrors('start_date');
    }

    public function test_logged_user_sees_cost_carrier_table()
    {
        $this->withSession(['id' => 1])
            ->get('/kpi/cout-carrier')
            ->assertOk()
            ->assertSee('COÛT CARRIER')
            ->assertSee('Type de coût')
            ->assertSee('Période')
            ->assertSee('Dest net')
            ->assertSee('Carrier')
            ->assertSee('Rate')
            ->assertSee('Minutes');
    }

    public function test_launchpad_links_to_costs_and_cost_menu_only_shows_carrier_page()
    {
        $this->withSession(['id' => 1, 'type_user' => 3])
            ->get(route('lunchepade'))
            ->assertOk()
            ->assertSee('LES COÛTS')
            ->assertSee(route('kpi.cout-carrier'));

        $this->withSession(['id' => 1])
            ->get('/kpi/cout-carrier')
            ->assertOk()
            ->assertSee('Coût Carrier')
            ->assertDontSee('KPI Roming')
            ->assertDontSee('Partner KPI')
            ->assertDontSee('Network KPI');
    }
}
