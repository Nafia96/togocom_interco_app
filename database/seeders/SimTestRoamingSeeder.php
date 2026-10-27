<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SimTestRoamingSeeder extends Seeder
{
    public function run()
    {
        $path = resource_path('views/sim_test_roaming.csv');
        if (!is_readable($path)) {
            throw new RuntimeException('CSV SIM de test introuvable : ' . $path);
        }

        $handle = fopen($path, 'r');
        $expectedHeaders = ['group_number', 'msisdn', 'imsi', 'rp_name', 'country', 'comments'];
        $headers = fgetcsv($handle);
        if ($headers !== $expectedHeaders) {
            fclose($handle);
            throw new RuntimeException('Les colonnes du CSV SIM de test ne correspondent pas au format attendu.');
        }

        $rows = [];
        while (($values = fgetcsv($handle)) !== false) {
            if (count($values) !== count($expectedHeaders)) {
                fclose($handle);
                throw new RuntimeException('Une ligne du CSV SIM de test contient un nombre de colonnes invalide.');
            }

            $row = array_combine($expectedHeaders, $values);
            $rows[] = [
                'group_number' => $row['group_number'] === '' ? null : (int) $row['group_number'],
                'msisdn' => $row['msisdn'],
                'imsi' => $row['imsi'],
                'rp_name' => $row['rp_name'],
                'country' => $row['country'],
                'comments' => $row['comments'] === '' ? null : $row['comments'],
            ];
        }
        fclose($handle);

        if (empty($rows)) {
            throw new RuntimeException('Le CSV SIM de test ne contient aucune donnée.');
        }

        DB::connection('mysql')->transaction(function () use ($rows) {
            foreach (array_chunk($rows, 250) as $chunk) {
                DB::connection('mysql')->table('sim_test_roaming')->upsert(
                    $chunk,
                    ['group_number', 'msisdn', 'imsi', 'rp_name'],
                    ['country', 'comments']
                );
            }
        });
    }
}
