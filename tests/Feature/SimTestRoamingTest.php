<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\SimTestRoamingSeeder;
use Tests\TestCase;

class SimTestRoamingTest extends TestCase
{
    private $originalMysqlConnectionConfig;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalMysqlConnectionConfig = config('database.connections.mysql');
        config(['database.connections.mysql' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => false,
        ]]);
        DB::purge('mysql');

        Schema::connection('mysql')->create('sim_test_roaming', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('group_number')->nullable();
            $table->string('msisdn', 20);
            $table->string('imsi', 20);
            $table->string('rp_name', 150);
            $table->string('country', 100);
            $table->string('comments', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unique(['group_number', 'msisdn', 'imsi', 'rp_name']);
        });
    }

    protected function tearDown(): void
    {
        DB::purge('mysql');
        config(['database.connections.mysql' => $this->originalMysqlConnectionConfig]);

        parent::tearDown();
    }

    public function test_page_filters_and_shows_sim_records()
    {
        DB::connection('mysql')->table('sim_test_roaming')->insert([
            ['group_number' => 1, 'msisdn' => '22890740042', 'imsi' => '615010112442156', 'rp_name' => 'SIM_TEST_ROAMING_DU UAE_1', 'country' => 'UAE', 'comments' => null],
            ['group_number' => 2, 'msisdn' => '22890769693', 'imsi' => '615010112442164', 'rp_name' => 'SIM_TEST_ROAMING_FREE MOBILE FRANCE_1', 'country' => 'FRANCE', 'comments' => 'Test'],
        ]);

        $response = $this->withSession(['id' => 1])->get(route('kpi.sim-test-roaming'));

        $response->assertOk();
        $response->assertSee('SIM_TEST_ROAMING_DU UAE_1');
        $response->assertSee('22890740042');
        $response->assertSee('data-imsi="615010112442156"', false);
        $response->assertSee('data-country="UAE"', false);
        $response->assertSee('Rechercher COMMENTAIRES');
        $response->assertSee('Exporter CSV');
        $response->assertSee('aria-label="Navigation des coûts"', false);
        $response->assertDontSee('aria-label="KPI nav"', false);
        $response->assertSee('Modifier');
        $response->assertSee('Supprimer');

        $filtered = $this->withSession(['id' => 1])->get(route('kpi.sim-test-roaming', ['filter_country' => 'FRANCE']));
        $filtered->assertOk();
        $filtered->assertSee('SIM_TEST_ROAMING_FREE MOBILE FRANCE_1');
        $filtered->assertDontSee('22890740042');
    }

    public function test_create_update_and_delete_only_the_selected_sim()
    {
        $data = [
            'group_number' => 3,
            'msisdn' => '22890049449',
            'imsi' => '615010112442168',
            'rp_name' => 'SIM_TEST_ROAMING_SFR FRANCE_1',
            'country' => 'FRANCE',
            'comments' => 'Initial',
        ];

        $created = $this->withSession(['id' => 1])->post(route('kpi.sim-test-roaming.store'), $data);
        $created->assertRedirect(route('kpi.sim-test-roaming'));
        $this->assertDatabaseHas('sim_test_roaming', ['msisdn' => $data['msisdn'], 'comments' => 'Initial'], 'mysql');
        $id = DB::connection('mysql')->table('sim_test_roaming')->where('msisdn', $data['msisdn'])->value('id');

        $data['comments'] = 'Updated';
        $updated = $this->withSession(['id' => 1])->put(route('kpi.sim-test-roaming.update', $id), $data);
        $updated->assertRedirect(route('kpi.sim-test-roaming'));
        $this->assertDatabaseHas('sim_test_roaming', ['id' => $id, 'comments' => 'Updated'], 'mysql');

        $deleted = $this->withSession(['id' => 1])->delete(route('kpi.sim-test-roaming.destroy', $id));
        $deleted->assertRedirect(route('kpi.sim-test-roaming'));
        $this->assertDatabaseMissing('sim_test_roaming', ['id' => $id], 'mysql');
    }

    public function test_seeder_can_be_repeated_without_duplicating_or_deleting_records()
    {
        (new SimTestRoamingSeeder())->run();
        $firstCount = DB::connection('mysql')->table('sim_test_roaming')->count();
        $this->assertSame(959, $firstCount);

        (new SimTestRoamingSeeder())->run();

        $this->assertSame($firstCount, DB::connection('mysql')->table('sim_test_roaming')->count());
        $this->assertSame(2, DB::connection('mysql')->table('sim_test_roaming')->where('group_number', 154)->count());
    }
}
