<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('agence')->insert([

            'nom' => 'Agence TEST',
            'code_barre' => 'A55SSS55',
            'tel1' =>"90111111",
            'ville' => "Tchamba",

        ]);

        DB::table('users')->insert([
            'name' => 'DUPOND',
            'first_name' => 'Charles',
            'tel2' =>"superadmin@gmail.com",
            'type_user' => 1,
            'login' => 'agent',
            'id_agence' => 1,
            'password' => Hash::make('123456789')
        ]);
    }
}
