<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        //création de l'utilisateur principal:Admin
        DB::table('users')->insert([

            'last_name' => 'DJIRE',
            'first_name' => 'Nahfiou',
            'email' =>"superadmin@gmail.com",
            'type_user' => 3,
            'avatar' => "/avatar/default.png",
            'login' => 'superAdmin',
            'password' => Hash::make('123456789')
        ]);

         //création d'un compte de Togocom
         DB::table('operator')->insert([

            'name' => 'TOGOCOM',
            'currency' => 'XOF',
            'tel' => '0000000',
            'email' => 'info@togocom.com',
            'adresse' => 'Lomé, cacaveli',
            'country' => 'Togo',
            'is_delete' => '3',

        ]);


        //création d'un compte de Togocom
        DB::table('account')->insert([

            'id_operator' => 1,
            //Account number of Togocom is 000
            'account_number' =>000,
            'receivable' => 0,
            'debt' => 0,
            'netting' => 0,
        ]);



      /*  DB::table('comptes')->insert([
            'compte' => 000000001,
            'debut_de_cotisation' => '2022-01-13 15:17:40',
            'account_number' =>000000001,
            'type_compte' => 'agence_principal',
            'id_agence' => 1,
            'add_by_id' => 1,

            'id_client' => 1,
        ]);

        DB::table('comptes')->insert([
            'compte' => 000000002,
            'debut_de_cotisation' => '2022-01-13 15:17:40',
            'account_number' =>000000002,
            'type_compte' => 'tontine',
            'id_agence' => 1,
            'add_by_id' => 1,
            'is_delete' => 2,

            'id_client' => 1,
        ]);


        DB::table('client')->insert([
            'occupation' => "",
            'type_client' => 'agence_principal',
            'adresse_collecte' =>"",
            'id_user' => 1,
            'id_agence' => 1,
        ]);

        */
    }
}
