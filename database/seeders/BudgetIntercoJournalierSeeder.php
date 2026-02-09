<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BudgetIntercoJournalierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('budget_interco_journalier')->insertOrIgnore([
            ['annee' => 2025, 'mois' => 'Janvier',   'revenu_journalier' => 8210139, 'charge_journaliere' => 7404551],
            ['annee' => 2025, 'mois' => 'Février',   'revenu_journalier' => 7942493, 'charge_journaliere' => 7473203],
            ['annee' => 2025, 'mois' => 'Mars',      'revenu_journalier' => 6535061, 'charge_journaliere' => 7437196],
            ['annee' => 2025, 'mois' => 'Avril',     'revenu_journalier' => 8483904, 'charge_journaliere' => 7182487],
            ['annee' => 2025, 'mois' => 'Mai',       'revenu_journalier' => 8686822, 'charge_journaliere' => 6741812],
            ['annee' => 2025, 'mois' => 'Juin',      'revenu_journalier' => 9233630, 'charge_journaliere' => 6447625],
            ['annee' => 2025, 'mois' => 'Juillet',   'revenu_journalier' => 7669274, 'charge_journaliere' => 6205455],
            ['annee' => 2025, 'mois' => 'Août',      'revenu_journalier' => 8231307, 'charge_journaliere' => 6307263],
            ['annee' => 2025, 'mois' => 'Septembre', 'revenu_journalier' => 8488436, 'charge_journaliere' => 6378017],
            ['annee' => 2025, 'mois' => 'Octobre',   'revenu_journalier' => 9413242, 'charge_journaliere' => 6367505],
            ['annee' => 2025, 'mois' => 'Novembre',  'revenu_journalier' => 10690645, 'charge_journaliere' => 6811633],
            ['annee' => 2025, 'mois' => 'Décembre',  'revenu_journalier' => 10705743, 'charge_journaliere' => 7696697],
        ]);
    }
}
