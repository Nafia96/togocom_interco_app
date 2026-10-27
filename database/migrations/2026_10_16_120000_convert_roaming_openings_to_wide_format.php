<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConvertRoamingOpeningsToWideFormat extends Migration
{
    private $services = [
        'POSTPAID:inbound' => 'postpaid_inbound',
        'POSTPAID:outbound' => 'postpaid_outbound',
        'CAMEL:inbound' => 'camel_inbound',
        'CAMEL:outbound' => 'camel_outbound',
        'DATA 3G:inbound' => 'data_3g_inbound',
        'DATA 3G:outbound' => 'data_3g_outbound',
        'DATA 4G:inbound' => 'data_4g_inbound',
        'DATA 4G:outbound' => 'data_4g_outbound',
        'DATA 5G N-SA:inbound' => 'data_5g_nsa_inbound',
        'DATA 5G N-SA:outbound' => 'data_5g_nsa_outbound',
        'K2R:' => 'k2r',
        'VRS:' => 'vrs',
    ];

    public function up()
    {
        Schema::table('roaming_openings', function (Blueprint $table) {
            $table->unsignedInteger('numero')->nullable();
            $table->boolean('postpaid_inbound')->default(false);
            $table->boolean('postpaid_outbound')->default(false);
            $table->boolean('camel_inbound')->default(false);
            $table->boolean('camel_outbound')->default(false);
            $table->boolean('data_3g_inbound')->default(false);
            $table->boolean('data_3g_outbound')->default(false);
            $table->boolean('data_4g_inbound')->default(false);
            $table->boolean('data_4g_outbound')->default(false);
            $table->boolean('data_5g_nsa_inbound')->default(false);
            $table->boolean('data_5g_nsa_outbound')->default(false);
            $table->boolean('k2r')->default(false);
            $table->boolean('vrs')->default(false);
        });

        $groups = DB::table('roaming_openings')
            ->orderBy('id')
            ->get()
            ->groupBy(function ($row) {
                return json_encode([$row->country, $row->code, $row->operator]);
            });

        $numero = 1;
        foreach ($groups as $rows) {
            $first = $rows->first();
            $values = ['numero' => $numero++];
            foreach ($this->services as $column) {
                $values[$column] = false;
            }

            foreach ($rows as $row) {
                $service = strtoupper(trim($row->service));
                $direction = strtolower(trim($row->direction ?? ''));
                $key = $service . ':' . $direction;
                if (isset($this->services[$key])) {
                    $values[$this->services[$key]] = true;
                }
            }

            DB::table('roaming_openings')->where('id', $first->id)->update($values);
            $duplicateIds = $rows->pluck('id')->slice(1)->all();
            if ($duplicateIds) {
                DB::table('roaming_openings')->whereIn('id', $duplicateIds)->delete();
            }
        }

        Schema::table('roaming_openings', function (Blueprint $table) {
            $table->dropIndex('idx_service');
            $table->dropIndex('idx_direction');
            $table->dropColumn(['service', 'direction']);
        });
    }

    public function down()
    {
        Schema::table('roaming_openings', function (Blueprint $table) {
            $table->string('service', 50)->nullable();
            $table->string('direction', 20)->nullable();
            $table->index('service', 'idx_service');
            $table->index('direction', 'idx_direction');
        });

        $rows = DB::table('roaming_openings')->orderBy('id')->get();
        foreach ($rows as $row) {
            $openings = [];
            foreach ($this->services as $key => $column) {
                if (!$row->$column) {
                    continue;
                }

                list($service, $direction) = explode(':', $key, 2);
                $openings[] = [
                    'service' => $service,
                    'direction' => $direction === '' ? null : $direction,
                ];
            }

            if (!$openings) {
                $openings[] = ['service' => '', 'direction' => null];
            }

            $firstOpening = array_shift($openings);
            DB::table('roaming_openings')->where('id', $row->id)->update($firstOpening);
            foreach ($openings as $opening) {
                DB::table('roaming_openings')->insert([
                    'country' => $row->country,
                    'code' => $row->code,
                    'operator' => $row->operator,
                    'service' => $opening['service'],
                    'direction' => $opening['direction'],
                    'created_at' => $row->created_at,
                ]);
            }
        }

        Schema::table('roaming_openings', function (Blueprint $table) {
            $table->dropColumn([
                'numero',
                'postpaid_inbound',
                'postpaid_outbound',
                'camel_inbound',
                'camel_outbound',
                'data_3g_inbound',
                'data_3g_outbound',
                'data_4g_inbound',
                'data_4g_outbound',
                'data_5g_nsa_inbound',
                'data_5g_nsa_outbound',
                'k2r',
                'vrs',
            ]);
        });
    }
}
