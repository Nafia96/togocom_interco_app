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
}
