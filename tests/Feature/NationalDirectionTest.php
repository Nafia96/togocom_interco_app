<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;
use App\Models\Measure;

class NationalDirectionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create a minimal measures table for the test to avoid running full migrations
        Schema::dropIfExists('measures');
        Schema::create('measures', function (Blueprint $table) {
            $table->id();
            $table->string('period');
            $table->decimal('m_tgt', 20, 2)->nullable();
            $table->decimal('m_tgc', 20, 2)->nullable();
            $table->decimal('diff', 20, 2)->nullable();
            $table->decimal('pct_diff', 8, 2)->nullable();
            $table->string('traffic_validated')->nullable();
            $table->decimal('valuation', 20, 2)->nullable();
            $table->text('comment')->nullable();
            $table->string('direction')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function test_mesure_store_separates_directions()
    {
        $this->withoutMiddleware();

        // Post a measure for TGC->TGT
        $response1 = $this->post(route('mesure.store'), [
            'periode' => '2026-07',
            'm_tgc' => '1000.00',
            'm_tgt' => '900.00',
            'direction' => 'TGC->TGT',
        ]);

        $response1->assertRedirect(route('tgc-tgt'));
        $this->assertDatabaseHas('measures', [
            'period' => '2026-07',
            'direction' => 'TGC->TGT',
        ]);

        // Post a measure for TGT->TGC (same period)
        $response2 = $this->post(route('mesure.store'), [
            'periode' => '2026-07',
            'm_tgc' => '2000.00',
            'm_tgt' => '1900.00',
            'direction' => 'TGT->TGC',
        ]);

        $response2->assertRedirect(route('tgt-tgc'));
        $this->assertDatabaseHas('measures', [
            'period' => '2026-07',
            'direction' => 'TGT->TGC',
        ]);

        $this->assertEquals(1, Measure::where('direction', 'TGC->TGT')->where('period', '2026-07')->count());
        $this->assertEquals(1, Measure::where('direction', 'TGT->TGC')->where('period', '2026-07')->count());
    }

    public function test_tgt_mat_route_and_store_use_canonical_direction()
    {
        $this->withoutMiddleware();
        session(['id' => 1]);

        $response = $this->get(route('tgt-mat'));
        $response->assertOk();

        $storeResponse = $this->post(route('mesure.store'), [
            'periode' => '2026-08',
            'm_tgc' => '4500.00',
            'm_tgt' => '4000.00',
            'direction' => 'TGT->MAT',
        ]);

        $storeResponse->assertRedirect(route('tgt-mat'));
        $this->assertDatabaseHas('measures', [
            'period' => '2026-08',
            'direction' => 'TGT->MAT',
        ]);
    }
}
