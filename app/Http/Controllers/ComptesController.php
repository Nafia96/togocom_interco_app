<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\Client;
use App\Models\Comptes;
use App\Models\Journal;
use App\Models\Operation;
use App\Models\User;
use Illuminate\Http\Request;

class ComptesController extends Controller
{
    //Tontine controlleur



    public function add_tontine()
    {
        $clients = Client::where(['is_delete' => 0, 'id_agence' => session('id_agence')])
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('comptes.add_tontine', compact('clients'))->render();
    }

    public function tontine_register(Request $request)
    {



        $request->validate([
            'start_date' => 'required|date',
            'client_id' => 'required',
            'taux_cotisation' => 'required',
            'description' => 'nullable|string',

        ]);

        $data = $request->all();

        //dd($data);

        $last_insert_compte = Comptes::where('type_compte', 'tontine')
            ->orderBy('updated_at', 'DESC')
            ->first();

        //dd($last_insert_compte->compte);

        $compte_num = $last_insert_compte->compte + 1;



        $compte = Comptes::create([
            'debut_de_cotisation' => $data['start_date'],
            'id_client' => $data['client_id'],
            'taux_cotisation' => $data['taux_cotisation'],
            'description' => $data['description'],
            'create_by' => session('id'),
            'add_by_id' => session('id'),


            'delete_by' => session('id'),
            'reactive_by' => session('id'),
            'id_agence' => session('id_agence'),
            'account_number' => tontineAccountNumber(),
            'type_compte' => 'tontine',
            'compte' => $compte_num,

            'is_delete' => 0,

        ]);



        Journal::create([
            'action' => "Création de compte" . tontineAccountNumber(),
            'user_id' => session('id'),
        ]);



        return redirect('liste_tontine')->with('flash_message_success', 'Compte tontine ajouté avec succès!');
    }

    public function liste_tontine()
    {
        if (getUserType()->type_user == 2) {
            $comptes = Comptes::where([
                'is_delete' => 0,
                'type_compte' => 'tontine',
                'id_agence' => session('id_agence')
            ])
                ->orderBy('created_at', 'DESC')
                ->get();



            //  dd($clients[1]->user->last_name);
            return view('comptes.liste_tontine', compact('comptes'));
        } elseif (getUserType()->type_user == 3) {
            return   redirect('liste_tontine_agent');
        }elseif (getUserType()->type_user == 4) {
            return   redirect('liste_tontine_client');
        }
    }


    public function liste_tontine_agent()
    {

        $comptes = Comptes::where([
            'is_delete' => 0,
            'type_compte' => 'tontine',
            'id_agence' => session('id_agence'),
            'add_by_id' => session('id'),
        ])
            ->orderBy('created_at', 'DESC')
            ->get();



        //  dd($clients[1]->user->last_name);
        return view('comptes.liste_tontine', compact('comptes'));
    }


    public function liste_tontine_client()
    {
        $user = User::where(['id' => session('id')])->first();
        $client = Client::where(['id_user' => session('id')])->first();
        $id_client = $client->id;

        $comptes = Comptes::where([
            'is_delete' => 0,
            'type_compte' => 'tontine',
            'id_client' => $id_client
        ])
            ->orderBy('created_at', 'DESC')
            ->get();

            $gestionaire_compte = null;
            $agence = null;

            if(!$comptes->isEmpty())
            {

            $gestionaire_compte_id = $comptes[0]->add_by_id;

            $gestionaire_compte = User::where(['id' => $gestionaire_compte_id])->first();
            $agence = Agence::where(['id' => $client->id_agence])->first();

            }


        return view('client.client_tontine_liste', compact('comptes','gestionaire_compte','agence'));
    }


    public function liste_epargne_client()
    {
        $user = User::where(['id' => session('id')])->first();
        $client = Client::where(['id_user' => session('id')])->first();
        $id_client = $client->id;

        $comptes = Comptes::where([
            'is_delete' => 0,
            'type_compte' => 'epargne',
            'id_client' => $id_client
        ])
            ->orderBy('created_at', 'DESC')
            ->get();


            $gestionaire_compte = null;
            $agence = null;

            if(!$comptes->isEmpty())
            {

            $gestionaire_compte_id = $comptes[0]->add_by_id;

            $gestionaire_compte = User::where(['id' => $gestionaire_compte_id])->first();
            $agence = Agence::where(['id' => $client->id_agence])->first();

            }



        return view('client.liste_epargne_client', compact('comptes','gestionaire_compte','agence'));
    }

    public function delete_tontine_liste()
    {

        $comptes = Comptes::where([
            'is_delete' => 1,
            'type_compte' => 'tontine',
            'id_agence' => session('id_agence')
        ])
            ->orderBy('updated_at', 'DESC')
            ->get();

        //  dd($clients[1]->user->last_name);
        return view('comptes.liste_tontines_supprime', compact('comptes'));
    }



    public function update_tontine($id)
    {
        $compte = Comptes::where('id', $id)->first();

        $clients = Client::where('is_delete', 0)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('comptes.update_tontine', compact('compte', 'clients'));
    }

    public function save_tontine_update(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'taux_cotisation' => 'required',
            'description' => 'nullable|string',

        ]);

        $data = $request->all();

        //dd($data);
        $compte = Comptes::where('id', $data['id_compte'])->first();


        Comptes::where(['id' => $data['id_compte']])->update([
            'debut_de_cotisation' => $data['start_date'],
            'taux_cotisation' => $data['taux_cotisation'],
            'description' => $data['description'],


        ]);



        Journal::create([
            'action' => "Modification de compte" . $compte->account_number,
            'user_id' => session('id'),
        ]);



        return redirect('liste_tontine')->with('flash_message_success', 'Compte tontine modifié avec succès!');
    }

    public function delete_tontine($id)
    {
        $compte = Comptes::where(['id' => $id])->first();
        Comptes::where(['id' => $id])->update([
            'is_delete' => 1,
        ]);


        $operations_compte = Operation::where('id_compte',$id)->get();

        foreach($operations_compte as $operation){
            Operation::where('id_compte',$id)
            ->update(['is_delete'=>1]);
        }

        Journal::create([
            'action' => "Suppression du compte " . $compte->account_number,
            'user_id' => session('id'),
        ]);
        return redirect()->back()->with('flash_message_success', 'Compte supprimé avec succès!');
    }

    public function activate_tontine($id)
    {
        $compte = Comptes::where(['id' => $id])->first();
        Comptes::where(['id' => $id])->update([
            'is_delete' => 0,
        ]);

        $operations_compte = Operation::where('id_compte',$id)->get();

        foreach($operations_compte as $operation){
            Operation::where('id_compte',$id)
            ->update(['is_delete'=>0]);
        }

        Journal::create([
            'action' => "Réactivation du compte " . $compte->account_number,
            'user_id' => session('id'),
        ]);
        return redirect()->back()->with('flash_message_success', 'Compte réactivée avec succès!');
    }

    //End Tontine controlleur


    //Epargne controlleur


    public function add_epargne()
    {
        $clients = Client::where('is_delete', 0)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('comptes.add_epargne', compact('clients'))->render();;
    }

    public function epargne_register(Request $request)
    {



        $request->validate([
            'start_date' => 'required|date',
            'client_id' => 'required',
            'somme' => 'required|numeric',
            'description' => 'nullable|string',

        ]);

        $data = $request->all();

        //dd($data);

        if ($data['somme'] >= 5500) {


            $solde_actuel = $data['somme'] - 5500;


            $compte = Comptes::create([
                'debut_de_cotisation' => $data['start_date'],
                'id_client' => $data['client_id'],
                'solde_actuelle' => $solde_actuel,
                'solde_total' => $solde_actuel,
                'beniefice_accumule' => 5500,

                'description' => $data['description'],
                'create_by' => session('id'),
                'add_by_id' => session('id'),


                'delete_by' => session('id'),
                'reactive_by' => session('id'),
                'id_agence' => session('id_agence'),
                'account_number' => epargneAccountNumber(),
                'type_compte' => 'epargne',
                'compte' => generateAccountNumber(),

                'is_delete' => 0,

            ]);







            Journal::create([
                'action' => "Création de compte" . epargneAccountNumber(),
                'user_id' => session('id'),
            ]);



            $agence = Agence::where('id', session('id_agence'))->first();


            $operation = Operation::create([
                'libelle_operation' => "Ouverture du compte epargne",

                'type_operation' => 'new',
                'entre' =>  $data['somme'],
                'tous_entre' =>  $data['somme'],
                'sortie' => 0,
                'benefice' => 5500,
                'solde_restant' => $solde_actuel,
                'account_number' => $compte->account_number,
                'add_by' => session('id'),
                'id_compte' => $compte->id,
                'type_compte' => $compte->type_compte,
                'id_client' => $data['client_id'],
                'id_agence' => session('id_agence'),
            ]);


            $user = $compte->client->user;

            Journal::create([
                'action' => "Ouverture du compte épargne avec un depot de " . $solde_actuel . ", numeros du compte: " . $compte->account_number . ", client: " . $user->first_name . " " . $user->last_name,
                'user_id' => session('id'),
            ]);


            $entre_agence = Operation::where(['id_agence' => $agence->id_agence, 'is_delete' => 0])->sum('entre');

            if ($entre_agence >= $agence->quota_maximum) {

                return redirect('liste_epargne')->with('flash_message_success', 'Vous avez atteint votre quotta maximum pour effectuer votre versement!');
            }


            return redirect('liste_epargne')->with('flash_message_success', 'Compte épargne ajouté avec succès!');
        } else {


            return redirect()->back()->with('flash_message_error', 'Le montant à épargner est insuffisant pour effectuer cette opération!');
        }
    }



    public function liste_epargne()
    {
if(session('type_user') == 2){
    $comptes = Comptes::where([
        'is_delete' => 0,
        'type_compte' => 'epargne',
        'id_agence' => session('id_agence')
    ])
        ->orderBy('created_at', 'DESC')
        ->get();



    //  dd($clients[1]->user->last_name);
    return view('comptes.liste_epargne', compact('comptes'));
}else{
    return redirect('liste_epargne_agent');
}

    }

    public function liste_epargne_agent()
    {

        $comptes = Comptes::where([
            'is_delete' => 0,
            'type_compte' => 'epargne',
            'id_agence' => session('id_agence'),
            'add_by_id' => session('id')
        ])
            ->orderBy('created_at', 'DESC')
            ->get();



        //  dd($clients[1]->user->last_name);
        return view('comptes.liste_epargne', compact('comptes'));
    }

    public function delete_epargne_liste()
    {

        $comptes = Comptes::where([
            'is_delete' => 1,
            'type_compte' => 'epargne',
            'id_agence' => session('id_agence')
        ])
            ->orderBy('updated_at', 'DESC')
            ->get();

        //  dd($clients[1]->user->last_name);
        return view('comptes.liste_epargnes_supprime', compact('comptes'));
    }



    public function update_epargne($id)
    {
        $compte = Comptes::where('id', $id)->first();

        $clients = Client::where('is_delete', 0)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('comptes.update_epargne', compact('compte', 'clients'));
    }

    public function save_epargne_update(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'epargne' => 'required',
            'description' => 'nullable|string',

        ]);

        $data = $request->all();

        $compte = Comptes::where('id', $data['id_compte'])->first();


        Comptes::where(['id' => $data['id_compte']])->update([
            'debut_de_cotisation' => $data['start_date'],
            'solde_actuelle' => $data['epargne'],
            'solde_total' => $data['epargne'],
            'description' => $data['description'],


        ]);



        Journal::create([
            'action' => "Modification de compte" . $compte->account_number,
            'user_id' => session('id'),
        ]);



        return redirect('liste_epargne')->with('flash_message_success', 'Compte epargne modifié avec succès!');
    }

    public function delete_epargne($id)
    {
        $compte = Comptes::where(['id' => $id])->first();
        Comptes::where(['id' => $id])->update([
            'is_delete' => 1,
        ]);

        $operations_compte = Operation::where('id_compte',$id)->get();

        foreach($operations_compte as $operation){
            Operation::where('id_compte',$id)
            ->update(['is_delete'=>1]);
        }

        Journal::create([
            'action' => "Suppression du compte " . $compte->account_number,
            'user_id' => session('id'),
        ]);
        return redirect()->back()->with('flash_message_success', 'Compte supprimé avec succès!');
    }

    public function activate_epargne($id)
    {
        $compte = Comptes::where(['id' => $id])->first();
        Comptes::where(['id' => $id])->update([
            'is_delete' => 0,
        ]);



        Journal::create([
            'action' => "Réactivation du compte " . $compte->account_number,
            'user_id' => session('id'),
        ]);
        return redirect()->back()->with('flash_message_success', 'Compte réactivée avec succès!');
    }




    //Salaire controlleur


    public function add_salaire()
    {
        $clients = Client::where('is_delete', 0)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('comptes.add_salaire', compact('clients'))->render();;
    }

    public function salaire_register(Request $request)
    {



        $request->validate([
            'start_date' => 'required|date',
            'client_id' => 'required',
            'epargne' => 'required',
            'description' => 'nullable|string',

        ]);

        $data = $request->all();

        //dd($data);


        $compte = Comptes::create([
            'debut_de_cotisation' => $data['start_date'],
            'id_client' => $data['client_id'],
            'solde_actuelle' => $data['epargne'],
            'solde_total' => $data['epargne'],

            'description' => $data['description'],
            'create_by' => session('id'),
            'add_by_id' => session('id'),


            'delete_by' => session('id'),
            'reactive_by' => session('id'),
            'id_agence' => session('id_agence'),
            'account_number' => salaireAccountNumber(),
            'type_compte' => 'salaire',
            'compte' => generateAccountNumber(),

            'is_delete' => 0,

        ]);



        Journal::create([
            'action' => "Création de compte" . salaireAccountNumber(),
            'user_id' => session('id'),
        ]);



        return redirect('liste_salaire')->with('flash_message_success', 'Compte salaire ajouté avec succès!');
    }

    public function liste_salaire()
    {

        $comptes = Comptes::where([
            'is_delete' => 0,
            'type_compte' => 'salaire',
            'id_agence' => session('id_agence')
        ])
            ->orderBy('created_at', 'DESC')
            ->get();



        //  dd($clients[1]->user->last_name);
        return view('comptes.liste_salaire', compact('comptes'));
    }

    public function delete_salaire_liste()
    {

        $comptes = Comptes::where([
            'is_delete' => 1,
            'type_compte' => 'salaire',
            'id_agence' => session('id_agence')
        ])
            ->orderBy('updated_at', 'DESC')
            ->get();

        //  dd($clients[1]->user->last_name);
        return view('comptes.liste_salaires_supprime', compact('comptes'));
    }



    public function update_salaire($id)
    {
        $compte = Comptes::where('id', $id)->first();

        $clients = Client::where('is_delete', 0)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('comptes.update_salaire', compact('compte', 'clients'));
    }

    public function save_salaire_update(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'epargne' => 'required',
            'description' => 'nullable|string',

        ]);

        $data = $request->all();

        //dd($data);
        $compte = Comptes::where('id', $data['id_compte'])->first();


        Comptes::where(['id' => $data['id_compte']])->update([
            'debut_de_cotisation' => $data['start_date'],
            'solde_actuelle' => $data['epargne'],
            'solde_total' => $data['epargne'],
            'description' => $data['description'],


        ]);



        Journal::create([
            'action' => "Modification de compte" . $compte->account_number,
            'user_id' => session('id'),
        ]);



        return redirect('liste_salaire')->with('flash_message_success', 'Compte salaire modifié avec succès!');
    }

    public function delete_salaire($id)
    {
        $compte = Comptes::where(['id' => $id])->first();
        Comptes::where(['id' => $id])->update([
            'is_delete' => 1,
        ]);

        Journal::create([
            'action' => "Suppression du compte " . $compte->account_number,
            'user_id' => session('id'),
        ]);
        return redirect()->back()->with('flash_message_success', 'Compte supprimé avec succès!');
    }

    public function activate_salaire($id)
    {
        $compte = Comptes::where(['id' => $id])->first();
        Comptes::where(['id' => $id])->update([
            'is_delete' => 0,
        ]);

        Journal::create([
            'action' => "Réactivation du compte " . $compte->account_number,
            'user_id' => session('id'),
        ]);
        return redirect()->back()->with('flash_message_success', 'Compte réactivée avec succès!');
    }




    //Depot Controller


    public function add_depot()
    {
        $clients = Client::where('is_delete', 0)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('comptes.add_depot', compact('clients'))->render();;
    }

    public function depot_register(Request $request)
    {



        $request->validate([
            'start_date' => 'required|date',
            'client_id' => 'required',
            'epargne' => 'required',
            'description' => 'nullable|string',

        ]);

        $data = $request->all();

        //dd($data);


        $compte = Comptes::create([
            'debut_de_cotisation' => $data['start_date'],
            'id_client' => $data['client_id'],
            'solde_actuelle' => $data['epargne'],
            'solde_total' => $data['epargne'],

            'description' => $data['description'],
            'create_by' => session('id'),
            'add_by_id' => session('id'),


            'delete_by' => session('id'),
            'reactive_by' => session('id'),
            'id_agence' => session('id_agence'),
            'account_number' => depotAccountNumber(),
            'type_compte' => 'depot',
            'compte' => generateAccountNumber(),

            'is_delete' => 0,

        ]);



        Journal::create([
            'action' => "Création de compte" . depotAccountNumber(),
            'user_id' => session('id'),
        ]);



        return redirect('liste_depot')->with('flash_message_success', 'Compte dépôt ajouté avec succès!');
    }

    public function liste_depot()
    {

        $comptes = Comptes::where([
            'is_delete' => 0,
            'type_compte' => 'depot',
            'id_agence' => session('id_agence')
        ])
            ->orderBy('created_at', 'DESC')
            ->get();



        //  dd($clients[1]->user->last_name);
        return view('comptes.liste_depot', compact('comptes'));
    }

    public function delete_depot_liste()
    {

        $comptes = Comptes::where([
            'is_delete' => 1,
            'type_compte' => 'depot',
            'id_agence' => session('id_agence')
        ])
            ->orderBy('updated_at', 'DESC')
            ->get();

        //  dd($clients[1]->user->last_name);
        return view('comptes.liste_depots_supprime', compact('comptes'));
    }



    public function update_depot($id)
    {
        $compte = Comptes::where('id', $id)->first();

        $clients = Client::where('is_delete', 0)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('comptes.update_depot', compact('compte', 'clients'));
    }

    public function save_depot_update(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'epargne' => 'required',
            'description' => 'nullable|string',

        ]);

        $data = $request->all();

        //dd($data);
        $compte = Comptes::where('id', $data['id_compte'])->first();


        Comptes::where(['id' => $data['id_compte']])->update([
            'debut_de_cotisation' => $data['start_date'],
            'solde_actuelle' => $data['epargne'],
            'solde_total' => $data['epargne'],
            'description' => $data['description'],


        ]);



        Journal::create([
            'action' => "Modification de compte" . $compte->account_number,
            'user_id' => session('id'),
        ]);



        return redirect('liste_depot')->with('flash_message_success', 'Compte dépôt modifié avec succès!');
    }

    public function delete_depot($id)
    {
        $compte = Comptes::where(['id' => $id])->first();
        Comptes::where(['id' => $id])->update([
            'is_delete' => 1,
        ]);

        Journal::create([
            'action' => "Suppression du compte " . $compte->account_number,
            'user_id' => session('id'),
        ]);
        return redirect()->back()->with('flash_message_success', 'Compte supprimé avec succès!');
    }

    public function activate_depot($id)
    {
        $compte = Comptes::where(['id' => $id])->first();
        Comptes::where(['id' => $id])->update([
            'is_delete' => 0,
        ]);

        Journal::create([
            'action' => "Réactivation du compte " . $compte->account_number,
            'user_id' => session('id'),
        ]);
        return redirect()->back()->with('flash_message_success', 'Compte réactivée avec succès!');
    }
}
