<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Agent;
use App\Models\Client;
use App\Models\Account;
use App\Models\Journal;
use App\Models\Operation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function add_user()
    {
        return view('user/add_user');
    }

    public function setting()
    {
        $operator = Operator::where('id', 1)->first();

        return view('setting', compact('operator'));

      
    }

    public function user_register(Request $request)
    {

        $request->validate([
            'password' => '|required|min:8|regex:/[@$.!%*#?&]/|confirmed',
            'password_confirmation' => 'required',
            'login' => '|required|unique:users|string|max:255',
            'last_name' => '|required|string|max:255',
            'first_name' => '|required|string|max:255',
            'post' => '|required|string|max:255',
            'tel' => 'required|string',
            'email' => 'required|email|unique:users',

        ]);


        $data = $request->all();

        $user = User::create([
            'password' => Hash::make($data['password']) ,
            'login' => $data['login'],
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'post' => $data['post'],
            'tel' => $data['tel'],
            'email' => $data['email'],
            'type_user' => $data['type_user'],
            'create_by' => session('id'),

        ]);

            

        Journal::create([
            'action' => "Création de l'utilisateur " . $data['last_name'].' '.$data['first_name'],
            'user_id' => session('id'),
        ]);

        return redirect('users_list')->with('flash_message_success', 'Nouveau utilisateur ajouté avec succès!');
    }

    public function users_list()
    {

        $users = User::where('is_delete', 0)
            ->orderBy('created_at', 'DESC')
            ->get();
        return view('user.users_list', compact('users'));
    }

    public function delete_user_liste()
    {

        $users = User::where('is_delete', 1)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('user.liste_users_supprime', compact('users'));
    }

    public function info_user($id)
    {

        $user = User::where('id', $id)->get();
        $chef_user = User::where('id', $user->id_chef_user)->get();

        return view('user.info_user', compact('user', 'chef_user'));
    }

    public function update_user($id)
    {
        $user = User::where('id', $id)->first();

        return view('user.update_user', compact('user'));
    }

    public function save_update_user(Request $request)
    {


        $request->validate([
      
            'last_name' => '|required|string|max:255',
            'first_name' => '|required|string|max:255',
            'post' => '|required|string|max:255',
            'tel' => 'required|string',
            'email' => 'required|email',

        ]);

        $data = $request->all();

            User::where(['id' => $data['id_user']])->update([
               
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'post' => $data['post'],
            'tel' => $data['tel'],
            'email' => $data['email'],
            'type_user' => $data['type_user'],
            'create_by' => session('id'),

            ]);

        Journal::create([
            'action' => "Mise à jours de l\'user  " . $data['last_name'].''.$data['first_name']  ,
            'user_id' => session('id'),
        ]);

        return redirect('users_list')->with('flash_message_success', "Utilisateur mise à jours avec succès!");
    }

    public function delete_user($id)
    {
        $user = User::where(['id' => $id])->first();
        User::where(['id' => $id])->update([
            'is_delete' => 1,
        ]);

        Journal::create([
            'action' => "Suppression de l'utilisateur " . $user->last_name.' '.$user->first_name,
            'user_id' => session('id'),
        ]);
        return redirect('delete_user_liste')->with('flash_message_success', 'utilisateur supprimé avec succès!');
    }

    public function activate_user($id)
    {
        $user = User::where(['id' => $id])->first();
        User::where(['id' => $id])->update([
            'is_delete' => 0,
        ]);

        Journal::create([
            'action' => "Réactivation de l'utilisateur " . $user->last_name.' '.$user->first_name,
            'user_id' => session('id'),
        ]);
        return redirect('users_list')->with('flash_message_success', 'utilisateur réactivé avec succès!');
    }


    public function user_statistique($id_user)
    {
        $user = User::where(['id' => session('id')])->first();
        $id_user = $id_user;

        $mesClients = Client::where(['id_user' => $id_user, 'is_delete' => 0])->get()->count();
        $mesAgents = Agent::where(['id_user' => $id_user, 'is_delete' => 0])->get()->count();
        $nbr_comptes = Account::where(['id_user' => $id_user, 'is_delete' => 0])->get()->count();
        $user = User::where(['id' => $id_user, 'is_delete' => 0])->first();

        $operations = Operation::whereId_user($id_user)
            ->whereYear('created_at', '=', date('Y'))
            ->whereDay('created_at', '=', date('d'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->orderBy('updated_at', 'DESC')
            ->get();


        $nbre_carnet = Operation::where(['id_user' => $id_user, 'is_delete' => 0,'type_operation'=>'carnet'])->count();
        $solde_carnet_entre = Operation::where(['id_user' => $id_user, 'is_delete' => 0,'type_operation'=>'carnet'])->sum('entre');
        $solde_benefice_carnet = Operation::where(['id_user' => $id_user, 'is_delete' => 0,'type_operation'=>'carnet'])->sum('benefice');

        $solde_carnet = $solde_carnet_entre - $solde_benefice_carnet;

        $sortie = Operation::where(['id_user' => $id_user, 'is_delete' => 0])->sum('sortie');
        $entre = Operation::where(['id_user' => $id_user, 'is_delete' => 0])->sum('entre');
        $benefice_total = Operation::where(['id_user' => $id_user, 'is_delete' => 0])->sum('benefice');

        $sortie_today = Operation::whereId_user($id_user)
            ->whereYear('created_at', '=', date('Y'))
            ->whereDay('created_at', '=', date('d'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->sum('sortie');

        $total_entre_today = Operation::whereId_user($id_user)
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereDay('created_at', '=', date('d'))
            ->whereIs_delete(0)
            ->sum('entre');


            $tontine_sortie = Operation::whereId_user($id_user)
            ->whereType_operation('retrait')
            ->whereType_compte('tontine')
            ->whereIs_delete(0)
            ->sum('sortie');


        $tontine_entre = Operation::where(['id_user' => $id_user, 'is_delete' => 0,'type_operation'=>'cotisation'])->sum('entre');

        $tontine_entre -= $tontine_sortie;

        $tontine_entre_today = Operation::whereId_user($id_user)
            ->whereType_operation('cotisation')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('entre');


            $tontine_sortie_today = Operation::whereId_user($id_user)
            ->whereType_operation('retrait')
            ->whereType_compte('tontine')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('sortie');



        $epargne_entre_depot = Operation::where(['id_user' => $id_user, 'is_delete' => 0,'type_operation'=>'depot'])->sum('entre');
        $epargne_entre_new = Operation::where(['id_user' => $id_user, 'is_delete' => 0,'type_operation'=>'new'])->sum('entre');

        $epargne_entre = $epargne_entre_depot + $epargne_entre_new;

        $epargne_entre_today_depot = Operation::whereId_user($id_user)
            ->whereType_operation('new')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('entre');

            $epargne_entre_today_new = Operation::whereId_user($id_user)
            ->whereType_operation('depot')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('entre');

            $epargne_sortie = Operation::whereId_user($id_user)
            ->whereType_operation('retrait')
            ->whereType_compte('epargne')
            ->whereIs_delete(0)
            ->sum('sortie');

            $epargne_entre =$epargne_entre- $epargne_sortie;

            $epargne_entre_today = $epargne_entre_today_new + $epargne_entre_today_depot;

            $epargne_sortie_today = Operation::whereId_user($id_user)
            ->whereType_operation('retrait')
            ->whereType_compte('epargne')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('sortie');

            $epargne_benefice_today = Operation::whereId_user($id_user)
            ->whereType_operation('benefice')
            ->whereType_compte('epargne')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('sortie');


        $versement = Operation::whereId_user($id_user)
            ->whereType_operation('versement')
            ->whereIs_delete(0)
            ->sum('versement');

        $solde_entre_today = Operation::whereId_user($id_user)
            ->whereYear('created_at', '=', date('Y'))
            ->whereDay('created_at', '=', date('d'))
            ->whereIs_delete(0)
            ->whereMonth('created_at', '=', date('m'))->sum('entre');

            $entre_today = $total_entre_today - $sortie_today;

            $benefice = $benefice_total - $versement;

            $solde_user = $entre - $sortie -$versement;

            $entre -= $sortie;


        return view('user.user_stastic_dashboard', compact('epargne_benefice_today','epargne_sortie_today','tontine_sortie_today','nbre_carnet','solde_carnet','versement', 'solde_user', 'solde_entre_today', 'epargne_entre', 'epargne_entre_today', 'tontine_entre_today', 'tontine_entre', 'nbr_comptes', 'mesClients', 'mesAgents', 'operations', 'user', 'sortie', 'entre', 'benefice_total', 'sortie_today', 'entre_today'));

  }

    public function user_dashboard()
    {
        $user = User::where(['id' => session('id')])->first();
        $id_user = session('id_user');

        $mesClients = Client::where(['id_user' => $id_user, 'is_delete' => 0])->get()->count();
        $mesAgents = Agent::where(['id_user' => $id_user, 'is_delete' => 0])->get()->count();
        $nbr_comptes = Account::where(['id_user' => $id_user, 'is_delete' => 0])->get()->count();
        $user = User::where(['id' => $id_user, 'is_delete' => 0])->first();

        $operations = Operation::whereId_user(session('id_user'))
            ->whereYear('created_at', '=', date('Y'))
            ->whereDay('created_at', '=', date('d'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->orderBy('updated_at', 'DESC')
            ->get();


        $nbre_carnet = Operation::where(['id_user' => $id_user, 'is_delete' => 0,'type_operation'=>'carnet'])->count();
        $solde_carnet_entre = Operation::where(['id_user' => $id_user, 'is_delete' => 0,'type_operation'=>'carnet'])->sum('entre');
        $solde_benefice_carnet = Operation::where(['id_user' => $id_user, 'is_delete' => 0,'type_operation'=>'carnet'])->sum('benefice');

        $solde_carnet = $solde_carnet_entre;

        $sortie = Operation::where(['id_user' => $id_user, 'is_delete' => 0])->sum('sortie');
        $entre = Operation::where(['id_user' => $id_user, 'is_delete' => 0])->sum('entre');
        $benefice_total = Operation::where(['id_user' => $id_user, 'is_delete' => 0])->sum('benefice');

        $sortie_today = Operation::whereId_user(session('id_user'))
            ->whereYear('created_at', '=', date('Y'))
            ->whereDay('created_at', '=', date('d'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->sum('sortie');

        $total_entre_today = Operation::whereId_user(session('id_user'))
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereDay('created_at', '=', date('d'))
            ->whereIs_delete(0)
            ->sum('entre');


            $tontine_sortie = Operation::whereId_user(session('id_user'))
            ->whereType_operation('retrait')
            ->whereType_compte('tontine')
            ->whereIs_delete(0)
            ->sum('sortie');


        $tontine_entre = Operation::where(['id_user' => $id_user, 'is_delete' => 0,'type_operation'=>'cotisation'])->sum('entre');

        $tontine_entre -= $tontine_sortie;

        $tontine_entre_today = Operation::whereId_user(session('id_user'))
            ->whereType_operation('cotisation')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('entre');


            $tontine_sortie_today = Operation::whereId_user(session('id_user'))
            ->whereType_operation('retrait')
            ->whereType_compte('tontine')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('sortie');



        $epargne_entre_depot = Operation::where(['id_user' => $id_user, 'is_delete' => 0,'type_operation'=>'depot'])->sum('entre');
        $epargne_entre_new = Operation::where(['id_user' => $id_user, 'is_delete' => 0,'type_operation'=>'new'])->sum('entre');

        $epargne_entre = $epargne_entre_depot + $epargne_entre_new;

        $epargne_entre_today_depot = Operation::whereId_user(session('id_user'))
            ->whereType_operation('new')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('entre');

            $epargne_entre_today_new = Operation::whereId_user(session('id_user'))
            ->whereType_operation('depot')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('entre');

            $epargne_sortie = Operation::whereId_user(session('id_user'))
            ->whereType_operation('retrait')
            ->whereType_compte('epargne')
            ->whereIs_delete(0)
            ->sum('sortie');

            $epargne_entre =$epargne_entre- $epargne_sortie;

            $epargne_entre_today = $epargne_entre_today_new + $epargne_entre_today_depot;

            $epargne_sortie_today = Operation::whereId_user(session('id_user'))
            ->whereType_operation('retrait')
            ->whereType_compte('epargne')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('sortie');

            $epargne_benefice_today = Operation::whereId_user(session('id_user'))
            ->whereType_operation('benefice')
            ->whereType_compte('epargne')
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereIs_delete(0)
            ->whereDay('created_at', '=', date('d'))
            ->sum('sortie');


        $versement = Operation::whereId_user(session('id_user'))
            ->whereType_operation('versement')
            ->whereIs_delete(0)
            ->sum('versement');

        $solde_entre_today = Operation::whereId_user(session('id_user'))
            ->whereYear('created_at', '=', date('Y'))
            ->whereDay('created_at', '=', date('d'))
            ->whereIs_delete(0)
            ->whereMonth('created_at', '=', date('m'))->sum('entre');

            $entre_today = $total_entre_today - $sortie_today;

            $benefice = $benefice_total - $versement;

            $solde_user = $entre - $sortie -$versement;

            $entre -= $sortie;


        return view('user.user_dashboard', compact('epargne_benefice_today','epargne_sortie_today','tontine_sortie_today','nbre_carnet','solde_carnet','versement', 'solde_user', 'solde_entre_today', 'epargne_entre', 'epargne_entre_today', 'tontine_entre_today', 'tontine_entre', 'nbr_comptes', 'mesClients', 'mesAgents', 'operations', 'user', 'sortie', 'entre', 'benefice', 'sortie_today', 'entre_today'));
    }

    public function get_new_chef_user($id)
    {

        $user = User::where(['id' => $id])->first();

        return view('user.add_new_chef_user', compact('user'));
    }

    public function add_new_chef_user(Request $request)
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

        $olde_chef_user = User::where([
            'id_user' => $data['id_user'],
            'type_user' => 2,
        ])->first();

        User::where(['id' => $olde_chef_user->id])->update([
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
            'id_user' => $data['id_user'],
            'type_user' => 2,

            'is_delete' => 0,

        ]);

        Journal::create([
            'action' => $data['first_name'] . " " . $data['last_name'] . "Ajouter comme nouveau chef d'user (" . $data['name_user'] . ") ",
            'user_id' => session('id'),
        ]);
        return redirect('liste_user')->with('flash_message_success', "Nouveau chef de cette user ajouter avec succès!");
    }
}
