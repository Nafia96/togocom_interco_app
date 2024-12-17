<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\Agent;
use App\Models\Client;
use App\Models\Comptes;
use App\Models\Journal;
use App\Models\Operation;
use App\Http\Controllers\ComptesController;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    //

    public function add_client()
    {
        //$code_barre = generateRandomString(10);
        return view('client.add_client');
    }

    public function client_register(Request $request)
    {

        //   dd($request['code_barre']);

        $request->validate([
            'tel1' => 'required|string',
            'tel_b' => 'required|string',
            'card_number' => 'nullable|string',

            'email' => 'nullable|email',
            'adresse' => 'nullable|string',
            'quartier' => 'required|string',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string',
            'login' => 'required|unique:users|string',
            'password' => 'required|confirmed',
            'adresse_collecte' => 'required|string',
            'occupation' => 'required|string',
            'type_client' => 'nullable|string',

        ]);

        $data = $request->all();

        $avatar = "/avatar/default.png";
        if ($request->hasfile('avatar')) {
            $imageIcon = $request->file('avatar');
            $exten = $imageIcon->getClientOriginalExtension();
            $imageIconName = $request->nom . uniqid() . '.' . $exten;
            $destinationPath = public_path('/avatar');
            $ulpoadImageSuccess = $imageIcon->move($destinationPath, $imageIconName);
            $avatar = "/avatar/" . $imageIconName;
        }

        //dd($data);

        $user = User::create([
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'email' => $data['email'],
            'tel' => $data['tel1'],
            'create_by' => session('id'),
            'delete_by' => session('id'),
            'reactive_by' => session('id'),
            'adresse' => $data['adresse'],
            'quartier' => $data['quartier'],
            'ville' => $data['ville'],
            'login' => $data['login'],
            'id_agence'=>session('id_agence'),
            'card_number' => $data['card_number'],
            'type_carte' => $data['type_carte'],
            'avatar' => $avatar,
            'token' => getRamdomText(20),
            'password' => bcrypt($data['password']),
            'type_user' => 4,

            'is_delete' => 0,

        ]);



        $user_actif= User::where(['id' => session('id')])->first();

      //  dd($chef_agence->agence->id);



        Client::create([
            'id_user' => $user->id,
            'id_agence' => $user_actif->agence->id,
            'type_of_add_by'=>$user_actif->type_user,
            'id_agent'=>session('id'),

            'occupation' => $data['occupation'],
            'type_client' => $data['type_client'],
            'adresse_collecte' => $data['adresse_collecte'],
            'tel_b' => $data['tel_b'],
            'is_delete' => 0,

        ]);



        Journal::create([
            'action' => "Création de l' client " . $data['first_name'],
            'user_id' => session('id'),
        ]);



        return redirect('liste_client')->with('flash_message_success', 'Client ajouté avec succès!');

    }

    public function liste_client()
    {
        if (getUserType()->type_user == 2 ){




        $clients = Client::where(['is_delete'=> 0, 'id_agence'=>session('id_agence')])
            ->orderBy('created_at', 'DESC')
            ->get();



        //  dd($clients[1]->user->last_name);
        return view('client.liste_client', compact('clients'));
    }elseif (getUserType()->type_user == 3 ){
      return   redirect('liste_client_agent');
    }
    }


    public function liste_client_agent()
    {

        $clients = Client::where(['is_delete'=> 0,
         'id_agence'=>session('id_agence'),
         'id_agent'=>session('id'),

         ])
            ->orderBy('created_at', 'DESC')
            ->get();



        //  dd($clients[1]->user->last_name);
        return view('client.liste_client', compact('clients'));
    }

    public function delete_client_liste()
    {

        $clients = Client::where(['is_delete'=> 1, 'id_agence'=>session('id_agence')])
            ->orderBy('updated_at', 'DESC')
            ->get();

        //  dd($clients[1]->user->last_name);
        return view('client.liste_client_supprime', compact('clients'));
    }



    public function update_client($id)
    {
        $client = Client::where('id', $id)->first();

        return view('client.update_client', compact('client'));
    }

    public function save_update(Request $request)
    {

        $request->validate([
            'tel1' => 'required|string',
            'email' => 'required|email',
            'adresse' => 'nullable|string',
            'quartier' => 'required|string',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string',

            'adresse_collecte' => 'required|string',
            'occupation' => 'required|string',
            'type_client' => 'nullable|string',
            'tel_b' => 'required|string',

        ]);

        $data = $request->all();

        if ($request->hasfile('avatar')) {
            $imageIcon = $request->file('avatar');
            $exten = $imageIcon->getClientOriginalExtension();
            $imageIconName = $request->nom . uniqid() . '.' . $exten;
            $destinationPath = public_path('/avatar');
            $ulpoadImageSuccess = $imageIcon->move($destinationPath, $imageIconName);
            $avatar = "/avatar/" . $imageIconName;

            User::where(['id' => $data['id']])->update([
                'last_name' => $data['last_name'],
                'first_name' => $data['first_name'],
                'email' => $data['email'],
                'tel' => $data['tel1'],
                'adresse' => $data['adresse'],
                'quartier' => $data['quartier'],
                'ville' => $data['ville'],


                 'avatar' => $avatar,


            ]);

            Client ::where(['id' => $data['client_id']])->update([

                'occupation' => $data['occupation'],
                'type_client' => $data['type_client'],
                'adresse_collecte' => $data['adresse_collecte'],
                'tel_b' => $data['tel_b'],



            ]);



        } else {

            User::where(['id' => $data['id']])->update([
                'last_name' => $data['last_name'],
                'first_name' => $data['first_name'],
                'email' => $data['email'],
                'tel' => $data['tel1'],
                'adresse' => $data['adresse'],
                'quartier' => $data['quartier'],
                'ville' => $data['ville'],



            ]);

            Client ::where(['id' => $data['client_id']])->update([


                'occupation' => $data['occupation'],
                'type_client' => $data['type_client'],
                'adresse_collecte' => $data['adresse_collecte'],
                'tel_b' => $data['tel_b'],




            ]);
        }

        Journal::create([
            'action' => "Mise à jours de du client  " . $data['first_name'],
            'user_id' => session('id'),
        ]);

        return redirect('liste_client')->with('flash_message_success', "Client mise à jours avec succès!");
    }

    public function delete_client($id)
    {
        $client = Client::where(['id' => $id])->first();
        Client::where(['id' => $id])->update([
            'is_delete' => 1,
        ]);

        $compteController = new ComptesController();

        $client_comptes = Comptes::where(['id_client'=> $id])

            ->get();

        foreach($client_comptes as $compte){

            $compteController->delete_tontine($compte->id);

        }


        Journal::create([
            'action' => "Suppression du client " . $client->user->first_name,
            'user_id' => session('id'),
        ]);
        return redirect()->back()->with('flash_message_success', 'Client supprimé avec succès!');

    }

    public function activate_client($id)
    {
        $client = Client::where(['id' => $id])->first();
        Client::where(['id' => $id])->update([
            'is_delete' => 0,
        ]);

        $compteController = new ComptesController();

        $client_comptes = Comptes::where(['id_client'=> $id])

            ->get();

        foreach($client_comptes as $compte){

            $compteController->activate_tontine($compte->id);

        }

        Journal::create([
            'action' => "Réactivation du client " . $client->user->first_name,
            'user_id' => session('id'),
        ]);
        return redirect()->back()->with('flash_message_success', 'Client réactivée avec succès!');
    }

    public function client_dashboard()
    {

        $user = User::where(['id' => session('id')])->first();
        $client = Client::where(['id_user' => session('id')])->first();
        $id_agence = session('id_agence');
        $id_client = $client->id;

        $monAgents = Agent::where(['id_user' => $user->add_by])->get();
        $mes_comptes = Comptes::where(['id_agence' => $id_agence, 'is_delete' => 0])->get();
        $agence = Agence::where(['id' => $id_agence])->first();

        $solde_total_acutelle = Operation::where(['id_client'=> $id_client,'is_delete'=> 0])->sum('entre');
        $solde_total_sortie= Operation::where(['id_client'=> $id_client,'is_delete'=> 0])->sum('sortie');
        $solde_total_bene_tont= Operation::where(['id_client'=> $id_client,'type_operation'=> 'tontine','is_delete'=> 0])->sum('benefice');
        $solde_total_bene_epar= Operation::where(['id_client'=> $id_client,'type_operation'=> 'epargne','is_delete'=> 0])->sum('benefice');
        $solde_total_bene_new_epar= Operation::where(['id_client'=> $id_client,'type_operation'=> 'new','is_delete'=> 0])->sum('benefice');
        $solde_total_bene_cotisation= Operation::where(['id_client'=> $id_client,'type_operation'=> 'cotisation','is_delete'=> 0])->sum('benefice');
        $solde_total_carnet= Operation::where(['id_client'=> $id_client,'type_operation'=> 'carnet','is_delete'=> 0])->sum('entre');
        $solde_total_acutelle -=$solde_total_sortie;
        $solde_total_acutelle -=$solde_total_bene_tont;
        $solde_total_acutelle -=$solde_total_bene_epar;
        $solde_total_acutelle -=$solde_total_bene_cotisation;
        $solde_total_acutelle -=$solde_total_carnet;
        $solde_total_acutelle -=$solde_total_bene_new_epar;

        $epargne_acutelle = Operation::where(['id_client'=> $id_client,'is_delete'=> 0,'type_compte'=>'epargne','type_operation'=>'depot'])->sum('entre');
        $epargne_acutelle_new = Operation::where(['id_client'=> $id_client,'is_delete'=> 0,'type_compte'=>'epargne','type_operation'=>'new'])->sum('entre');
        $epargne_acutelle_bene = Operation::where(['id_client'=> $id_client,'is_delete'=> 0,'type_operation'=>'new'])->sum('benefice');
        $epargne_sortie = Operation::where(['id_client'=> $id_client,'is_delete'=> 0,'type_compte'=>'epargne'])->sum('sortie');
        $epargne_acutelle += $epargne_acutelle_new;
        $epargne_acutelle -= $epargne_sortie;
        $epargne_acutelle -= $solde_total_bene_epar;
        $epargne_acutelle -= $epargne_acutelle_bene;


        $tontine_acutelle = Operation::where(['id_client'=> $id_client,'is_delete'=> 0,'type_compte'=>'tontine','type_operation'=>'cotisation'])->sum('entre');
        $tontine_sortie= Operation::where(['id_client'=> $id_client,'is_delete'=> 0,'type_compte'=>'tontine'])->sum('sortie');
        //$tontine_bene= Operation::where(['id_client'=> $id_client,'is_delete'=> 0,'type_compte'=>'tontine'])->sum('benefice');
        $tontine_acutelle -= $tontine_sortie;
        $tontine_acutelle -= $solde_total_bene_tont;
        $tontine_acutelle -= $solde_total_bene_cotisation;


        $solde_sortie = Operation::where(['id_client'=> $id_client,'is_delete'=>0, 'type_operation' => 'retrait' ] )->sum('sortie');
      //  $mes_operations = Operation::where(['id_client'=> $id_client,'is_delete'=>0 ])->orderBy('updated_at', 'DESC')
      //  ->get();

        $mes_operations = Operation::whereId_client($id_client)
        ->whereYear('created_at', '=', date('Y'))
        ->whereMonth('created_at', '=', date('m'))
        ->whereDay('created_at', '=', date('d'))
        ->whereId_client($id_client)
        ->whereIs_delete(0)
        ->orderBy('updated_at', 'DESC')
        ->get();



        return view('client.client_dashboard', compact('epargne_acutelle','tontine_acutelle',   'mes_operations','user','client','id_agence','monAgents','solde_total_acutelle','solde_sortie'));


    }
}
