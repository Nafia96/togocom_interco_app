<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Agent;
use App\Models\Client;
use App\Models\Account;
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
        
        $operator = Operator::where('id', $id_operator)->first();
        $op_account = Account::where('id_operator', $operator->id)->first();

        $resums = Resum::where(['id_operator' => $id_operator, 'is_delete' => 0])
        ->orderBy('updated_at', 'DESC')
        ->get();

    $operations = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0])
        ->orderBy('updated_at', 'DESC')
        ->get();

        return view('operator/ope_dashboard', compact('operations', 'op_account','resums', 'operator'))->render();

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
            'is_carrier' => $data['is_carrier'],
            'cedeao' => $data['cedeao'],
            'afrique' => $data['afrique'],
            'description' => $data['description'],

        ]);

            Account::create([
            'id_operator' => $operator->id,
            'account_number' =>$operator->id.'55',
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
        return view('operator.liste_operator', compact('operators'));
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


        $nbre_carnet = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0,'type_operation'=>'carnet'])->count();
        $solde_carnet_entre = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0,'type_operation'=>'carnet'])->sum('entre');
        $solde_benefice_carnet = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0,'type_operation'=>'carnet'])->sum('benefice');

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


        $tontine_entre = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0,'type_operation'=>'cotisation'])->sum('entre');

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



        $epargne_entre_depot = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0,'type_operation'=>'depot'])->sum('entre');
        $epargne_entre_new = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0,'type_operation'=>'new'])->sum('entre');

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

            $epargne_entre =$epargne_entre- $epargne_sortie;

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

            $solde_operator = $entre - $sortie -$versement;

            $entre -= $sortie;


        return view('operator.operator_stastic_dashboard', compact('epargne_benefice_today','epargne_sortie_today','tontine_sortie_today','nbre_carnet','solde_carnet','versement', 'solde_operator', 'solde_entre_today', 'epargne_entre', 'epargne_entre_today', 'tontine_entre_today', 'tontine_entre', 'nbr_comptes', 'mesClients', 'mesAgents', 'operations', 'operator', 'sortie', 'entre', 'benefice_total', 'sortie_today', 'entre_today'));

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


        $nbre_carnet = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0,'type_operation'=>'carnet'])->count();
        $solde_carnet_entre = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0,'type_operation'=>'carnet'])->sum('entre');
        $solde_benefice_carnet = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0,'type_operation'=>'carnet'])->sum('benefice');

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


        $tontine_entre = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0,'type_operation'=>'cotisation'])->sum('entre');

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



        $epargne_entre_depot = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0,'type_operation'=>'depot'])->sum('entre');
        $epargne_entre_new = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0,'type_operation'=>'new'])->sum('entre');

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

            $epargne_entre =$epargne_entre- $epargne_sortie;

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

            $solde_operator = $entre - $sortie -$versement;

            $entre -= $sortie;


        return view('operator.operator_dashboard', compact('epargne_benefice_today','epargne_sortie_today','tontine_sortie_today','nbre_carnet','solde_carnet','versement', 'solde_operator', 'solde_entre_today', 'epargne_entre', 'epargne_entre_today', 'tontine_entre_today', 'tontine_entre', 'nbr_comptes', 'mesClients', 'mesAgents', 'operations', 'operator', 'sortie', 'entre', 'benefice', 'sortie_today', 'entre_today'));
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
