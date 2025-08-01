<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Agent;
use App\Models\Client;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Journal;
use App\Models\Operation;
use App\Models\Resum;
use App\Models\User;
use Illuminate\Http\Request;

class OperatorController extends Controller
{



    public function add_operator()
    {
        return view('operator/add_operator');
    }

    public function ope_dashboard($id_operator)
    {

        $sum_resum = Resum::selectRaw('year(resum.periodDate) year, SUM(receivable) as total_receivable,
            SUM(debt) as total_debt,SUM(incoming_payement) as encaissement,SUM(payout) as decaissement')
            ->Where(['id_operator' => $id_operator, 'is_delete' => 0])
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        $sum_resum_total = Resum::selectRaw(' SUM(receivable) as total_receivable,
            SUM(debt) as total_debt,SUM(incoming_payement) as encaissement,SUM(payout) as decaissement')
            ->Where(['id_operator' => $id_operator, 'is_delete' => 0])
            ->first();

        $sum_resum_total_annuelle = Resum::selectRaw('year(resum.periodDate) year, SUM(receivable) as total_receivable,
            SUM(debt) as total_debt,SUM(incoming_payement) as encaissement,SUM(payout) as decaissement')
            ->Where(['id_operator' => $id_operator, 'is_delete' => 0])
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->first();


        $operator = Operator::where('id', $id_operator)->first();
        $op_account = Account::where('id_operator', $operator->id)->first();

        $resums = Resum::where(['id_operator' => $id_operator, 'is_delete' => 0])
            ->orderBy('period', 'DESC')
            ->get();

        $decaissement_invoices = Invoice::where(['operator_id' => $id_operator, 'is_delete' => 0, 'tgc_invoice' => 1])
            ->whereYear('periodDate', '=', date('Y'))
            ->get();

        $encaissement_invoices = Invoice::where(['operator_id' => $id_operator, 'is_delete' => 0, 'tgc_invoice' => 1])
            ->whereYear('periodDate', '=', date('Y'))
            ->get();


        $operations = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0])
            ->orderBy('updated_at', 'DESC')
            ->get();


        return view('operator/ope_dashboard', compact('sum_resum_total_annuelle', 'sum_resum_total', 'sum_resum', 'operations', 'op_account', 'resums', 'operator'))->render();
    }
    public function operator_register(Request $request)
    {

        $request->validate([
            'name' => '|required|unique:operator|string|max:255',
            'tel' => 'required|string',
            'tel2' => 'nullable|string',
            'email' => 'required|email',
            'email2' => 'nullable|email',
            'email3' => 'nullable|email',
            'adresse' => 'nullable|string',
            'country' => 'required|string',
            'currency' => 'required|string',
            'description' => 'nullable|string|max:500',
            'rib' => 'nullable|string|max:500',
            'ope_account_number' => 'nullable|string|max:500',
            'banque_adresse' => 'nullable|string|max:800',
            'swift_code' => 'nullable|string|max:500',

        ]);

        $data = $request->all();

        $operator = Operator::create([
            'name' => $data['name'],
            'tel' => $data['tel'],
            'tel2' => $data['tel2'],
            'email' => $data['email'],
            'email2' => $data['email2'],
            'email3' => $data['email3'],
            'adresse' => $data['adresse'],
            'country' => $data['country'],
            'currency' => $data['currency'],
            'cedeao' => $data['cedeao'],
            'afrique' => $data['afrique'],
            'description' => $data['description'],
            'rib' => $data['rib'],
            'ope_account_number' => $data['ope_account_number'],
            'banque_adresse' => $data['banque_adresse'],
            'swift_code' => $data['swift_code'],

        ]);

        Account::create([
            'id_operator' => $operator->id,
            'account_number' => $operator->id . '5577',
            'receivable' => 0,
            'debt' => 0,
            'netting' => 0,

        ]);

        Journal::create([
            'action' => "Création d'un opérateur " . $data['name'],
            'user_id' => session('id'),
        ]);

        return redirect('liste_operator')->with('flash_message_success', 'Nouveau opérateur ajouté avec succès!');
    }

    public function liste_operator()
    {

        $operators = Operator::where('is_delete', 0)
            ->orderBy('created_at', 'DESC')
            ->get();

        //dd($operators);
        return view('operator.liste_operator', compact('operators'));
    }

    public function liste_operator_netting(Request $request)
{
  $debut = $request->input('start_period');  // ✔ correspond à name="start_period"
$fin = $request->input('end_period');

    $resums_query = Resum::selectRaw('id_operator,
        SUM(receivable) as total_receivable,
        SUM(debt) as total_debt,
        SUM(incoming_payement) as encaissement,
        SUM(payout) as decaissement')
        ->where('is_delete', 0);

    // Appliquer les filtres de période
    if ($debut && $fin) {
        $resums_query->whereBetween('period', [$debut, $fin]);
    } elseif ($debut) {
        $resums_query->where('period', '>=', $debut);
    } elseif ($fin) {
        $resums_query->where('period', '<=', $fin);
    }

    $resums = $resums_query->groupBy('id_operator')->get()->keyBy('id_operator');

    $operators = Operator::where('is_delete', 0)
        ->orderBy('created_at', 'DESC')
        ->get();

    // Totaux globaux
    $total_global_creance = 0;
    $total_global_dette = 0;
    $total_global_encaissement = 0;
    $total_global_decaissement = 0;

    foreach ($operators as $operator) {
        if (isset($resums[$operator->id])) {
            $r = $resums[$operator->id];
            $operator->netting = ($r->total_receivable - $r->encaissement) - ($r->total_debt + $r->decaissement);
            $operator->total_receivable = $r->total_receivable;
            $operator->total_debt = $r->total_debt;
            $operator->encaissement = $r->encaissement;
            $operator->decaissement = $r->decaissement;

            $total_global_creance += $r->total_receivable;
            $total_global_dette += $r->total_debt;
            $total_global_encaissement += $r->encaissement;
            $total_global_decaissement += $r->decaissement;
        } else {
            $operator->netting = 0;
            $operator->total_receivable = 0;
            $operator->total_debt = 0;
            $operator->encaissement = 0;
            $operator->decaissement = 0;
        }
    }

       return view('operator.liste_operator_netting', compact(
       'operators',
        'total_global_creance',
        'total_global_dette',
        'total_global_encaissement',
        'total_global_decaissement',
        'debut',
        'fin'
    ));

}




    public function delete_operator_liste()
    {

        $operators = Operator::where('is_delete', 1)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('operator.liste_operators_supprime', compact('operators'));
    }

    public function info_operator($id)
    {

        $operator = Operator::where('id', $id)->get();
        $chef_operator = User::where('id', $operator->id_chef_operator)->get();

        return view('operator.info_operator', compact('operator', 'chef_operator'));
    }

    public function update_operator($id)
    {
        $operator = Operator::where('id', $id)->first();

        return view('operator.update_operator', compact('operator'));
    }

    public function save_update(Request $request)
    {


        $request->validate([
            'name' => 'required|string|max:255',
            'tel' => 'required|string',
            'tel2' => 'nullable|string',
            'email' => 'required|email',
            'email2' => 'nullable|email',
            'email3' => 'nullable|email',
            'adresse' => 'nullable|string',
            'country' => 'required|string',
            'currency' => 'required|string',
            'description' => 'nullable|string|max:500',

        ]);

        $data = $request->all();

        Operator::where(['id' => $data['id']])->update([
            'name' => $data['name'],
            'tel' => $data['tel'],
            'email' => $data['email'],
            'adresse' => $data['adresse'],
            'country' => $data['country'],
            'currency' => $data['currency'],
            'description' => $data['description'],

        ]);

        Journal::create([
            'action' => "Mise à jours de l\'operator  " . $data['name'],
            'user_id' => session('id'),
        ]);

        return redirect('liste_operator')->with('flash_message_success', "Opérateur mise à jours avec succès!");
    }

    public function save_setting(Request $request)
    {


        $request->validate([
            'name' => 'required|string|max:255',
            'tel' => 'required|string',
            'tel2' => 'nullable|string',
            'email' => 'required|email',
            'email2' => 'nullable|email',
            'email3' => 'nullable|email',
            'adresse' => 'nullable|string',
            'country' => 'required|string',
            'currency' => 'required|string',
            'euro_conversion' => 'required|string',
            'dollar_conversion' => 'required|string',
            'xaf_conversion' => 'required|string',
            'description' => 'nullable|string|max:500',

        ]);

        $data = $request->all();

        Operator::where(['id' => $data['id']])->update([
            'name' => $data['name'],
            'tel' => $data['tel'],
            'email' => $data['email'],
            'adresse' => $data['adresse'],
            'country' => $data['country'],
            'currency' => $data['currency'],
            'euro_conversion' => $data['euro_conversion'],
            'dollar_conversion' => $data['dollar_conversion'],
            'xaf_conversion' => $data['xaf_conversion'],
            'description' => $data['description'],

        ]);

        Journal::create([
            'action' => "Mise à jours des paramètres  " . $data['name'],
            'user_id' => session('id'),
        ]);

        return redirect('dashboard')->with('flash_message_success', "Paramètres mise à jours avec succès!");
    }

    public function delete_operator($id)
    {
        $operator = Operator::where(['id' => $id])->first();
        Operator::where(['id' => $id])->update([
            'is_delete' => 1,
        ]);

        Journal::create([
            'action' => "Suppression de l'opérateur " . $operator->name,
            'user_id' => session('id'),
        ]);
        return redirect('delete_operator_liste')->with('flash_message_success', 'Opérateur supprimé avec succès!');
    }

    public function activate_operator($id)
    {
        $operator = Operator::where(['id' => $id])->first();
        Operator::where(['id' => $id])->update([
            'is_delete' => 0,
        ]);

        Journal::create([
            'action' => "Réactivation de l'opérateur " . $operator->name,
            'user_id' => session('id'),
        ]);
        return redirect('liste_operator')->with('flash_message_success', 'Opérateur réactivé avec succès!');
    }


    public function operator_statistique($id_operator)
    {
        $user = User::where(['id' => session('id')])->first();
        $id_operator = $id_operator;

        $mesClients = Client::where(['id_operator' => $id_operator, 'is_delete' => 0])->get()->count();
        $mesAgents = Agent::where(['id_operator' => $id_operator, 'is_delete' => 0])->get()->count();
        $nbr_comptes = Account::where(['id_operator' => $id_operator, 'is_delete' => 0])->get()->count();
        $operator = Operator::where(['id' => $id_operator, 'is_delete' => 0])->first();

        $operations = Operation::whereId_operator($id_operator)
            ->whereYear('created_at', '=', date('Y'))
            ->whereDay('created_at', '=', date('d'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->orderBy('updated_at', 'DESC')
            ->get();


        $nbre_carnet = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0, 'type_operation' => 'carnet'])->count();
        $solde_carnet_entre = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0, 'type_operation' => 'carnet'])->sum('entre');
        $solde_benefice_carnet = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0, 'type_operation' => 'carnet'])->sum('benefice');

        $solde_carnet = $solde_carnet_entre - $solde_benefice_carnet;

        $sortie = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0])->sum('sortie');
        $entre = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0])->sum('entre');
        $benefice_total = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0])->sum('benefice');

        $sortie_today = Operation::whereId_operator($id_operator)
            ->whereYear('created_at', '=', date('Y'))
            ->whereDay('created_at', '=', date('d'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->sum('sortie');

        $total_entre_today = Operation::whereId_operator($id_operator)
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereDay('created_at', '=', date('d'))
            ->whereIs_delete(0)
            ->sum('entre');


        $tontine_sortie = Operation::whereId_operator($id_operator)
            ->whereType_operation('retrait')
            ->whereType_compte('tontine')
            ->whereIs_delete(0)
            ->sum('sortie');


        $tontine_entre = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0, 'type_operation' => 'cotisation'])->sum('entre');

        $tontine_entre -= $tontine_sortie;

        $tontine_entre_today = Operation::whereId_operator($id_operator)
            ->whereType_operation('cotisation')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('entre');


        $tontine_sortie_today = Operation::whereId_operator($id_operator)
            ->whereType_operation('retrait')
            ->whereType_compte('tontine')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('sortie');



        $epargne_entre_depot = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0, 'type_operation' => 'depot'])->sum('entre');
        $epargne_entre_new = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0, 'type_operation' => 'new'])->sum('entre');

        $epargne_entre = $epargne_entre_depot + $epargne_entre_new;

        $epargne_entre_today_depot = Operation::whereId_operator($id_operator)
            ->whereType_operation('new')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('entre');

        $epargne_entre_today_new = Operation::whereId_operator($id_operator)
            ->whereType_operation('depot')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('entre');

        $epargne_sortie = Operation::whereId_operator($id_operator)
            ->whereType_operation('retrait')
            ->whereType_compte('epargne')
            ->whereIs_delete(0)
            ->sum('sortie');

        $epargne_entre = $epargne_entre - $epargne_sortie;

        $epargne_entre_today = $epargne_entre_today_new + $epargne_entre_today_depot;

        $epargne_sortie_today = Operation::whereId_operator($id_operator)
            ->whereType_operation('retrait')
            ->whereType_compte('epargne')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('sortie');

        $epargne_benefice_today = Operation::whereId_operator($id_operator)
            ->whereType_operation('benefice')
            ->whereType_compte('epargne')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('sortie');


        $versement = Operation::whereId_operator($id_operator)
            ->whereType_operation('versement')
            ->whereIs_delete(0)
            ->sum('versement');

        $solde_entre_today = Operation::whereId_operator($id_operator)
            ->whereYear('created_at', '=', date('Y'))
            ->whereDay('created_at', '=', date('d'))
            ->whereIs_delete(0)
            ->whereMonth('created_at', '=', date('m'))->sum('entre');

        $entre_today = $total_entre_today - $sortie_today;

        $benefice = $benefice_total - $versement;

        $solde_operator = $entre - $sortie - $versement;

        $entre -= $sortie;


        return view('operator.operator_stastic_dashboard', compact('epargne_benefice_today', 'epargne_sortie_today', 'tontine_sortie_today', 'nbre_carnet', 'solde_carnet', 'versement', 'solde_operator', 'solde_entre_today', 'epargne_entre', 'epargne_entre_today', 'tontine_entre_today', 'tontine_entre', 'nbr_comptes', 'mesClients', 'mesAgents', 'operations', 'operator', 'sortie', 'entre', 'benefice_total', 'sortie_today', 'entre_today'));
    }

    public function operator_dashboard()
    {
        $user = User::where(['id' => session('id')])->first();
        $id_operator = session('id_operator');

        $mesClients = Client::where(['id_operator' => $id_operator, 'is_delete' => 0])->get()->count();
        $mesAgents = Agent::where(['id_operator' => $id_operator, 'is_delete' => 0])->get()->count();
        $nbr_comptes = Account::where(['id_operator' => $id_operator, 'is_delete' => 0])->get()->count();
        $operator = Operator::where(['id' => $id_operator, 'is_delete' => 0])->first();

        $operations = Operation::whereId_operator(session('id_operator'))
            ->whereYear('created_at', '=', date('Y'))
            ->whereDay('created_at', '=', date('d'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->orderBy('updated_at', 'DESC')
            ->get();


        $nbre_carnet = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0, 'type_operation' => 'carnet'])->count();
        $solde_carnet_entre = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0, 'type_operation' => 'carnet'])->sum('entre');
        $solde_benefice_carnet = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0, 'type_operation' => 'carnet'])->sum('benefice');

        $solde_carnet = $solde_carnet_entre;

        $sortie = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0])->sum('sortie');
        $entre = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0])->sum('entre');
        $benefice_total = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0])->sum('benefice');

        $sortie_today = Operation::whereId_operator(session('id_operator'))
            ->whereYear('created_at', '=', date('Y'))
            ->whereDay('created_at', '=', date('d'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->sum('sortie');

        $total_entre_today = Operation::whereId_operator(session('id_operator'))
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereDay('created_at', '=', date('d'))
            ->whereIs_delete(0)
            ->sum('entre');


        $tontine_sortie = Operation::whereId_operator(session('id_operator'))
            ->whereType_operation('retrait')
            ->whereType_compte('tontine')
            ->whereIs_delete(0)
            ->sum('sortie');


        $tontine_entre = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0, 'type_operation' => 'cotisation'])->sum('entre');

        $tontine_entre -= $tontine_sortie;

        $tontine_entre_today = Operation::whereId_operator(session('id_operator'))
            ->whereType_operation('cotisation')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('entre');


        $tontine_sortie_today = Operation::whereId_operator(session('id_operator'))
            ->whereType_operation('retrait')
            ->whereType_compte('tontine')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('sortie');



        $epargne_entre_depot = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0, 'type_operation' => 'depot'])->sum('entre');
        $epargne_entre_new = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0, 'type_operation' => 'new'])->sum('entre');

        $epargne_entre = $epargne_entre_depot + $epargne_entre_new;

        $epargne_entre_today_depot = Operation::whereId_operator(session('id_operator'))
            ->whereType_operation('new')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('entre');

        $epargne_entre_today_new = Operation::whereId_operator(session('id_operator'))
            ->whereType_operation('depot')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('entre');

        $epargne_sortie = Operation::whereId_operator(session('id_operator'))
            ->whereType_operation('retrait')
            ->whereType_compte('epargne')
            ->whereIs_delete(0)
            ->sum('sortie');

        $epargne_entre = $epargne_entre - $epargne_sortie;

        $epargne_entre_today = $epargne_entre_today_new + $epargne_entre_today_depot;

        $epargne_sortie_today = Operation::whereId_operator(session('id_operator'))
            ->whereType_operation('retrait')
            ->whereType_compte('epargne')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('sortie');

        $epargne_benefice_today = Operation::whereId_operator(session('id_operator'))
            ->whereType_operation('benefice')
            ->whereType_compte('epargne')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('sortie');


        $versement = Operation::whereId_operator(session('id_operator'))
            ->whereType_operation('versement')
            ->whereIs_delete(0)
            ->sum('versement');

        $solde_entre_today = Operation::whereId_operator(session('id_operator'))
            ->whereYear('created_at', '=', date('Y'))
            ->whereDay('created_at', '=', date('d'))
            ->whereIs_delete(0)
            ->whereMonth('created_at', '=', date('m'))->sum('entre');

        $entre_today = $total_entre_today - $sortie_today;

        $benefice = $benefice_total - $versement;

        $solde_operator = $entre - $sortie - $versement;

        $entre -= $sortie;


        return view('operator.operator_dashboard', compact('epargne_benefice_today', 'epargne_sortie_today', 'tontine_sortie_today', 'nbre_carnet', 'solde_carnet', 'versement', 'solde_operator', 'solde_entre_today', 'epargne_entre', 'epargne_entre_today', 'tontine_entre_today', 'tontine_entre', 'nbr_comptes', 'mesClients', 'mesAgents', 'operations', 'operator', 'sortie', 'entre', 'benefice', 'sortie_today', 'entre_today'));
    }

    public function get_new_chef_operator($id)
    {

        $operator = Operator::where(['id' => $id])->first();

        return view('operator.add_new_chef_operator', compact('operator'));
    }

    public function add_new_chef_operator(Request $request)
    {

        $request->validate([

            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string',
            'poste' => 'required|string',
            'user_email' => 'required|email',
            'telephone' => 'required|string',
            'user_adresse' => 'nullable|string',
            'user_quartier' => 'nullable|string',
            'login' => 'required|unique:users|string',
            'password' => 'required|confirmed',
            'card_number' => 'required|string|max:500',

        ]);

        $data = $request->all();

        $olde_chef_operator = User::where([
            'id_operator' => $data['id_operator'],
            'type_user' => 2,
        ])->first();

        User::where(['id' => $olde_chef_operator->id])->update([
            'is_delete' => 1,
            'type_user' => 0,
        ]);

        $document = "/document/default.png";
        if ($request->hasfile('document')) {
            $imageIcon = $request->file('document');
            $exten = $imageIcon->getClientOriginalExtension();
            $imageIconName = $request->name . uniqid() . '.' . $exten;
            $destinationPath = public_path('/document');
            $ulpoadImageSuccess = $imageIcon->move($destinationPath, $imageIconName);
            $document = "/document/" . $imageIconName;
        }

        $avatar = "/avatar/default.png";
        if ($request->hasfile('avatar')) {
            $imageIcon = $request->file('avatar');
            $exten = $imageIcon->getClientOriginalExtension();
            $imageIconName = $request->name . uniqid() . '.' . $exten;
            $destinationPath = public_path('/avatar');
            $ulpoadImageSuccess = $imageIcon->move($destinationPath, $imageIconName);
            $avatar = "/avatar/" . $imageIconName;
        }

        User::create([
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'email' => $data['user_email'],
            'tel' => $data['telephone'],
            'create_by' => session('id'),
            'delete_by' => session('id'),
            'reactive_by' => session('id'),
            'adresse' => $data['user_adresse'],
            'quartier' => $data['user_quartier'],
            'ville' => $data['user_ville'],
            'login' => $data['login'],
            'card_number' => $data['card_number'],
            'type_carte' => $data['type_carte'],
            'poste' => $data['poste'],
            'avatar' => $avatar,
            'token' => getRamdomText(20),
            'password' => bcrypt($data['password']),
            'id_operator' => $data['id_operator'],
            'type_user' => 2,

            'is_delete' => 0,

        ]);

        Journal::create([
            'action' => $data['first_name'] . " " . $data['last_name'] . "Ajouter comme nouveau chef d'operator (" . $data['name_operator'] . ") ",
            'user_id' => session('id'),
        ]);
        return redirect('liste_operator')->with('flash_message_success', "Nouveau chef de cette operator ajouter avec succès!");
    }
}
