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

}
