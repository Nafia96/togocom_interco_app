<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use App\Models\Contestation;
use App\Models\Creditnote;
use App\Models\Invoice;
use App\Models\Journal;
use App\Models\Operation;
use App\Models\Operator;
use App\Models\Resum;
use App\Models\User;
use Illuminate\Http\Request;

class NationalController extends Controller
{
    //Togotelecom - tgc view

    public function show_tgt_tgc()
    {
        return view('national.tgt_tgc_dashboard');
    }

    public function mesure_tgt_tgc(Request $request)
    {
        $request->validate([
            'm_tgc' => 'required|numeric|between:0,99999999999999999999.99',
            'm_tgt' => 'required|numeric|between:0,99999999999999999999.99',
        ]);

        $data = $request->all();
        //dd($data);

        $mesure = new Resum();
        $mesure->m_tgc = $request->m_tgc;
        $mesure->save();



        return redirect()->route('show_tgt_tgc')->with('success', 'Mesure de TGC ajoutée avec succès.');
    }



}
