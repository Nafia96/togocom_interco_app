<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class KpiRomingRouteTest extends TestCase
{
    private $originalMysqlConnectionConfig;

    protected function tearDown(): void
    {
        if ($this->originalMysqlConnectionConfig !== null) {
            DB::purge('mysql');
            config(['database.connections.mysql' => $this->originalMysqlConnectionConfig]);
        }

        parent::tearDown();
    }

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

    public function test_partner_kpi_uses_asr_label_and_not_duplicate_acd()
    {
        $response = $this->withSession(['id' => 1])->get('/partnerKpi?start_period=2026-08-01&end_period=2026-08-31');

        $response->assertOk();
        $response->assertSee('Tentatives net');
        $response->assertSee('ASR (%)');
        $response->assertSee('ACD (sec)');
        $response->assertDontSee('ACD (%)');
    }

    public function test_roaming_openings_edit_button_exposes_row_data_for_modal_prefill()
    {
        $this->originalMysqlConnectionConfig = config('database.connections.mysql');
        config(['database.connections.mysql' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => false,
        ]]);
        DB::purge('mysql');

        Schema::connection('mysql')->create('roaming_openings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('numero')->default(1);
            $table->string('country')->nullable();
            $table->string('code')->nullable();
            $table->string('operator')->nullable();
            $table->tinyInteger('postpaid_inbound')->default(0);
            $table->tinyInteger('postpaid_outbound')->default(0);
            $table->tinyInteger('camel_inbound')->default(0);
            $table->tinyInteger('camel_outbound')->default(0);
            $table->tinyInteger('data_3g_inbound')->default(0);
            $table->tinyInteger('data_3g_outbound')->default(0);
            $table->tinyInteger('data_4g_inbound')->default(0);
            $table->tinyInteger('data_4g_outbound')->default(0);
            $table->tinyInteger('data_5g_nsa_inbound')->default(0);
            $table->tinyInteger('data_5g_nsa_outbound')->default(0);
            $table->tinyInteger('k2r')->default(0);
            $table->tinyInteger('vrs')->default(0);
        });

        DB::connection('mysql')->table('roaming_openings')->insert([
            'numero' => 1,
            'country' => 'Côte d\'Ivoire',
            'code' => 'CI',
            'operator' => 'Orange CI',
            'postpaid_inbound' => 1,
            'postpaid_outbound' => 0,
            'camel_inbound' => 1,
            'camel_outbound' => 1,
            'data_3g_inbound' => 1,
            'data_3g_outbound' => 0,
            'data_4g_inbound' => 0,
            'data_4g_outbound' => 1,
            'data_5g_nsa_inbound' => 0,
            'data_5g_nsa_outbound' => 0,
            'k2r' => 1,
            'vrs' => 0,
        ]);

        $response = $this->withSession(['id' => 1])->get('/kpi/roaming-openings');

        $response->assertOk();
        $response->assertSee('data-country="Côte d&#039;Ivoire"', false);
        $response->assertSee('data-code="CI"', false);
        $response->assertSee('data-operator="Orange CI"', false);
        $response->assertSee('data-postpaid_inbound="1"', false);
        $response->assertSee('data-postpaid_outbound="0"', false);
        $response->assertSee('data-k2r="1"', false);
        $response->assertSee('data-update-url-template="', false);
        $response->assertSee('<th rowspan="2" class="action-column">Actions</th>', false);
        $response->assertSee('aria-label="Navigation des coûts"', false);
        $response->assertDontSee('aria-label="KPI nav"', false);
        $this->assertSame(1, preg_match('/id="roaming-opening-form"[\s\S]*?name="_method"/', $response->getContent()));
    }
}
