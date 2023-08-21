<?php

namespace App\Http\Controllers;
use App\Models\Agent;
use App\Models\Client;
use App\Models\Comptes;
use App\Models\Journal;
use App\Models\Operation;
use App\Models\User;


use Illuminate\Http\Request;

class AgentBureauxController extends Controller
{
    //

    public function add_agent_br()
    {
        //$code_barre = generateRandomString(10);
        return view('agent_bureaux/add_agent');
    }

    public function agentadd_agent_br_register(Request $request)
    {

        //   dd($request['code_barre']);

        $request->validate([
            'tel1' => 'required|string',
            'email' => 'required|unique:users|email',
            'card_number' => 'required|string',

            'adresse' => 'nullable|string',
            'quartier' => 'required|string',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string',
            'login' => 'required|unique:users|string',
            'password' => 'required|confirmed',
            'date_naissance' => 'required|string',
            'zone' => 'required|string',
            'type_contrat' => 'nullable|string',

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
            'avatar' => $avatar,
            'card_number' => $data['card_number'],
            'type_carte' => $data['type_carte'],
            'token' => getRamdomText(20),
            'password' => bcrypt($data['password']),
            //Agent de bureau prend le user 6
            'type_user' => 6,

            'is_delete' => 0,

        ]);

        $chef_agence = User::where(['id' => session('id')])->first();

        //dd($chef_agence->agence->id);

        Agent::create([
            'id_user' => $user->id,
            'id_agence' => $chef_agence->agence->id,
            'diplome' => $data['diplome'],
            'date_naissance' => $data['date_naissance'],
            'zone' => $data['zone'],
            'type_contrat' => $data['type_contrat'],
            'is_delete' => 0,

        ]);

        Journal::create([
            'action' => "Création de l' agent " . $data['first_name'],
            'user_id' => session('id'),
        ]);

        return redirect('liste_agent_br')->with('flash_message_success', 'Agent de bureau ajouté avec succès!');

    }

    public function liste_agent_br()
    {

        $agents = Agent::where(['is_delete'=> 0, 'id_agence'=>session('id_agence')])
            ->orderBy('created_at', 'DESC')
            ->get();

        //  dd($agents[1]->user->last_name);
        return view('agent_bureaux.liste_agents_br', compact('agents'));
    }

    public function delete_agent_br_liste()
    {

        $agents = Agent::where(['is_delete'=> 1, 'id_agence'=>session('id_agence')])
            ->orderBy('updated_at', 'DESC')
            ->get();

        //  dd($agents[1]->user->last_name);
        return view('agents.liste_agents_supprime', compact('agents'));
    }

    public function info_agence($id)
    {

        $agence = Agent::where('id', $id)->get();
        $chef_agence = User::where('id', $agence->id_chef_agence)->get();

        return view('agence.info_agence', compact('agence', 'chef_agence'));
    }

    public function update_agent($id)
    {
        $agent = Agent::where('id', $id)->first();

        return view('agents.update_agent', compact('agent'));
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

            'date_naissance' => 'required|string',
            'zone' => 'required|string',
            'type_contrat' => 'nullable|string',
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


                 'avatar' => $avatar,


            ]);

            Agent ::where(['id' => $data['agent_id']])->update([

            'diplome' => $data['diplome'],
            'date_naissance' => $data['date_naissance'],
            'zone' => $data['zone'],
            'type_contrat' => $data['type_contrat'],




            ]);



        } else {

            User::where(['id' => $data['id']])->update([
                'last_name' => $data['last_name'],
                'first_name' => $data['first_name'],
                'email' => $data['email'],
                'tel' => $data['tel1'],
                'adresse' => $data['adresse'],
                'quartier' => $data['quartier'],



            ]);

            Agent ::where(['id' => $data['agent_id']])->update([

            'diplome' => $data['diplome'],
            'date_naissance' => $data['date_naissance'],
            'zone' => $data['zone'],
            'type_contrat' => $data['type_contrat'],




            ]);
        }

        Journal::create([
            'action' => "Mise à jours de l\'agent  " . $data['first_name'],
            'user_id' => session('id'),
        ]);

        return redirect('liste_agent')->with('flash_message_success', "Agent mise à jours avec succès!");
    }

    public function delete_agent($id)
    {
        $agent = Agent::where(['id' => $id])->first();
        Agent::where(['id' => $id])->update([
            'is_delete' => 1,
        ]);

        Journal::create([
            'action' => "Suppression de l'agent " . $agent->user->first_name,
            'user_id' => session('id'),
        ]);
        return redirect()->back()->with('flash_message_success', 'Agent supprimé avec succès!');
    }

    public function activate_agent($id)
    {
        $agent = Agent::where(['id' => $id])->first();
        Agent::where(['id' => $id])->update([
            'is_delete' => 0,
        ]);

        Journal::create([
            'action' => "Réactivation de l'agent " . $agent->user->first_name,
            'user_id' => session('id'),
        ]);
        return redirect()->back()->with('flash_message_success', 'Agent réactivée avec succès!');
    }



    public function agent_dashboard()
    {


        $user = User::where(['id' => session('id')])->first();
        $id_agence = session('id_agence');

        $mesClients = Client::where(['id_agence' => $id_agence, 'is_delete' => 0,'id_agent'=>session('id'),
        ])->get()->count();
        $nbr_comptes = Comptes::where(['id_agence' => $id_agence, 'is_delete' => 0,'add_by_id' => session('id'),
        ])->get()->count();

        $operations = Operation::whereId_agence(session('id_agence'))
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereDay('created_at', '=', date('d'))
            ->whereAdd_by(session('id'))
            ->whereIs_delete(0)
            ->orderBy('updated_at', 'DESC')
            ->get();



            $entre = Operation::where(['id_agence'=> $id_agence,'add_by' => session('id'),
            'is_delete' => 0])->sum('tous_entre');



            $entre_today = Operation::whereId_agence(session('id_agence'))
            ->whereAdd_by(session('id'))
            ->whereIs_delete(0)
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereDay('created_at', '=', date('d'))
            ->sum('tous_entre')
         ;


            $tontine_entre = Operation::whereId_agence(session('id_agence'))
            ->whereAdd_by(session('id'))
            ->whereIs_delete(0)
            ->sum('tous_entre');

            $tontine_entre_today = Operation::whereId_agence(session('id_agence'))
            ->whereAdd_by(session('id'))
            ->whereIs_delete(0)
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereDay('created_at', '=', date('d'))
            ->sum('tous_entre');



            $solde_entre = Operation::whereId_agence(session('id_agence'))
            ->whereAdd_by(session('id'))
            ->whereIs_delete(0)
            ->sum('tous_entre');



            $solde_entre_today = Operation::whereId_agence(session('id_agence'))
            ->whereAdd_by(session('id'))
            ->whereIs_delete(0)
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereDay('created_at', '=', date('d'))
            ->sum('tous_entre');


        return view('agents.agent_dashboard', compact('solde_entre','solde_entre_today','tontine_entre_today','tontine_entre','nbr_comptes','mesClients',  'operations', 'entre','entre_today'));

    }





    public function agent_statistique($id_agent_get)
    {



        $user = User::where(['id' => $id_agent_get])->first();
        $id_agence = session('id_agence');
        $id_agent = $user->id;

        $mesClients = Client::where(['id_agence' => $id_agence, 'is_delete' => 0,'id_agent'=>$id_agent,
        ])->get()->count();

        $clients = Client::where(['id_agence' => $id_agence, 'is_delete' => 0,'id_agent'=>$id_agent,
        ])->get();

        $nbr_comptes = Comptes::where(['id_agence' => $id_agence, 'is_delete' => 0,'add_by_id' => $id_agent,
        ])->get()->count();

        $comptes = Comptes::where(['id_agence' => $id_agence, 'is_delete' => 0,'add_by_id' => $id_agent,
        ])->get();

        $operations = Operation::whereId_agence(session('id_agence'))
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereDay('created_at', '=', date('d'))
            ->whereAdd_by($id_agent)
            ->whereIs_delete(0)
            ->orderBy('updated_at', 'DESC')
            ->get();



            $entre = Operation::where(['id_agence'=> $id_agence,'add_by' => $id_agent,
            'is_delete' => 0])->sum('tous_entre');



            $entre_today = Operation::whereId_agence(session('id_agence'))
            ->whereAdd_by($id_agent)
            ->whereIs_delete(0)
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereDay('created_at', '=', date('d'))
            ->sum('tous_entre')
         ;


            $tontine_entre = Operation::whereId_agence(session('id_agence'))
            ->whereAdd_by($id_agent)
            ->whereIs_delete(0)
            ->sum('tous_entre');

            $tontine_entre_today = Operation::whereId_agence(session('id_agence'))
            ->whereAdd_by($id_agent)
            ->whereIs_delete(0)
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereDay('created_at', '=', date('d'))
            ->sum('tous_entre');



            $solde_entre = Operation::whereId_agence(session('id_agence'))
            ->whereAdd_by($id_agent)
            ->whereIs_delete(0)
            ->sum('tous_entre');



            $solde_entre_today = Operation::whereId_agence(session('id_agence'))
            ->whereAdd_by($id_agent)
            ->whereIs_delete(0)
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereDay('created_at', '=', date('d'))
            ->sum('tous_entre');


        return view('agents.agent_statistique_dashboard', compact('comptes','clients','user','solde_entre','solde_entre_today','tontine_entre_today','tontine_entre','nbr_comptes','mesClients',  'operations', 'entre','entre_today'));

    }

}
