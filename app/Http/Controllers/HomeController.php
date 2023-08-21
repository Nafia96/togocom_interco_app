<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Models\User;
use App\Models\Intervention;
use App\Models\Journal;
use App\Models\Admin;
use App\Models\Agence;
use App\Models\Agent;
use App\Models\Client;
use App\Models\Comptes;
use App\Models\Operation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Session;

class HomeController extends Controller
{
    //
    public function index(Request $request){

        if ($request->isMethod('post')) {
            $data = $request->input();

            if (User::where(['login' => $data['login']])->first()) {
                $currentUser = User::where(['login' => $data['login']])->first();
                //dd($currentUser);
                if (Hash::check($data['password'], $currentUser->password)) {


                    if($currentUser->is_delete == 0){


                    session(['id' => $currentUser->id,
                        'type_user' => $currentUser->type_user,
                        'email' => $currentUser->email,
                        ]
                    );
                    Journal::create([
                        'action' => "Connexion à la plateforme",
                        'user_id' => $currentUser->id,
                    ]);

                    if($currentUser->type_user == 1){

                        return redirect('/dashboard');

                    }elseif($currentUser->type_user == 2){

                        return redirect('/agence_dashboard');

                    }elseif($currentUser->type_user == 3){

                        return redirect('/agent_dashboard');

                    }else{
                        return redirect('/client_dashboard');
                    }


                    }else{

                        return redirect()->back()->with('error', "Compte bloqué, Veuillez contacter votre administation")->withInput();


                    }



                } else {
                    return redirect()->back()->with('error', 'Votre mot de passe est invalide')->withInput();
                }
            } else {
                return redirect()->back()->with('error', 'Nom utilisateur incorrecte! Veuillez réessayer')->withInput();
            }
        }
        return view('index');

    }

    public function forgot_password(Request $request){
        return view('forgot_password');
    }

    public function update_password(){

        return view('update_password');
    }




    public function update_password_save(Request $request){

        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        $data = $request->all();

        $id = session('id');

        $currentUser = User::where('id', $id)->first();



        if(Hash::check($data['old_password'], $currentUser->password)){

            User::where(['id' => $id])->update([
                'password' => bcrypt($data['password']),
                ]);

            return redirect('/')->with('success', 'Mot de passe réinitialisé avec succès');


        }else{

            return redirect()->back()->with('error', 'Encien Mot de passe incorrect! Veuillez réessayer');

        }



    }


    public function dashboard(Request $request){

        if(session('id') != null){



        $user = User::where(['id' =>session('id')])->first();



        if($user->type_user == 1){







            return view('dashboard');

        }elseif($user->type_user == 2){

            return redirect('agence_dashboard');

        }elseif($user->type_user == 3){
            return redirect('agent_dashboard');

        }elseif($user->type_user == 4){
            return redirect('client_dashboard');
        }
    }
    return view('index');
    }

    public function journaux(Request $request)
    {

            $id = session('id');
        $journaux = Journal::orderBy("created_at", 'DESC')->get();
        return view('logs', compact('journaux'));
    }


       public function logout(Request $request)
        {
            $id = session('id');
            if ($id != null) {
                Journal::create([
                    'action' => "Déconnexion de la plateforme",
                    'user_id' => $id,
                ]);
            }
            $locale = app()->getLocale();
            Session::flush();
            Session::put('locale',$locale);
            return redirect('/');
        }

}
