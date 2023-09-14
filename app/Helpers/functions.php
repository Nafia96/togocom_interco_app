<?php

use App\Models\Account;
use App\Models\ControleLigne;
use App\Models\ControleZone;
use App\Models\Ligne;
use App\Models\User;
use App\Models\Zone;

if (!function_exists('generateAccountNumber')) {

    function generateAccountNumber()
    {
        $number = mt_rand(100000000, 999999999); // better than rand()

        // call the same function if the barcode exists already
        if (accountNumberExists($number)) {
            return generateAccountNumber();
        }

        // otherwise, it's valid and can be used
        return $number;
    }
}

if (!function_exists('accountNumberExists')) {

    function accountNumberExists($number)
    {
        // query the database and return a boolean
        // for instance, it might look like this in Laravel
        return Account::whereAccount_number($number)->exists();
    }
}

//Function qui genere les numeros de compte en function de la region et le type de compte
//Gener les compte tontine des agence de Tchamba

if (!function_exists('tontineAccountNumber')) {

    function tontineAccountNumber()
    {
        $user = User::where(['id' => session('id')])->first();

        $agence_ville = $user->agence->ville;

        $result='';


         $total_compte_tontine = Account::where('type_compte','tontine')
         ->get()->count();

        //   dd($last_insert_compte->compte);

         $compte = $total_compte_tontine+1;



        if ($agence_ville == 'Tchamba') {


        $start = 120;
        $end = 400;

        $result = $start . '' . $compte . '' . $end;

        return $result;



        } elseif ($agence_ville == 'Lome') {


        $start = 140;
        $end = 400;

        $result = $start . '' . $compte . '' . $end;

        return $result;

        } elseif ($agence_ville == 'kara') {


        $start = 160;
        $end = 400;

        $result = $start . '' . $compte . '' . $end;

        return $result;

        } elseif ($agence_ville == 'sokode') {


        $start = 180;
        $end = 400;

        $result = $start . '' . $compte . '' . $end;

        return $result;

        }elseif ($agence_ville == 'kpalime') {


            $start = 200;
            $end = 400;

            $result = $start . '' . $compte . '' . $end;

            return $result;

            }


    }
}


//Epargen generate account Number

if (!function_exists('epargneAccountNumber')) {

    function epargneAccountNumber()
    {
        $user = User::where(['id' => session('id')])->first();

        $agence_ville = $user->agence->ville;

        $result='';

       // dd($agence_ville);

        if ($agence_ville == 'Tchamba') {

        $compte = generateAccountNumber();

        $start = 120;
        $end = 800;

        $result = $start . '' . $compte . '' . $end;

        return $result;



        } elseif ($agence_ville == 'Lome') {

        $compte = generateAccountNumber();

        $start = 140;
        $end = 600;

        $result = $start . '' . $compte . '' . $end;

        return $result;

        } elseif ($agence_ville == 'kara') {

            $compte = generateAccountNumber();

        $start = 160;
        $end = 600;

        $result = $start . '' . $compte . '' . $end;

        return $result;

        } elseif ($agence_ville == 'sokode') {

        $compte = generateAccountNumber();

        $start = 180;
        $end = 600;

        $result = $start . '' . $compte . '' . $end;

        return $result;

        } elseif ($agence_ville == 'kpalime') {

            $compte = generateAccountNumber();

            $start = 200;
            $end = 600;

            $result = $start . '' . $compte . '' . $end;

            return $result;

            }


    }
}

//End epargne


//Salaire generate account Number

if (!function_exists('salaireAccountNumber')) {

    function salaireAccountNumber()
    {
        $user = User::where(['id' => session('id')])->first();

        $agence_ville = $user->agence->ville;

        $result='';

        if ($agence_ville == 'Tchamba') {

        $compte = generateAccountNumber();

        $start = 120;
        $end = 800;

        $result = $start . '' . $compte . '' . $end;

        return $result;



        } elseif ($agence_ville == 'Lome') {

        $compte = generateAccountNumber();

        $start = 140;
        $end = 800;

        $result = $start . '' . $compte . '' . $end;

        return $result;

        } elseif ($agence_ville == 'kara') {

            $compte = generateAccountNumber();

        $start = 160;
        $end = 800;

        $result = $start . '' . $compte . '' . $end;

        return $result;

        } elseif ($agence_ville == 'sokode') {

        $compte = generateAccountNumber();

        $start = 180;
        $end = 800;

        $result = $start . '' . $compte . '' . $end;

        return $result;

        }elseif ($agence_ville == 'kpalime') {

            $compte = generateAccountNumber();

            $start = 200;
            $end = 800;

            $result = $start . '' . $compte . '' . $end;

            return $result;

            }


    }
}

//End Salaire




//Depot generate account Number

if (!function_exists('depotAccountNumber')) {

    function depotAccountNumber()
    {
        $user = User::where(['id' => session('id')])->first();

        $agence_ville = $user->agence->ville;

        $result='';

        if ($agence_ville == 'Tchamba') {

        $compte = generateAccountNumber();

        $start = 120;
        $end = 900;

        $result = $start . '' . $compte . '' . $end;

        return $result;



        } elseif ($agence_ville == 'Lome') {

        $compte = generateAccountNumber();

        $start = 140;
        $end = 900;

        $result = $start . '' . $compte . '' . $end;

        return $result;

        } elseif ($agence_ville == 'kara') {

            $compte = generateAccountNumber();

        $start = 160;
        $end = 900;

        $result = $start . '' . $compte . '' . $end;

        return $result;

        } elseif ($agence_ville == 'sokode') {

        $compte = generateAccountNumber();

        $start = 180;
        $end = 900;

        $result = $start . '' . $compte . '' . $end;

        return $result;

        }


    }
}

//End Depot

if (!function_exists('getChefAgence')) {
    function getChefAgence($id_agence)
    {
        $user = User::where(['id_agence' => $id_agence,
            'type_user' => 2,
        ])->first();

        return $user;

    }
}

// Getting permissions
if (!function_exists('getAddingAgent')) {
    function getAddingAgent($id_agent)
    {
        $user = User::where(['id' => $id_agent])->first();

        return $user;

    }
}

if (!function_exists('getUserType')) {
    function getUserType()
    {
        $user = User::where(['id' => session('id')])->first();

        return $user;

    }
}


if (!function_exists('periodePrint')) {
    function periodePrint($period)
    {
        $date = strtotime ( $period );
        $newDate = date ( 'M-Y' , $date ) ;
        return $newDate;

    }
}


if (!function_exists('getAllZones')) {
    function getAllZones()
    {
        $zones = Zone::where('is_delete', 0)
            ->orderBy('nom', 'ASC')
            ->get();

        return $zones;
    }
}

if (!function_exists('getAllLignes')) {
    function getAllLignes()
    {
        $lignes = Ligne::where('is_delete', 0)
            ->orderBy('nom', 'ASC')
            ->get();

        return $lignes;

    }
}

// Getting user logged informations
if (!function_exists('getUserAuth')) {
    function getUserAuth()
    {
        $id = session('id');
        $user = User::where(['id' => $id])->first();
        return $user;

    }
}
// Random string
if (!function_exists('getRamdomText')) {
    function getRamdomText($n)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }
}
// int string
if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 25)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('getRamdomInt')) {
    function getRamdomInt($n)
    {
        $characters = '0123456789';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date)
    {
        $formatDates = explode("T", $date);
        $elements = explode(" ", $formatDates[0]);
        $elements2 = explode("-", $elements[0]);
        $date = $elements2[2] . "-" . $elements2[1] . "-" . $elements2[0] . " " . $elements[1];
        return $date;
    }
}

if (!function_exists('formatDate2')) {
    function formatDate2($date)
    {
        $elements2 = explode("-", $date);
        $date = $elements2[2] . "-" . $elements2[1] . "-" . $elements2[0];
        return $date;
    }
}
// Random int
if (!function_exists('getRamdomInt')) {
    function getRamdomInt($n)
    {
        $characters = '0123456789';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }
}

if (!function_exists('getMonthName')) {
    function getMonthName($monthOfYear)
    {
        $arrayMonth = array(
            1 => "Janvier",
            2 => "Février",
            3 => "Mars",
            4 => "Avril",
            5 => "Mai",
            6 => "Juin",
            7 => "Juillet",
            8 => "Aôut",
            9 => "Septembre",
            10 => "Octobre",
            11 => "Novembre",
            12 => "Décembre",
        );
        $month = $arrayMonth[$monthOfYear];
        return $month;
    }
}
if (!function_exists('existInZone')) {
    function existInZone($controle, $zone)
    {
        return ControleZone::where(['controle_id' => $controle->id, 'zone_id' => $zone->id])->get()->count();
    }
}

if (!function_exists('existInLigne')) {
    function existInLigne($controle, $ligne)
    {
        return ControleLigne::where(['controle_id' => $controle->id, 'ligne_id' => $ligne->id])->get()->count();
    }
}
