<?php

namespace App\Http\Controllers;

use Session;
use App\Models\User;
use App\Models\Resum;
use App\Models\Invoice;
use App\Models\Journal;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    //
    public function index(Request $request)
    {

        if ($request->isMethod('post')) {
            $data = $request->input();

            if (User::where(['login' => $data['login']])->first()) {
                $currentUser = User::where(['login' => $data['login']])->first();
                //dd($currentUser);
                if (Hash::check($data['password'], $currentUser->password)) {

                    if ($currentUser->is_delete == 0) {

                        session(
                            [
                                'id' => $currentUser->id,
                                'type_user' => $currentUser->type_user,
                                'email' => $currentUser->email,
                            ]
                        );
                        Journal::create([
                            'action' => "Connexion à la plateforme",
                            'user_id' => $currentUser->id,
                        ]);

                        return redirect('lunchpade');
                    } else {

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

    public function forgot_password(Request $request)
    {
        return view('forgot_password');
    }

    public function update_password()
    {

        return view('update_password');
    }

    public function update_password_save(Request $request)
    {

        $request->validate([
            'old_password' => 'required',
            'password' => '|required|min:8|regex:/[@$.!%*#?&]/|confirmed',
            'password_confirmation' => 'required',
        ]);

        $data = $request->all();

        $id = session('id');

        $currentUser = User::where('id', $id)->first();

        if (Hash::check($data['old_password'], $currentUser->password)) {

            User::where(['id' => $id])->update([
                'password' => bcrypt($data['password']),
            ]);

            return redirect('/')->with('success', 'Mot de passe réinitialisé avec succès');
        } else {

            return redirect()->back()->with('error', 'Encien Mot de passe incorrect! Veuillez réessayer');
        }
    }

    public function dashboard(Request $request)
    {

        if (session('id') != null) {

            $user = User::where(['id' => session('id')])->first();

            if ($user->type_user == 0) {

                $sum_of_user = User::where(['is_delete' => 0])->get()->count();
                $sum_of_ope = Operator::where(['is_delete' => 0])->get()->count();
                $sum_of_ope_cdeao = Operator::where(['is_delete' => 0, 'cedeao' => 1])->get()->count();
                $sum_of_ope_afrique = Operator::where(['is_delete' => 0, 'afrique' => 1])->get()->count();
                $sum_of_invoice = Invoice::where(['is_delete' => 0])->get()->count();

                $sum_of_invoice_month = Invoice::whereYear('created_at', '=', date('Y'))
                    ->whereMonth('created_at', '=', date('m'))
                    ->whereIs_delete(0)
                    ->orderBy('updated_at', 'DESC')
                    ->get()
                    ->count();

                /*    $result = Resum::selectRaw('year(periodDate) year, monthname(periodDate) month, SUM(debt_cfa) as total_debt')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->get(); */

                $year_befors = Resum::selectRaw('year(periodDate) year, month(periodDate) month, SUM(debt_cfa) as total_debt, SUM(receivable_cfa) as total_receivable')
                    ->whereYear('periodDate', '=', date('Y') - 1)
                    ->whereIs_delete(0)
                    ->groupBy('year', 'month')
                    ->orderBy('month', 'asc')
                    ->get();

                $results = Resum::selectRaw('year(periodDate) year, month(periodDate) month, SUM(debt_cfa) as total_debt, SUM(receivable_cfa) as total_receivable')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereIs_delete(0)
                    ->groupBy('year', 'month')
                    ->orderBy('month', 'asc')
                    ->get();

                $curent_year_result = Resum::selectRaw('year(periodDate) year, SUM(debt_cfa) as total_year_debt, SUM(receivable_cfa) as total_year_receivable')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereIs_delete(0)
                    ->groupBy('year')
                    ->first();

                $curent_year_befor_result = Resum::selectRaw('year(periodDate) year, SUM(debt_cfa) as total_year_debt, SUM(receivable_cfa) as total_year_receivable')
                    ->whereYear('periodDate', '=', date('Y') - 1)
                    ->whereIs_delete(0)
                    ->groupBy('year')
                    ->first();

                $recouvrement_results = Resum::selectRaw('year(periodDate) year, month(periodDate) month, SUM(incoming_payement_cfa) as total_incoming_payement')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereIs_delete(0)
                    ->groupBy('year', 'month')
                    ->orderBy('month', 'asc')
                    ->get();

                $curent_year_recouvrement = Resum::selectRaw('year(periodDate) year, SUM(incoming_payement_cfa) as total_year_incoming_payement')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereIs_delete(0)
                    ->groupBy('year')
                    ->first();

                $volumEntrant_results = Invoice::selectRaw('year(periodDate) year, month(periodDate) month, SUM(call_volume) as volume')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereTgc_invoice(1)
                    ->whereIs_delete(0)
                    ->groupBy('year', 'month')
                    ->orderBy('month', 'asc')
                    ->get();

                $volumEntrantBefor_results = Invoice::selectRaw('year(periodDate) year, month(periodDate) month, SUM(call_volume) as volume')
                    ->whereYear('periodDate', '=', date('Y') - 1)
                    ->whereTgc_invoice(1)
                    ->whereIs_delete(0)
                    ->groupBy('year', 'month')
                    ->orderBy('month', 'asc')
                    ->get();

                $volumSortant_results = Invoice::selectRaw('year(periodDate) year, month(periodDate) month, SUM(call_volume) as volume')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereTgc_invoice(2)
                    ->whereIs_delete(0)
                    ->groupBy('year', 'month')
                    ->orderBy('month', 'asc')
                    ->get();

                $volumSortantBefor_results = Invoice::selectRaw('year(periodDate) year, month(periodDate) month, SUM(call_volume) as volume')
                    ->whereYear('periodDate', '=', date('Y') - 1)
                    ->whereTgc_invoice(2)
                    ->whereIs_delete(0)
                    ->groupBy('year', 'month')
                    ->orderBy('month', 'asc')
                    ->get();

                $comparaison_current_month = Resum::selectRaw('year(resum.periodDate) year, month(resum.periodDate) month, operator.name as operator_name , SUM(receivable_cfa) as total_receivable')
                    ->join('operator', 'resum.id_operator', '=', 'operator.id')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereMonth('periodDate', '=', date('m'))
                    ->where('resum.is_delete', 0)
                    ->where('operator.is_delete', 0)
                    ->where('operator.afrique', 0)
                    ->groupBy('year', 'month', 'operator_name')
                    ->orderBy('month', 'asc')
                    ->get();

                $comparaison_month_befor = Resum::selectRaw('year(resum.periodDate) year, month(resum.periodDate) month, operator.name as operator_name , SUM(receivable_cfa) as total_receivable')
                    ->join('operator', 'resum.id_operator', '=', 'operator.id')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereMonth('periodDate', '=', date('m') - 1)
                    ->where('resum.is_delete', 0)
                    ->where('operator.is_delete', 0)

                    ->where('operator.afrique', 0)
                    ->groupBy('year', 'month', 'operator_name')
                    ->orderBy('month', 'asc')
                    ->get();

                $comparaison_current_month_afq = Resum::selectRaw('year(resum.periodDate) year, month(resum.periodDate) month, operator.name as operator_name , SUM(receivable_cfa) as total_receivable')
                    ->join('operator', 'resum.id_operator', '=', 'operator.id')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereMonth('periodDate', '=', date('m'))
                    ->where('resum.is_delete', 0)
                    ->where('operator.is_delete', 0)

                    ->where('operator.afrique', 1)
                    ->groupBy('year', 'month', 'operator_name')
                    ->orderBy('month', 'asc')
                    ->get();

                $comparaison_month_befor_afq = Resum::selectRaw('year(resum.periodDate) year, month(resum.periodDate) month, operator.name as operator_name , SUM(receivable_cfa) as total_receivable')
                    ->join('operator', 'resum.id_operator', '=', 'operator.id')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereMonth('periodDate', '=', date('m') - 1)
                    ->where('resum.is_delete', 0)
                    ->where('operator.is_delete', 0)

                    ->where('operator.afrique', 1)
                    ->groupBy('year', 'month', 'operator_name')
                    ->orderBy('month', 'asc')
                    ->get();

                    $operators = Operator::where('is_delete', 0)
                    ->orderBy('created_at', 'DESC')
                    ->get();
                //  dd($comparaison_month_befor);

                return view('no_dashboard', compact('operators','comparaison_current_month_afq', 'comparaison_month_befor_afq', 'comparaison_month_befor', 'comparaison_current_month', 'sum_of_ope_afrique', 'volumEntrant_results', 'volumEntrantBefor_results', 'volumSortant_results', 'volumSortantBefor_results', 'curent_year_befor_result', 'year_befors', 'curent_year_recouvrement', 'recouvrement_results', 'curent_year_result', 'results', 'sum_of_user', 'sum_of_ope', 'sum_of_ope_cdeao', 'sum_of_invoice', 'sum_of_invoice_month'))->render();
            } elseif ($user->type_user == 3 || $user->type_user == 2 || $user->type_user == 1) {

                $sum_of_user = User::where(['is_delete' => 0])->get()->count();
                $sum_of_ope = Operator::where(['is_delete' => 0])->get()->count();
                $sum_of_ope_cdeao = Operator::where(['is_delete' => 0, 'cedeao' => 1])->get()->count();
                $sum_of_ope_afrique = Operator::where(['is_delete' => 0, 'afrique' => 1])->get()->count();
                $sum_of_invoice = Invoice::where(['is_delete' => 0])->get()->count();

                $sum_of_invoice_month = Invoice::whereYear('created_at', '=', date('Y'))
                    ->whereMonth('created_at', '=', date('m'))
                    ->whereIs_delete(0)
                    ->orderBy('updated_at', 'DESC')
                    ->get()
                    ->count();

                /*    $result = Resum::selectRaw('year(periodDate) year, monthname(periodDate) month, SUM(debt_cfa) as total_debt')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->get(); */

                $year_befors = Resum::selectRaw('year(periodDate) year, month(periodDate) month, SUM(debt_cfa) as total_debt, SUM(receivable_cfa) as total_receivable')
                    ->whereYear('periodDate', '=', date('Y') - 1)
                    ->whereIs_delete(0)
                    ->groupBy('year', 'month')
                    ->orderBy('month', 'asc')
                    ->get();

                $results = Resum::selectRaw('year(periodDate) year, month(periodDate) month, SUM(debt_cfa) as total_debt, SUM(receivable_cfa) as total_receivable')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereIs_delete(0)
                    ->groupBy('year', 'month')
                    ->orderBy('month', 'asc')
                    ->get();

                $curent_year_result = Resum::selectRaw('year(periodDate) year, SUM(debt_cfa) as total_year_debt, SUM(receivable_cfa) as total_year_receivable')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereIs_delete(0)
                    ->groupBy('year')
                    ->first();

                $curent_year_befor_result = Resum::selectRaw('year(periodDate) year, SUM(debt_cfa) as total_year_debt, SUM(receivable_cfa) as total_year_receivable')
                    ->whereYear('periodDate', '=', date('Y') - 1)
                    ->whereIs_delete(0)
                    ->groupBy('year')
                    ->first();

                $recouvrement_results = Resum::selectRaw('year(periodDate) year, month(periodDate) month, SUM(incoming_payement_cfa) as total_incoming_payement')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereIs_delete(0)
                    ->groupBy('year', 'month')
                    ->orderBy('month', 'asc')
                    ->get();

                $curent_year_recouvrement = Resum::selectRaw('year(periodDate) year, SUM(incoming_payement_cfa) as total_year_incoming_payement')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereIs_delete(0)
                    ->groupBy('year')
                    ->first();

                $volumEntrant_results = Invoice::selectRaw('year(periodDate) year, month(periodDate) month, SUM(call_volume) as volume')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereTgc_invoice(1)
                    ->whereIs_delete(0)
                    ->groupBy('year', 'month')
                    ->orderBy('month', 'asc')
                    ->get();

                $volumEntrantBefor_results = Invoice::selectRaw('year(periodDate) year, month(periodDate) month, SUM(call_volume) as volume')
                    ->whereYear('periodDate', '=', date('Y') - 1)
                    ->whereTgc_invoice(1)
                    ->whereIs_delete(0)
                    ->groupBy('year', 'month')
                    ->orderBy('month', 'asc')
                    ->get();

                $volumSortant_results = Invoice::selectRaw('year(periodDate) year, month(periodDate) month, SUM(call_volume) as volume')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereTgc_invoice(2)
                    ->whereIs_delete(0)
                    ->groupBy('year', 'month')
                    ->orderBy('month', 'asc')
                    ->get();

                $volumSortantBefor_results = Invoice::selectRaw('year(periodDate) year, month(periodDate) month, SUM(call_volume) as volume')
                    ->whereYear('periodDate', '=', date('Y') - 1)
                    ->whereTgc_invoice(2)
                    ->whereIs_delete(0)
                    ->groupBy('year', 'month')
                    ->orderBy('month', 'asc')
                    ->get();

                $comparaison_current_month = Resum::selectRaw('year(resum.periodDate) year, month(resum.periodDate) month, operator.name as operator_name , SUM(receivable_cfa) as total_receivable')
                    ->join('operator', 'resum.id_operator', '=', 'operator.id')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereMonth('periodDate', '=', date('m'))
                    ->where('resum.is_delete', 0)
                    ->where('operator.is_delete', 0)
                    ->where('operator.afrique', 0)
                    ->groupBy('year', 'month', 'operator_name')
                    ->orderBy('month', 'asc')
                    ->get();

                $comparaison_month_befor = Resum::selectRaw('year(resum.periodDate) year, month(resum.periodDate) month, operator.name as operator_name , SUM(receivable_cfa) as total_receivable')
                    ->join('operator', 'resum.id_operator', '=', 'operator.id')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereMonth('periodDate', '=', date('m') - 1)
                    ->where('resum.is_delete', 0)
                    ->where('operator.is_delete', 0)

                    ->where('operator.afrique', 0)
                    ->groupBy('year', 'month', 'operator_name')
                    ->orderBy('month', 'asc')
                    ->get();

                $comparaison_current_month_afq = Resum::selectRaw('year(resum.periodDate) year, month(resum.periodDate) month, operator.name as operator_name , SUM(receivable_cfa) as total_receivable')
                    ->join('operator', 'resum.id_operator', '=', 'operator.id')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereMonth('periodDate', '=', date('m'))
                    ->where('resum.is_delete', 0)
                    ->where('operator.is_delete', 0)

                    ->where('operator.afrique', 1)
                    ->groupBy('year', 'month', 'operator_name')
                    ->orderBy('month', 'asc')
                    ->get();

                $comparaison_month_befor_afq = Resum::selectRaw('year(resum.periodDate) year, month(resum.periodDate) month, operator.name as operator_name , SUM(receivable_cfa) as total_receivable')
                    ->join('operator', 'resum.id_operator', '=', 'operator.id')
                    ->whereYear('periodDate', '=', date('Y'))
                    ->whereMonth('periodDate', '=', date('m') - 1)
                    ->where('resum.is_delete', 0)
                    ->where('operator.is_delete', 0)

                    ->where('operator.afrique', 1)
                    ->groupBy('year', 'month', 'operator_name')
                    ->orderBy('month', 'asc')
                    ->get();


                    $operators = Operator::where('is_delete', 0)
                    ->orderBy('created_at', 'DESC')
                    ->get();

                //  dd($comparaison_month_befor);

                return view('dashboard', compact('operators','comparaison_current_month_afq', 'comparaison_month_befor_afq', 'comparaison_month_befor', 'comparaison_current_month', 'sum_of_ope_afrique', 'volumEntrant_results', 'volumEntrantBefor_results', 'volumSortant_results', 'volumSortantBefor_results', 'curent_year_befor_result', 'year_befors', 'curent_year_recouvrement', 'recouvrement_results', 'curent_year_result', 'results', 'sum_of_user', 'sum_of_ope', 'sum_of_ope_cdeao', 'sum_of_invoice', 'sum_of_invoice_month'))->render();
            }
        }
        return view('index');
    }



    public function lunchpade(Request $request)
    {
        //dd('ok');
        if (session('id') != null) {

            return view('lunchpad');
        }
    }

    public function national()
    {
        //dd(session('id'));
        if (session('id') != null) {

            return view('national.national');
        }

        return view('index');
    }

    public function roaming()
    {
        //dd(session('id'));
        if (session('id') != null) {

            return view('roaming.roaming');
        }

        return view('index');
    }

    public function roaming2(Request $request)
    {

        if (session('id') != null) {

      /*      $daily_trend_incoming = DB::connection('mysql_remote')
            ->table('live_roaming_trend')
            ->selectRaw('*')
            ->where('direction', '=', 'Incoming')
            ->orderBy('start_hour', 'ASC')
            ->get();

        $daily_trend_incoming_MAX = DB::connection('mysql_remote')
            ->table('live_roaming_trend')
            ->where('direction', '=', 'Incoming')
            ->max('duration_min');

        $daily_trend_incoming_MIN = DB::connection('mysql_remote')
            ->table('live_roaming_trend')
            ->where('direction', '=', 'Incoming')
            ->min('duration_min');

        $daily_trend_outgoing = DB::connection('mysql_remote')
            ->table('live_roaming_trend')
            ->selectRaw('*')
            ->where('direction', '=', 'Outgoing')
            ->orderBy('start_hour', 'ASC')
            ->get();


            */
           // dd($daily_trend_outgoing);

           $daily_trend_incoming = DB::connection('mysql_remote')
           ->table('live_roaming_trend')
           ->selectRaw('*')
           ->where('direction', '=', 'Incoming')
           ->orderBy('start_hour', 'ASC')
           ->get();

       $daily_trend_incoming_MAX = DB::connection('mysql_remote')
           ->table('live_roaming_trend')
           ->where('direction', '=', 'Incoming')
           ->max('duration_min');

       $daily_trend_incoming_MIN = DB::connection('mysql_remote')
           ->table('live_roaming_trend')
           ->where('direction', '=', 'Incoming')
           ->min('duration_min');

       $daily_trend_outgoing = DB::connection('mysql_remote')
           ->table('live_roaming_trend')
           ->selectRaw('*')
           ->where('direction', '=', 'Outgoing')
           ->orderBy('start_hour', 'ASC')
           ->get();

            return view('roaming.roaming', compact('daily_trend_incoming', 'daily_trend_outgoing','daily_trend_incoming_MIN','daily_trend_incoming_MAX'))->render();
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
        Session::put('locale', $locale);
        return redirect('/');
    }
}
