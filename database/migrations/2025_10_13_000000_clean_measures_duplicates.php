<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

class CleanMeasuresDuplicates extends Migration
{
    /**
     * Run the migrations.
     * Remove duplicate measures (same direction + period), keeping the latest id per group.
     * IMPORTANT: backup your DB before running migrations that delete rows.
     *
     * @return void
     */
    public function up()
    {
        // If the measures table doesn't exist yet (migrations ordering), skip this migration.
        if (!Schema::hasTable('measures')) {
            return;
        }

        // Find duplicate groups
        $duplicates = DB::table('measures')
            ->select('direction', 'period', DB::raw('COUNT(*) as cnt'))
            ->groupBy('direction', 'period')
            ->having('cnt', '>', 1)
            ->get();

        foreach ($duplicates as $dup) {
            $rows = DB::table('measures')
                ->where('direction', $dup->direction)
                ->where('period', $dup->period)
                ->orderBy('id', 'desc')
                ->get();

            // Keep the first (highest id), delete the rest
            $keep = true;
            $idsToDelete = [];
            foreach ($rows as $r) {
                if ($keep) {
                    $keep = false;
                    continue;
                }
                $idsToDelete[] = $r->id;
            }

            if (!empty($idsToDelete)) {
                DB::table('measures')->whereIn('id', $idsToDelete)->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     * Note: deleted rows cannot be restored by this migration.
     *
     * @return void
     */
    public function down()
    {
        // nothing to do - this migration irreversibly deletes duplicates
    }
}
