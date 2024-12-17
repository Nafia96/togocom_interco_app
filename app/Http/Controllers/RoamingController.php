<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\Client;
use App\Models\Comptes;
use App\Models\IotDiscount;
use App\Models\Journal;
use App\Models\Operation;
use App\Models\User;
use Illuminate\Http\Request;

class RoamingController extends Controller
{
    //Roaming controlleur


    public function iot_discount()
    {
        if (session('id') != null) {

            $itds = IotDiscount::orderBy("created_at", 'DESC')->get();
            return view('roaming.iot_discount', compact('itds'));


        }

        return view('index');
    }


    public function iot_discount_register(Request $request)
    {

        $request->validate([
            'code' => '|required|string|max:255',
            'operator' => '|required|string|max:255',
            'comments' => 'nullable|string',

        ]);

        $data = $request->all();


       // dd($data);
         IotDiscount::create([
            'code' => $data['code'],
            'country' => $data['country'],
            'operator' => $data['operator'],
            'live_date' => $data['live_date'],
            'renewal' => $data['renewal'],
            'negociation' => $data['negociation'],
            'comments' => $data['comments'],

        ]);

        Journal::create([
            'action' => "Ajout de l'IOT discount de l'opérateur " . $data['operator'],
            'user_id' => session('id'),
        ]);

        return redirect('iot_discount')->with('flash_message_success', 'IOT Discount ajouté avec succès!');
    }


}
