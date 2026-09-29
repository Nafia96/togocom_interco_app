<?php

namespace Tests\Feature;

use Tests\TestCase;

class KpiRomingRouteTest extends TestCase
{
    public function test_kpi_roming_route_is_available_for_logged_user()
    {
        $response = $this->withSession(['id' => 1])->get('/kpi/roaming?direction=IN&view=month&start_date=2026-08-01&end_date=2026-08-10&orig_type=GSM&dest_type=GSM&orig_net=Orange&dest_net=MTN&partner=operator');

        $response->assertOk();
    }

    public function test_kpi_roming_route_accepts_granularity_and_renders_period_control()
    {
        $response = $this->withSession(['id' => 1])->get('/kpi/roaming?filter_direction=IN&filter_orig_type=GSM&filter_dest_type=GSM&filter_orig_net=Orange&filter_dest_net=MTN&filter_partner=operator&start_date=2026-08-01&end_date=2026-08-10&granularity=week');

        $response->assertOk();
        $response->assertSee('Périodicité');
        $response->assertSee('Semaine');
        $response->assertSee('Période');
        $response->assertSee('Nombre de tentatives');
        $response->assertSee('Qualité réseau');
        $response->assertSee('Taux d’efficacité');
        $response->assertSee('Durée moyenne de communication');
    }
}
