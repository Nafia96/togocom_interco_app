<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Resum;
use App\Models\Invoice;
use App\Models\Journal;
use App\Models\Rcredit;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;

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

                        if ($currentUser->type_user == 6) {

                            return redirect('lunchpadb');
                        } else {

                            return redirect('lunchpade');
                        }
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

                return view('no_dashboard', compact('operators', 'comparaison_current_month_afq', 'comparaison_month_befor_afq', 'comparaison_month_befor', 'comparaison_current_month', 'sum_of_ope_afrique', 'volumEntrant_results', 'volumEntrantBefor_results', 'volumSortant_results', 'volumSortantBefor_results', 'curent_year_befor_result', 'year_befors', 'curent_year_recouvrement', 'recouvrement_results', 'curent_year_result', 'results', 'sum_of_user', 'sum_of_ope', 'sum_of_ope_cdeao', 'sum_of_invoice', 'sum_of_invoice_month'))->render();
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

                return view('dashboard', compact('operators', 'comparaison_current_month_afq', 'comparaison_month_befor_afq', 'comparaison_month_befor', 'comparaison_current_month', 'sum_of_ope_afrique', 'volumEntrant_results', 'volumEntrantBefor_results', 'volumSortant_results', 'volumSortantBefor_results', 'curent_year_befor_result', 'year_befors', 'curent_year_recouvrement', 'recouvrement_results', 'curent_year_result', 'results', 'sum_of_user', 'sum_of_ope', 'sum_of_ope_cdeao', 'sum_of_invoice', 'sum_of_invoice_month'))->render();
            }
        }
        return view('index');
    }



    public function lunchpade(Request $request)
    {
        //dd('ok');
        if (session('id') != null) {

            $operators = Operator::where(['is_delete' => 0])->get()->count();

            return view('lunchpad', compact('operators'))->render();
        }
    }

    public function lunchpadb(Request $request)
    {
        //dd('ok');
        if (session('id') != null) {

            return view('lunchpadb');
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
    public function billing3(Request $request)
    {
        // Construire la requête de base
        $query = DB::table('billing_stat')
            ->select(
                'direction',
                'carrier_name',
                DB::raw('DATE(start_date) as periode'),
                DB::raw('SUM(CAST(REPLACE(minutes, ",", ".") AS DECIMAL(10,2))) as minutes'),
                DB::raw('SUM(CAST(REPLACE(amount_cfa, ",", "") AS DECIMAL(15,2))) as amount_cfa')
            )
            ->groupBy('direction', 'carrier_name', DB::raw('DATE(start_date)'))
            ->orderBy('periode', 'desc');

        // Appliquer les filtres si disponibles
        if ($request->filled('direction')) {
            $query->where('direction', $request->direction);
        }

        if ($request->filled('start_period')) {
            $query->whereDate('start_date', '>=', $request->start_period . '-01');
        }

        if ($request->filled('end_period')) {
            $query->whereDate('start_date', '<=', $request->end_period . '-31');
        }

        if ($request->filled('operator')) {
            $query->where('carrier_name', $request->operator);
        }

        $results = $query->get(); // ✅ Important

        // Transmettre à la vue
        return view('billing.billing2', compact('results'));
    }

    public function billing22222222(Request $request)
    {
        ini_set('memory_limit', '512M');

        if (!session('id')) return view('index');

        $direction = $request->input('direction');
        $viewType = $request->input('view_type', 'daily_carrier');

        $start = $request->input('start_period') ? $request->input('start_period') . '-01' : now()->subMonth()->startOfMonth()->toDateString();
        $end = $request->input('end_period') ? Carbon::parse($request->input('end_period'))->endOfMonth()->toDateString() : now()->toDateString();

        $carrier = $request->input('carrier_name');


        //dd($start, $end, $direction, $carrier, $viewType);

        // Construction de la requête
        $query = DB::connection('inter_traffic')->table('BILLING_STAT')
            ->whereBetween('start_date', [$start, $end]);

        if ($direction && in_array($direction, ['Revenue', 'Charge'])) {
            $query->where('direction', $direction);
        }


        if ($carrier) {
            if (is_array($carrier)) {
                $query->whereIn('carrier_name', $carrier);
            } else {
                $query->where('carrier_name', $carrier);
            }
        }

        // Sélection et groupement selon le type de vue
        switch ($viewType) {
            case 'daily_summary':
                $query->selectRaw("
                DATE(start_date) as period,
                direction,
                SUM(CAST(minutes AS DECIMAL(10,2))) as total_minutes,
                SUM(CAST(amount_cfa AS DECIMAL(20,2))) as total_amount
            ")
                    ->groupBy(DB::raw('DATE(start_date)'), 'direction');
                break;

            case 'daily_carrier':
                $query->selectRaw("
                DATE(start_date) as period,
                carrier_name,
                direction,
                SUM(CAST(minutes AS DECIMAL(10,2))) as total_minutes,
                SUM(CAST(amount_cfa AS DECIMAL(20,2))) as total_amount
            ")
                    ->groupBy(DB::raw('DATE(start_date)'), 'carrier_name', 'direction');
                break;

            case 'monthly_summary':
                $query->selectRaw("
                DATE_FORMAT(start_date, '%Y-%m') as period,
                direction,
                SUM(CAST(minutes AS DECIMAL(10,2))) as total_minutes,
                SUM(CAST(amount_cfa AS DECIMAL(20,2))) as total_amount
            ")
                    ->groupBy(DB::raw("DATE_FORMAT(start_date, '%Y-%m')"), 'direction');
                break;

            case 'monthly_carrier':
                $query->selectRaw("
                DATE_FORMAT(start_date, '%Y-%m') as period,
                carrier_name,
                direction,
                SUM(CAST(minutes AS DECIMAL(10,2))) as total_minutes,
                SUM(CAST(amount_cfa AS DECIMAL(20,2))) as total_amount
            ")
                    ->groupBy(DB::raw("DATE_FORMAT(start_date, '%Y-%m')"), 'carrier_name', 'direction');
                break;

            case 'monthly_details':
            default:
                $query->selectRaw("
                DATE(start_date) as period,
                carrier_name,
                direction,
                SUM(CAST(minutes AS DECIMAL(10,2))) as total_minutes,
                SUM(CAST(amount_cfa AS DECIMAL(20,2))) as total_amount
            ")
                    ->groupBy(DB::raw('DATE(start_date)'), 'carrier_name', 'direction');
                break;
        }

        $data = $query->orderBy('period', 'desc')->paginate(100);

        $operators = DB::connection('inter_traffic')->table('BILLING_STAT')
            ->select('carrier_name')
            ->distinct()
            ->orderBy('carrier_name')
            ->pluck('carrier_name');

        return view('billing.billing2', [
            'data' => $data,
            'operators' => $operators,
            'filters' => compact('direction', 'start', 'end', 'carrier', 'viewType')
        ]);
    }





    public function billing2(Request $request)
    {
        ini_set('memory_limit', '512M');
        if (!session('id')) return view('index');

        // Valeur par défaut : daily_carrier si aucun paramètre
        $viewType = $request->has('view_type') ? $request->input('view_type') : 'daily_carrier';

        $direction = $request->input('direction', null);

        // Période par défaut : mois précédent au complet
        $start = $request->input('start_period')
            ? $request->input('start_period') . '-01'
            : now()->subMonth()->startOfMonth()->toDateString();

        $end = $request->input('end_period')
            ? Carbon::parse($request->input('end_period'))->endOfMonth()->toDateString()
            : now()->toDateString();

        $carrier = $request->input('carrier_name');

        // Filtres multi-select
        $origNet = $request->input('orig_net_name');
        $destNet = $request->input('dest_net_name');
        $origCountry = $request->input('orig_country_name');
        $destCountry = $request->input('dest_country_name');

        $query = DB::connection('inter_traffic')->table('BILLING_STAT')
            ->whereBetween('start_date', [$start, $end]);

        if ($direction && in_array($direction, ['Revenue', 'Charge'])) {
            $query->where('direction', $direction);
        }
        if ($carrier) {
            is_array($carrier)
                ? $query->whereIn('carrier_name', $carrier)
                : $query->where('carrier_name', $carrier);
        }
        if ($origNet) {
            is_array($origNet)
                ? $query->whereIn('orig_net_name', $origNet)
                : $query->where('orig_net_name', $origNet);
        }
        if ($destNet) {
            is_array($destNet)
                ? $query->whereIn('dest_net_name', $destNet)
                : $query->where('dest_net_name', $destNet);
        }
        if ($origCountry) {
            is_array($origCountry)
                ? $query->whereIn('orig_country_name', $origCountry)
                : $query->where('orig_country_name', $origCountry);
        }
        if ($destCountry) {
            is_array($destCountry)
                ? $query->whereIn('dest_country_name', $destCountry)
                : $query->where('dest_country_name', $destCountry);
        }

        // Détection des colonnes optionnelles à inclure
        $selectNetNames = '';
        $groupNetNames = [];
        if (request('show_net_name') == '1' || request('orig_net_name') || request('dest_net_name')) {
            $selectNetNames = ",
        orig_net_name,
        dest_net_name";
            $groupNetNames = ['orig_net_name', 'dest_net_name'];
        }

        $selectCountryNames = '';
        $groupCountryNames = [];
        if (request('show_country_name') == '1' || request('orig_country_name') || request('dest_country_name')) {
            $selectCountryNames = ",
        orig_country_name,
        dest_country_name";
            $groupCountryNames = ['orig_country_name', 'dest_country_name'];
        }

        // Sélection et groupement selon le type de vue
        switch ($viewType) {
            case 'daily_summary':
                $query->selectRaw("
            DATE(start_date) as period,
            direction
            $selectNetNames
            $selectCountryNames,
            SUM(CAST(minutes AS DECIMAL(10,2))) as total_minutes,
            SUM(CAST(amount_cfa AS DECIMAL(20,2))) as total_amount
        ")
                    ->groupBy(
                        DB::raw('DATE(start_date)'),
                        'direction',
                        ...$groupNetNames,
                        ...$groupCountryNames
                    );
                break;

            case 'daily_carrier':
                $query->selectRaw("
            DATE(start_date) as period,
            carrier_name,
            direction
            $selectNetNames
            $selectCountryNames,
            SUM(CAST(minutes AS DECIMAL(10,2))) as total_minutes,
            SUM(CAST(amount_cfa AS DECIMAL(20,2))) as total_amount
        ")
                    ->groupBy(
                        DB::raw('DATE(start_date)'),
                        'carrier_name',
                        'direction',
                        ...$groupNetNames,
                        ...$groupCountryNames
                    );
                break;

            case 'monthly_summary':
                $query->selectRaw("
            DATE_FORMAT(start_date, '%Y-%m') as period,
            direction
            $selectNetNames
            $selectCountryNames,
            SUM(CAST(minutes AS DECIMAL(10,2))) as total_minutes,
            SUM(CAST(amount_cfa AS DECIMAL(20,2))) as total_amount
        ")
                    ->groupBy(
                        DB::raw("DATE_FORMAT(start_date, '%Y-%m')"),
                        'direction',
                        ...$groupNetNames,
                        ...$groupCountryNames
                    );
                break;

            case 'monthly_carrier':
                $query->selectRaw("
            DATE_FORMAT(start_date, '%Y-%m') as period,
            carrier_name,
            direction
            $selectNetNames
            $selectCountryNames,
            SUM(CAST(minutes AS DECIMAL(10,2))) as total_minutes,
            SUM(CAST(amount_cfa AS DECIMAL(20,2))) as total_amount
        ")
                    ->groupBy(
                        DB::raw("DATE_FORMAT(start_date, '%Y-%m')"),
                        'carrier_name',
                        'direction',
                        ...$groupNetNames,
                        ...$groupCountryNames
                    );
                break;

            case 'monthly_details':
            default:
                $query->selectRaw("
            DATE(start_date) as period,
            carrier_name,
            direction
            $selectNetNames
            $selectCountryNames,
            SUM(CAST(minutes AS DECIMAL(10,2))) as total_minutes,
            SUM(CAST(amount_cfa AS DECIMAL(20,2))) as total_amount
        ")
                    ->groupBy(
                        DB::raw('DATE(start_date)'),
                        'carrier_name',
                        'direction',
                        ...$groupNetNames,
                        ...$groupCountryNames
                    );
                break;
        }


        $data = $query->orderBy('period', 'desc')->paginate(1000);

        // Pour les filtres dynamiques dans la vue
        $operators = DB::connection('inter_traffic')->table('BILLING_STAT')->select('carrier_name')->distinct()->orderBy('carrier_name')->pluck('carrier_name');
        $origNets = DB::connection('inter_traffic')->table('BILLING_STAT')->select('orig_net_name')->distinct()->orderBy('orig_net_name')->pluck('orig_net_name');
        $destNets = DB::connection('inter_traffic')->table('BILLING_STAT')->select('dest_net_name')->distinct()->orderBy('dest_net_name')->pluck('dest_net_name');
        $origCountries = DB::connection('inter_traffic')->table('BILLING_STAT')->select('orig_country_name')->distinct()->orderBy('orig_country_name')->pluck('orig_country_name');
        $destCountries = DB::connection('inter_traffic')->table('BILLING_STAT')->select('dest_country_name')->distinct()->orderBy('dest_country_name')->pluck('dest_country_name');

        return view('billing.billing2', [
            'data' => $data,
            'operators' => $operators,
            'origNets' => $origNets,
            'destNets' => $destNets,
            'origCountries' => $origCountries,
            'destCountries' => $destCountries,
            'filters' => compact('direction', 'start', 'end', 'carrier', 'viewType', 'origNet', 'destNet', 'origCountry', 'destCountry')
        ]);
    }



    public function billingPivot(Request $request)
    {
        // Filtres
        $month = $request->input('month', now()->format('Y-m'));
        $filter = $request->input('filter', 'revenu');
        $startDate = $request->input('start_date'); // format YYYY-MM-DD
        $endDate   = $request->input('end_date');   // format YYYY-MM-DD

        // Définition de la période
        if ($startDate && $endDate) {
            // Utilisateur a choisi une période personnalisée
            $start = Carbon::parse($startDate)->startOfDay();
            $end   = Carbon::parse($endDate)->endOfDay();
        } else {
            // Sinon : on garde le filtre par mois
            $year = (int) substr($month, 0, 4);
            $monthNum = (int) substr($month, 5, 2);
            $start = Carbon::createFromDate($year, $monthNum, 1)->startOfDay();
            $end   = Carbon::createFromDate($year, $monthNum, 1)->endOfMonth()->endOfDay();
        }

        // Mapping métriques
        $map = [
            'entrant' => ['col' => 'minutes',    'direction' => 'Revenue', 'label' => 'Volume entrant (minutes)'],
            'sortant' => ['col' => 'minutes',    'direction' => 'Charge',  'label' => 'Volume sortant (minutes)'],
            'revenu'  => ['col' => 'amount_cfa', 'direction' => 'Revenue', 'label' => 'Revenu (CFA)'],
            'charge'  => ['col' => 'amount_cfa', 'direction' => 'Charge',  'label' => 'Charge (CFA)'],
        ];
        $conf = $map[$filter] ?? $map['revenu'];

        // Query principale
        $q = DB::connection('inter_traffic')
            ->table('BILLING_STAT')
            ->whereBetween('start_date', [$start, $end]);

        if (!empty($conf['direction'])) {
            $q->where('direction', $conf['direction']);
        }

        $sumExpr = $conf['col'] === 'minutes'
            ? "SUM(CAST(minutes AS DECIMAL(20,6)))"
            : "SUM(CAST(amount_cfa AS DECIMAL(20,2)))";

        $records = $q->select([
            DB::raw('carrier_name AS operator'),
            DB::raw('DATE(start_date) AS day'),
            DB::raw("$sumExpr AS total"),
        ])
            ->groupBy('carrier_name', DB::raw('DATE(start_date)'))
            ->orderBy('carrier_name')
            ->get();

        // Génération de la liste des jours (en YYYY-MM-DD)
        $days = [];
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $days[] = $cursor->toDateString();
            $cursor->addDay();
        }

        // Groupement par opérateur
        $operators = $records->groupBy('operator');

        // Totaux journaliers
        $totals = [];
        foreach ($days as $d) {
            $totals[$d] = (float) $records->where('day', $d)->sum('total');
        }

        $metricLabel = $conf['label'];

        return view('billing.billingPivot', compact(
            'operators',
            'days',
            'totals',
            'month',
            'filter',
            'metricLabel',
            'startDate',
            'endDate'
        ));
    }



    public function billingPivotNetCarrier(Request $request)
    {
        // 1️⃣ Paramètres d’entrée
        $month     = $request->input('month', now()->format('Y-m'));
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $carrier   = $request->input('carrier_name');
        $filter    = strtolower($request->input('filter', 'entrant')); // entrant | revenu | sortant | charge

        // 2️⃣ Détermination de la période
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end   = Carbon::parse($endDate)->endOfDay();
        } else {
            [$year, $monthNum] = explode('-', $month);
            $start = Carbon::createFromDate($year, $monthNum, 1)->startOfDay();
            $end   = (clone $start)->endOfMonth()->endOfDay();
        }

        // 3️⃣ Logique du filtre (direction / métrique)
        $isRevenue = in_array($filter, ['revenu', 'entrant']);
        $direction = $isRevenue ? 'revenue' : 'charge';
        $netColumn = $isRevenue ? 'orig_net_name' : 'dest_net_name';

        // 4️⃣ Type de valeur à agréger
        $selectValue = match ($filter) {
            'revenu', 'charge' => DB::raw('SUM(CAST(amount_cfa AS DECIMAL(20,2))) as value'),
            default => DB::raw('SUM(CAST(minutes AS DECIMAL(20,6))) as value'),
        };
        $valueLabel = in_array($filter, ['revenu', 'charge']) ? 'Montant CFA' : 'Minutes';

        // 5️⃣ Construction de la requête principale
        $query = DB::connection('inter_traffic')
            ->table('BILLING_STAT')
            ->select([
                'direction',
                DB::raw('DATE(start_date) as period'),
                DB::raw("$netColumn as orig_net_name"),
                'carrier_name',
                $selectValue,
            ])
            ->where('direction', $direction)
            ->whereBetween('start_date', [$start, $end])
            ->groupBy('direction', DB::raw('DATE(start_date)'), $netColumn, 'carrier_name');

        // 6️⃣ Filtres additionnels
        if ($carrier) {
            $query->where('carrier_name', $carrier);
        }
        if ($request->filled('orig_net_name')) {
            $query->where($netColumn, 'like', '%' . $request->orig_net_name . '%');
        }

        // 7️⃣ Exécution
        $records = $query
            ->orderBy('direction')
            ->orderBy('period')
            ->orderBy($netColumn)
            ->orderBy('carrier_name')
            ->get();

        // 8️⃣ Récupération des transporteurs distincts (cache possible)
        $allCarriers = DB::connection('inter_traffic')
            ->table('BILLING_STAT')
            ->distinct()
            ->orderBy('carrier_name')
            ->pluck('carrier_name');

        // 9️⃣ Génération des jours (pour affichage pivot)
        $days = collect();
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $days->push($cursor->toDateString());
            $cursor->addDay();
        }

        // 🔟 Retour de la vue
        return view('billing.billingPivotNetCarrier', [
            'records'     => $records,
            'days'        => $days,
            'month'       => $month,
            'startDate'   => $startDate,
            'endDate'     => $endDate,
            'allCarriers' => $allCarriers,
            'carrier'     => $carrier,
            'filter'      => $filter,
            'valueLabel'  => $valueLabel,
        ]);
    }

    public function billingPivotCountryCarrier(Request $req)
    {
        // ----------- 1️⃣ Filtres ------------- //
        $month        = $req->month ?? now()->format('Y-m');
        $filter       = $req->filter ?? 'entrant';
        $carrierName  = $req->carrier_name;
        $countryName  = $req->orig_country_name;
        $startDate    = $req->start_date;
        $endDate      = $req->end_date;

        // ----------- 2️⃣ Direction selon filtre ------------ //
        // mapping correct avec BILLING_STAT
        $direction = match ($filter) {
            'entrant', 'revenu' => 'revenue',
            'sortant', 'charge' => 'charge',
            default => 'revenue'
        };

        // ----------- 3️⃣ Période (jours) ----------- //
        if ($month) {
            $startPeriod = Carbon::parse("$month-01");
            $endPeriod   = $startPeriod->copy()->endOfMonth();
        }

        if ($startDate) $startPeriod = Carbon::parse($startDate);
        if ($endDate)   $endPeriod   = Carbon::parse($endDate);

        $days = collect();
        for ($d = $startPeriod->copy(); $d <= $endPeriod; $d->addDay()) {
            $days->push($d->format('Y-m-d'));
        }

        // ----------- 4️⃣ Choice pays colonne ------------ //
        $countryColumn = ($direction === 'revenue')
            ? 'orig_country_name'
            : 'dest_country_name';

        // ----------- 5️⃣ Requête principale ------------ //
        $query = DB::connection('inter_traffic')
            ->table('BILLING_STAT')
            ->select([
                DB::raw("$countryColumn as orig_country_name"),
                DB::raw("DATE(start_date) as period"),
                DB::raw($this->selectValueExpression($filter) . " as value"),
                'carrier_name'
            ])
            ->where('direction', $direction)
            ->whereBetween('start_date', [$startPeriod, $endPeriod]);

        // Filtres dynamiques
        if ($carrierName) {
            $query->where('carrier_name', $carrierName);
        }

        if ($countryName) {
            $query->where($countryColumn, 'LIKE', "%$countryName%");
        }

        // Group by
        $records = $query
            ->groupBy('orig_country_name', 'period', 'carrier_name')
            ->orderBy('orig_country_name')
            ->get();

        // Liste opérateurs
        $allCarriers = DB::connection('inter_traffic')
            ->table('BILLING_STAT')
            ->distinct()
            ->pluck('carrier_name');

        return view('billing.billingPivotCountryCarrier', [
            'records'       => $records,
            'days'          => $days,
            'allCarriers'   => $allCarriers,
            'month'         => $month,
            'filter'        => $filter,
            'carrier'       => $carrierName,
            'startDate'     => $startPeriod->format('Y-m-d'),
            'endDate'       => $endPeriod->format('Y-m-d'),
        ]);
    }

    private function selectValueExpression($filter)
    {
        return match ($filter) {
            'entrant', 'sortant' => "SUM(minutes)",       // minutes
            'revenu', 'charge'   => "SUM(amount_cfa)",    // montant total
            default              => "SUM(minutes)"
        };
    }





    public function billingKp(Request $request)
    {
        ini_set('memory_limit', '512M');
        if (!session('id')) return view('index');

        // Période par défaut : mois courant
        $start = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->toDateString()
            : now()->startOfMonth()->toDateString();

        $end = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->toDateString()
            : now()->toDateString();

        // Filtres optionnels
        $direction = $request->input('direction'); // Revenue ou Charge
        $carrier = $request->input('carrier_name');

        // Requête principale : récupérer données de facturation
        $query = DB::connection('inter_traffic')
            ->table('BILLING_STAT')
            ->whereBetween('start_date', [$start, $end]);

        if ($direction && in_array($direction, ['revenue', 'charge'])) {
            $query->where('direction', $direction);
        }

        if ($carrier) {
            $query->where('carrier_name', $carrier);
        }

        // Récupérer tous les enregistrements pour calculs agrégés
        $allData = $query->get();

        // Calcul des KPIs par jour et direction
        $dailyKpis = DB::connection('inter_traffic')
            ->table('BILLING_STAT')
            ->select([
                DB::raw('DATE(start_date) as day'),
                'direction',
                DB::raw('SUM(CAST(minutes AS DECIMAL(20,6))) as total_minutes'),
                DB::raw('SUM(CAST(amount_cfa AS DECIMAL(20,2))) as total_amount'),
                DB::raw('COUNT(DISTINCT carrier_name) as carrier_count'),
            ])
            ->whereBetween('start_date', [$start, $end]);

        if ($direction && in_array($direction, ['revenue', 'charge'])) {
            $dailyKpis->where('direction', $direction);
        }

        if ($carrier) {
            $dailyKpis->where('carrier_name', $carrier);
        }

        $dailyKpis = $dailyKpis
            ->groupBy(DB::raw('DATE(start_date)'), 'direction')
            ->orderBy('day', 'desc')
            ->get();

        // Calcul des KPIs globaux
        $globalKpis = [
            'total_minutes' => (float) ($allData->sum('minutes') ?? 0),
            'total_amount' => (float) ($allData->sum('amount_cfa') ?? 0),
            'avg_minutes' => $allData->count() > 0 ? (float) ($allData->sum('minutes') / $allData->count()) : 0,
            'avg_amount' => $allData->count() > 0 ? (float) ($allData->sum('amount_cfa') / $allData->count()) : 0,
            'record_count' => $allData->count(),
            'unique_carriers' => $allData->unique('carrier_name')->count(),
        ];

        // KPIs par opérateur
        $carrierKpis = DB::connection('inter_traffic')
            ->table('BILLING_STAT')
            ->select([
                'carrier_name',
                'direction',
                DB::raw('SUM(CAST(minutes AS DECIMAL(20,6))) as total_minutes'),
                DB::raw('SUM(CAST(amount_cfa AS DECIMAL(20,2))) as total_amount'),
                DB::raw('COUNT(*) as record_count'),
            ])
            ->whereBetween('start_date', [$start, $end]);

        if ($direction && in_array($direction, ['revenue', 'charge'])) {
            $carrierKpis->where('direction', $direction);
        }

        if ($carrier) {
            $carrierKpis->where('carrier_name', $carrier);
        }

        $carrierKpis = $carrierKpis
            ->groupBy('carrier_name', 'direction')
            ->orderBy('total_amount', 'desc')
            ->get();

        // Liste des opérateurs disponibles
        $allCarriers = DB::connection('inter_traffic')
            ->table('BILLING_STAT')
            ->distinct()
            ->orderBy('carrier_name')
            ->pluck('carrier_name');

        return view('billing.billingKp', [
            'dailyKpis'   => $dailyKpis,
            'globalKpis'  => $globalKpis,
            'carrierKpis' => $carrierKpis,
            'allCarriers' => $allCarriers,
            'start'       => $start,
            'end'         => $end,
            'direction'   => $direction,
            'carrier'     => $carrier,
        ]);
    }

    public function networkKpi(Request $request)
    {
        ini_set('memory_limit', '512M');
        if (!session('id')) return view('index');

        // Période par défaut : semaine précédente
        $start = $request->input('start_period')
            ? Carbon::parse($request->input('start_period'))->toDateString()
            : now()->subWeek()->startOfWeek()->toDateString();

        $end = $request->input('end_period')
            ? Carbon::parse($request->input('end_period'))->toDateString()
            : now()->subWeek()->endOfWeek()->toDateString();

        // Requête brute avec variables
        $sql = "
        select call_type,
               CONCAT(MIN(event_date), ' - ', MAX(event_date)) AS dates_range,
               CONCAT(YEAR(event_date), '-W', LPAD(WEEK(event_date, 3), 2, '0')) AS call_week,
               orig_net_name as net_name,
               partner_name,
               sum(attempt) attempt,
               CONCAT(round((sum(completed) / sum(attempt))*100,2),'%') NER,
               CONCAT(round((sum(answered) / sum(attempt))*100,2),'%') ASR,
               if(sum(minutes)=0,0,round((sum(minutes)*60) / sum(answered))) ACD_SEC
        from COMPLETION_STAT
        where event_date between :start1 and :end1
          and partner_name not like 'Not Available'
          and orig_net_name not like 'Not Available'
          and call_type like 'incoming'
        group by call_type, call_week, partner_name, orig_net_name

        union all

        select call_type,
               CONCAT(MIN(event_date), ' - ', MAX(event_date)) AS dates_range,
               CONCAT(YEAR(event_date), '-W', LPAD(WEEK(event_date, 3), 2, '0')) AS call_week,
               dest_net_name as net_name,
               partner_name,
               sum(attempt) attempt,
               CONCAT(round((sum(completed) / sum(attempt))*100,2),'%') NER,
               CONCAT(round((sum(answered) / sum(attempt))*100,2),'%') ASR,
               if(sum(minutes)=0,0,round((sum(minutes)*60) / sum(answered))) ACD_SEC
        from COMPLETION_STAT
        where event_date between :start2 and :end2
          and partner_name not like 'Not Available'
          and dest_net_name not like 'Not Available'
          and call_type like 'outgoing'
        group by call_type, call_week, partner_name, dest_net_name
        order by call_week desc
";

        $data = DB::connection('inter_traffic')->select($sql, [
            'start1' => $start,
            'end1'   => $end,
            'start2' => $start,
            'end2'   => $end,
        ]);

        return view('billing.kpi', [
            'data' => $data,
            'filters' => compact('start', 'end')
        ]);
    }



    public function complation(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $callType  = $request->input('call_type'); // ✅ Nouveau filtre

        $query = DB::connection('inter_traffic')
            ->table('COMPLETION_STAT');

        // ✅ Si pas de filtre date => dernier jour seulement
        if (!$startDate && !$endDate) {
            $lastDate = DB::connection('inter_traffic')
                ->table('COMPLETION_STAT')
                ->max('event_date');

            $query->whereDate('event_date', $lastDate);
            $startDate = $lastDate;
            $endDate   = $lastDate;
        } else {
            if ($startDate) {
                $query->whereDate('event_date', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('event_date', '<=', $endDate);
            }
        }

        // ✅ Nouveau filtre call_type
        if ($callType) {
            $query->where('call_type', $callType);
        }

        $stats = $query->orderBy('event_date', 'desc')->get();

        return view('billing.complation', [
            'stats'      => $stats,
            'startDate'  => $startDate,
            'endDate'    => $endDate,
            'callType'   => $callType,
        ]);
    }

    // KPI Partner (improved: follow index() pattern)

public function kpip_old(Request $request)
{
    /* ===============================
       PARAMÈTRES
    =============================== */
    $view      = $request->get('view', 'day');      // day | month
    $direction = $request->get('direction', 'IN');  // IN | OUT

    /* ===============================
       GESTION DES DATES (SAFE)
    =============================== */
    try {
        $start = $request->filled('start_date')
            ? Carbon::parse($request->get('start_date'))->startOfDay()
            : now()->startOfMonth()->startOfDay();

        $end = $request->filled('end_date')
            ? Carbon::parse($request->get('end_date'))->endOfDay()
            : now()->endOfMonth()->endOfDay();
    } catch (\Exception $e) {
        $start = now()->startOfMonth()->startOfDay();
        $end   = now()->endOfMonth()->endOfDay();
    }

    // Sécurité si dates inversées
    if ($start->gt($end)) {
        [$start, $end] = [$end, $start];
    }

    // Strings pour SQL
    $startStr = $start->toDateString();
    $endStr   = $end->toDateString();

    /* ===============================
       SQL PERIOD (FIX CRITIQUE)
    =============================== */
    if ($view === 'month') {
        $periodSql = "SUBSTR(event_date,1,7)";
    } else {
        // 🔴 FIX PRINCIPAL : PAS event_date !
        $periodSql = "DATE(event_date)";
    }

    /* ===============================
       REQUÊTE KPI
    =============================== */
    $sql = "
        SELECT
            call_type AS CALL_DIRECTION,
            {$periodSql} AS PERIOD,
            partner_name AS PARTNER_NAME,
            SUM(attempt) AS ATTEMPTS,
            ROUND((SUM(completed) / NULLIF(SUM(attempt),0)) * 100, 2) AS NER,
            ROUND((SUM(answered) / NULLIF(SUM(attempt),0)) * 100, 2) AS ASR,
            IF(
                SUM(minutes)=0,
                0,
                ROUND((SUM(minutes)*60) / NULLIF(SUM(answered),0), 0)
            ) AS ACD_SEC
        FROM COMPLETION_STAT
        WHERE event_date >= :start
          AND event_date < DATE_ADD(:end, INTERVAL 1 DAY)
          AND partner_name <> 'Not Available'
          AND call_type = :direction
        GROUP BY CALL_DIRECTION, PERIOD, PARTNER_NAME
        ORDER BY CALL_DIRECTION, PERIOD, PARTNER_NAME
    ";

    $data = collect(DB::connection('inter_traffic')->select($sql, [
        'start'     => $startStr,
        'end'       => $endStr,
        'direction' => $direction,
    ]));

   // dd($data);
    /* ===============================
       STRUCTURE POUR LE PIVOT
    =============================== */
    $periods = $data->pluck('PERIOD')->unique()->sort()->values();
    $partners = $data->groupBy('PARTNER_NAME');

    /* ===============================
       RENDER
    =============================== */
    return view('kpi', [
        'partners'  => $partners,
        'periods'   => $periods,
        'view'      => $view,
        'direction' => $direction,
        'start'     => $startStr,
        'end'       => $endStr,
    ]);
}



public function kpip_old2(Request $request)
{
    /* ===============================
       PARAMÈTRES
    =============================== */
    $view = $request->get('view', 'day');          // day | month
    $directionUi = $request->get('direction', 'IN'); // IN | OUT

    // Mapping UI -> DB
    $directionMap = [
        'IN'  => 'Incoming',
        'OUT' => 'Outgoing',
    ];

    $directionDb = $directionMap[$directionUi] ?? 'Incoming';

    /* ===============================
       DATES
    =============================== */
    try {
        $start = $request->filled('start_date')
            ? Carbon::parse($request->get('start_date'))->toDateString()
            : now()->startOfMonth()->toDateString();

        $end = $request->filled('end_date')
            ? Carbon::parse($request->get('end_date'))->toDateString()
            : now()->endOfMonth()->toDateString();
    } catch (\Exception $e) {
        $start = now()->startOfMonth()->toDateString();
        $end   = now()->endOfMonth()->toDateString();
    }

    /* ===============================
       SQL SELON LA VUE
    =============================== */
    if ($view === 'month') {

        // MONTHLY
        $sql = "
            SELECT
                call_type AS CALL_DIRECTION,
                SUBSTR(event_date,1,7) AS PERIOD,
                partner_name AS PARTNER_NAME,
                SUM(attempt) AS ATTEMPTS,
                ROUND((SUM(completed) / NULLIF(SUM(attempt),0)) * 100, 2) AS NER,
                ROUND((SUM(answered) / NULLIF(SUM(attempt),0)) * 100, 2) AS ASR,
                IF(
                    SUM(minutes)=0,
                    0,
                    ROUND((SUM(minutes)*60) / NULLIF(SUM(answered),0))
                ) AS ACD_SEC
            FROM COMPLETION_STAT
            WHERE event_date BETWEEN :start AND :end
              AND partner_name NOT LIKE 'Not Available'
              AND call_type = :direction
            GROUP BY CALL_DIRECTION, PERIOD, PARTNER_NAME
            ORDER BY PERIOD, PARTNER_NAME
        ";

    } else {

        // DAILY
        $sql = "
            SELECT
                call_type AS CALL_DIRECTION,
                event_date AS PERIOD,
                partner_name AS PARTNER_NAME,
                SUM(attempt) AS ATTEMPTS,
                ROUND((SUM(completed) / NULLIF(SUM(attempt),0)) * 100, 2) AS NER,
                ROUND((SUM(answered) / NULLIF(SUM(attempt),0)) * 100, 2) AS ASR,
                IF(
                    SUM(minutes)=0,
                    0,
                    ROUND((SUM(minutes)*60) / NULLIF(SUM(answered),0))
                ) AS ACD_SEC
            FROM COMPLETION_STAT
            WHERE event_date BETWEEN :start AND :end
              AND partner_name NOT LIKE 'Not Available'
              AND call_type = :direction
            GROUP BY CALL_DIRECTION, PERIOD, PARTNER_NAME
            ORDER BY PERIOD, PARTNER_NAME
        ";
    }

    /* ===============================
       EXÉCUTION
    =============================== */
    $data = collect(DB::connection('inter_traffic')->select($sql, [
        'start'     => $start,
        'end'       => $end,
        'direction' => $directionDb,
    ]));

    /* ===============================
       STRUCTURATION POUR LA VUE
    =============================== */
    $periods  = $data->pluck('PERIOD')->unique()->sort()->values();
    $partners = $data->groupBy('PARTNER_NAME');

    /* ===============================
       RENDER
    =============================== */
    return view('kpi', [
        'partners'  => $partners,
        'periods'   => $periods,
        'view'      => $view,
        'start'     => $start,
        'end'       => $end,
        'direction' => $directionUi, // IMPORTANT pour le Blade
    ]);
}



public function kpip(Request $request)
{
    /* ===============================
       PARAMÈTRES
    =============================== */
    $view = $request->get('view', 'day'); // day | week | month | year
    $directionUi = $request->get('direction', 'IN'); // IN | OUT

    // Mapping UI -> DB
    $directionMap = [
        'IN'  => 'Incoming',
        'OUT' => 'Outgoing',
    ];
    $directionDb = $directionMap[$directionUi] ?? 'Incoming';

    /* ===============================
       DATES
    =============================== */
    try {
        $start = $request->filled('start_date')
            ? Carbon::parse($request->get('start_date'))->toDateString()
            : now()->startOfMonth()->toDateString();

        $end = $request->filled('end_date')
            ? Carbon::parse($request->get('end_date'))->toDateString()
            : now()->endOfMonth()->toDateString();
    } catch (\Exception $e) {
        $start = now()->startOfMonth()->toDateString();
        $end   = now()->endOfMonth()->toDateString();
    }

    /* ===============================
       PERIOD SQL
    =============================== */
    switch ($view) {
        case 'year':
            $periodSql = "YEAR(event_date)";
            break;

        case 'month':
            $periodSql = "SUBSTR(event_date,1,7)";
            break;

        case 'week':
            // Ex: 2025-W31
            $periodSql = "CONCAT(YEAR(event_date), '-W', LPAD(WEEK(event_date,1),2,'0'))";
            break;

        default: // day
            $periodSql = "event_date";
    }

    /* ===============================
       KPI SQL
    =============================== */
    $sql = "
        SELECT
            call_type AS CALL_DIRECTION,
            {$periodSql} AS PERIOD,
            partner_name AS PARTNER_NAME,
            SUM(attempt) AS ATTEMPTS,
            ROUND((SUM(completed) / NULLIF(SUM(attempt),0)) * 100, 2) AS NER,
            ROUND((SUM(answered) / NULLIF(SUM(attempt),0)) * 100, 2) AS ASR,
            IF(
                SUM(minutes)=0,
                0,
                ROUND((SUM(minutes)*60) / NULLIF(SUM(answered),0))
            ) AS ACD_SEC
        FROM COMPLETION_STAT
        WHERE event_date BETWEEN :start AND :end
          AND partner_name NOT LIKE 'Not Available'
          AND call_type = :direction
        GROUP BY CALL_DIRECTION, PERIOD, PARTNER_NAME
        ORDER BY PERIOD, PARTNER_NAME
    ";

    $data = collect(DB::connection('inter_traffic')->select($sql, [
        'start'     => $start,
        'end'       => $end,
        'direction' => $directionDb,
    ]));

    /* ===============================
       STRUCTURE POUR LE PIVOT
    =============================== */
    $periods  = $data->pluck('PERIOD')->unique()->sort()->values();
    //$partners = $data->groupBy('PARTNER_NAME');

      $partners = $data
    ->groupBy('PARTNER_NAME')
    ->sortByDesc(function ($rows) {
        return $rows->sum('ATTEMPTS');
    });

    return view('kpi.pivot', [
        'partners'  => $partners,
        'periods'   => $periods,
        'view'      => $view,
        'start'     => $start,
        'end'       => $end,
        'direction' => $directionUi,
    ]);
}


public function Kpin(Request $request)
{
    /* ===============================
       PARAMÈTRES
    =============================== */
    $view = $request->get('view', 'day'); // day | week | month | year
    $direction = $request->get('direction', 'ORIGINATED'); // ORIGINATED | DESTINATED

    try {
        $start = $request->filled('start_date')
            ? Carbon::parse($request->get('start_date'))->toDateString()
            : now()->startOfMonth()->toDateString();

        $end = $request->filled('end_date')
            ? Carbon::parse($request->get('end_date'))->toDateString()
            : now()->endOfMonth()->toDateString();
    } catch (\Exception $e) {
        $start = now()->startOfMonth()->toDateString();
        $end   = now()->endOfMonth()->toDateString();
    }

    /* ===============================
       PERIOD SQL
    =============================== */
    switch ($view) {
        case 'week':
            $periodSql = "YEARWEEK(event_date,1)";
            break;
        case 'month':
            $periodSql = "SUBSTR(event_date,1,7)";
            break;
        case 'year':
            $periodSql = "YEAR(event_date)";
            break;
        default:
            $periodSql = "event_date";
            break;
    }

    /* ===============================
       SQL SELON DIRECTION
    =============================== */
    if ($direction === 'ORIGINATED') {

        $sql = "
            SELECT
                call_type AS CALL_DIRECTION,
                {$periodSql} AS PERIOD,
                orig_net_name AS NETWORK_NAME,
                SUM(attempt) AS ATTEMPTS,
                SUM(minutes) AS MINUTES,
                ROUND((SUM(completed) / NULLIF(SUM(attempt),0)) * 100, 2) AS NER,
                ROUND((SUM(answered) / NULLIF(SUM(attempt),0)) * 100, 2) AS ASR,
                IF(
                    SUM(minutes)=0,
                    0,
                    ROUND((SUM(minutes)*60) / NULLIF(SUM(answered),0))
                ) AS ACD_SEC
            FROM COMPLETION_STAT
            WHERE event_date BETWEEN :start AND :end
              AND orig_net_name NOT LIKE 'Not Available'
              AND call_type LIKE 'Incoming'
            GROUP BY CALL_DIRECTION, PERIOD, NETWORK_NAME
            ORDER BY PERIOD, NETWORK_NAME
        ";

    } else {

        $sql = "
            SELECT
                call_type AS CALL_DIRECTION,
                {$periodSql} AS PERIOD,
                dest_net_name AS NETWORK_NAME,
                SUM(attempt) AS ATTEMPTS,
                SUM(minutes) AS MINUTES,
                ROUND((SUM(completed) / NULLIF(SUM(attempt),0)) * 100, 2) AS NER,
                ROUND((SUM(answered) / NULLIF(SUM(attempt),0)) * 100, 2) AS ASR,
                IF(
                    SUM(minutes)=0,
                    0,
                    ROUND((SUM(minutes)*60) / NULLIF(SUM(answered),0))
                ) AS ACD_SEC
            FROM COMPLETION_STAT
            WHERE event_date BETWEEN :start AND :end
              AND dest_net_name NOT LIKE 'Not Available'
              AND call_type LIKE 'Outgoing'
            GROUP BY CALL_DIRECTION, PERIOD, NETWORK_NAME
            ORDER BY PERIOD, NETWORK_NAME
        ";
    }

    /* ===============================
       EXÉCUTION
    =============================== */
    $data = collect(DB::connection('inter_traffic')->select($sql, [
        'start' => $start,
        'end'   => $end,
    ]));

    /* ===============================
       STRUCTURATION
    =============================== */
    $periods  = $data->pluck('PERIOD')->unique()->sort()->values();
  $networks = $data
    ->groupBy('NETWORK_NAME')
    ->sortByDesc(function ($rows) {
        return $rows->sum('ATTEMPTS');
    });

    /* ===============================
       RENDER
    =============================== */
    return view('kpi.network', [
        'networks'  => $networks,
        'periods'   => $periods,
        'view'      => $view,
        'direction' => $direction,
        'start'     => $start,
        'end'       => $end,
    ]);
}

public function KpinCarrier(Request $request)
{
    /* ===============================
       PARAMÈTRES
    =============================== */
    $view      = $request->get('view', 'day'); // day | week | month | year
    $direction = $request->get('direction', 'ORIGINATED'); // ORIGINATED | DESTINATED
    $network   = $request->get('network', 'ALL');
    $partner   = $request->get('partner', 'ALL');

    try {
        $start = $request->filled('start_date')
            ? Carbon::parse($request->get('start_date'))->toDateString()
            : now()->startOfMonth()->toDateString();

        $end = $request->filled('end_date')
            ? Carbon::parse($request->get('end_date'))->toDateString()
            : now()->endOfMonth()->toDateString();
    } catch (\Exception $e) {
        $start = now()->startOfMonth()->toDateString();
        $end   = now()->endOfMonth()->toDateString();
    }

    /* ===============================
       PERIOD SQL (CLÉ TECHNIQUE)
    =============================== */
    switch ($view) {
        case 'week':
            $periodSql = "YEARWEEK(event_date, 1)"; // ex: 202527
            break;
        case 'month':
            $periodSql = "DATE_FORMAT(event_date,'%Y-%m')";
            break;
        case 'year':
            $periodSql = "YEAR(event_date)";
            break;
        default:
            $periodSql = "event_date";
    }

    /* ===============================
       DIRECTION LOGIQUE
    =============================== */
    if ($direction === 'ORIGINATED') {
        $networkField = 'orig_net_name';
        $callType     = 'Incoming';
    } else {
        $networkField = 'dest_net_name';
        $callType     = 'Outgoing';
    }

    /* ===============================
       FILTRES
    =============================== */
    $filters = "
        event_date BETWEEN :start AND :end
        AND call_type = :callType
        AND {$networkField} NOT LIKE 'Not Available'
        AND partner_name NOT LIKE 'Not Available'
    ";

    if ($network !== 'ALL') {
        $filters .= " AND {$networkField} = :network";
    }

    if ($partner !== 'ALL') {
        $filters .= " AND partner_name = :partner";
    }

    /* ===============================
       SQL
    =============================== */
    $sql = "
        SELECT
            call_type AS CALL_DIRECTION,
            {$periodSql} AS PERIOD,
            {$networkField} AS NETWORK_NAME,
            partner_name AS PARTNER_NAME,
            SUM(attempt) AS ATTEMPTS,
            SUM(minutes) AS MINUTES,
            ROUND((SUM(completed)/NULLIF(SUM(attempt),0))*100,2) AS NER,
            ROUND((SUM(answered)/NULLIF(SUM(attempt),0))*100,2) AS ASR,
            IF(
                SUM(minutes)=0,
                0,
                ROUND((SUM(minutes)*60)/NULLIF(SUM(answered),0))
            ) AS ACD_SEC
        FROM COMPLETION_STAT
        WHERE {$filters}
        GROUP BY CALL_DIRECTION, PERIOD, NETWORK_NAME, PARTNER_NAME
    ";

    $bindings = [
        'start'    => $start,
        'end'      => $end,
        'callType' => $callType,
    ];

    if ($network !== 'ALL')  $bindings['network'] = $network;
    if ($partner !== 'ALL')  $bindings['partner'] = $partner;

    /* ===============================
       EXÉCUTION
    =============================== */
    $data = collect(DB::connection('inter_traffic')->select($sql, $bindings));

    /* ===============================
       LABEL SEMAINE (AFFICHAGE)
    =============================== */
    if ($view === 'week') {
        $data = $data->map(function ($row) {
            $year = substr($row->PERIOD, 0, 4);
            $week = substr($row->PERIOD, 4, 2);
            $row->PERIOD_LABEL = "{$year}-W{$week}";
            return $row;
        });
    }

    /* ===============================
       LISTES FILTRES
    =============================== */
    $networksList = $data->pluck('NETWORK_NAME')->unique()->sort()->values();
    $partnersList = $data->pluck('PARTNER_NAME')->unique()->sort()->values();

    /* ===============================
       PERIODS (AFFICHAGE)
    =============================== */
    $periods = $view === 'week'
        ? $data->pluck('PERIOD_LABEL')->unique()->sort()->values()
        : $data->pluck('PERIOD')->unique()->sort()->values();

    /* ===============================
       TRI PAR ATTEMPTS (IMPORTANT)
    =============================== */
    $networks = $data
        ->groupBy('NETWORK_NAME')
        ->sortByDesc(fn ($rows) => $rows->sum('ATTEMPTS')) // 🔥 TRI KPI
        ->map(fn ($rows) => $rows->groupBy('PARTNER_NAME'));

    /* ===============================
       RENDER
    =============================== */
    return view('kpi.network_carrier', compact(
        'networks',
        'networksList',
        'partnersList',
        'periods',
        'direction',
        'view',
        'start',
        'end',
        'network',
        'partner'
    ));
}








    private function dailySql()
    {
        return "
            SELECT
                call_type AS CALL_DIRECTION,
                DATE(event_date) AS PERIOD,
                DAY(event_date) AS DAY_NUM,
                partner_name AS PARTNER_NAME,
                SUM(attempt) AS ATTEMPTS,
                ROUND((SUM(completed) / NULLIF(SUM(attempt),0)) * 100, 2) AS NER,
                ROUND((SUM(answered) / NULLIF(SUM(attempt),0)) * 100, 2) AS ASR,
                IF(SUM(minutes)=0,0,
                    ROUND((SUM(minutes)*60) / NULLIF(SUM(answered),0),2)
                ) AS ACD_SEC
            FROM COMPLETION_STAT
            WHERE event_date BETWEEN :start AND :end
              AND partner_name <> 'Not Available'
            GROUP BY CALL_DIRECTION, PERIOD, DAY_NUM, PARTNER_NAME
            ORDER BY CALL_DIRECTION, PERIOD, PARTNER_NAME
        ";
    }

    private function monthlySql()
    {
        return "
            SELECT
                call_type AS CALL_DIRECTION,
                SUBSTR(event_date,1,7) AS PERIOD,
                partner_name AS PARTNER_NAME,
                SUM(attempt) AS ATTEMPTS,
                ROUND((SUM(completed) / NULLIF(SUM(attempt),0)) * 100, 2) AS NER,
                ROUND((SUM(answered) / NULLIF(SUM(attempt),0)) * 100, 2) AS ASR,
                IF(SUM(minutes)=0,0,
                    ROUND((SUM(minutes)*60) / NULLIF(SUM(answered),0),2)
                ) AS ACD_SEC
            FROM COMPLETION_STAT
            WHERE event_date BETWEEN :start AND :end
              AND partner_name <> 'Not Available'
            GROUP BY CALL_DIRECTION, PERIOD, PARTNER_NAME
            ORDER BY CALL_DIRECTION, PERIOD, PARTNER_NAME
        ";
    }


    public function partnerKpi(Request $request)
    {
        ini_set('memory_limit', '512M');
        if (!session('id')) return view('index');

        // Période par défaut : semaine précédente
        $start = $request->input('start_period')
            ? Carbon::parse($request->input('start_period'))->toDateString()
            : now()->subWeek()->startOfWeek()->toDateString();

        $end = $request->input('end_period')
            ? Carbon::parse($request->input('end_period'))->toDateString()
            : now()->subWeek()->endOfWeek()->toDateString();

        // Requête brute SQL
        $sql = "
        SELECT call_type,
            CONCAT(MIN(event_date), ' - ', MAX(event_date)) AS dates_range,
            CONCAT(YEAR(event_date), '-W', LPAD(WEEK(event_date, 3), 2, '0')) AS call_week,
            partner_name,
            SUM(attempt) AS attempt,
            CONCAT(ROUND((SUM(completed)/SUM(attempt))*100,2), '%') AS NER,
            CONCAT(ROUND((SUM(answered)/SUM(attempt))*100,2), '%') AS ACD,
            IF(SUM(minutes)=0,0,ROUND((SUM(minutes)*60)/SUM(answered))) AS ACD_SEC
        FROM COMPLETION_STAT
        WHERE event_date BETWEEN :start AND :end
            AND partner_name NOT LIKE 'Not Available'
        GROUP BY call_type, call_week, partner_name
        ORDER BY call_week DESC
    ";

        $data = DB::connection('inter_traffic')->select($sql, [
            'start' => $start,
            'end' => $end,
        ]);

        // Pagination manuelle
        $page = request('page', 1);
        $perPage = 100;
        $total = count($data);
        $items = array_slice($data, ($page - 1) * $perPage, $perPage);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);

        return view('billing.pkpi', [
            'data' => $paginator,
            'filters' => compact('start', 'end')
        ]);
    }



    public function billing(Request $request)
    {
        ini_set('memory_limit', '512M');

        if (!session('id')) {
            return view('index');
        }

        $query = DB::connection('inter_traffic')->table('BILLING_STAT');

        // 🔍 Appliquer les filtres
        if ($request->filled('direction')) {
            $query->where('direction', $request->direction);
        }

        if ($request->filled('operator')) {
            $query->where('carrier_name', $request->operator);
        }

        if ($request->filled('start_period')) {
            $start = Carbon::parse($request->start_period)->startOfMonth();
            $query->where('period', '>=', $start);
        }

        if ($request->filled('end_period')) {
            $end = Carbon::parse($request->end_period)->endOfMonth();
            $query->where('period', '<=', $end);
        }

        // ✅ Obtenir toutes les données (charge et revenue)
        $rawData = $query->select(
            'direction',
            'carrier_name',
            DB::raw('SUM(minutes) as minutes'),
            DB::raw('SUM(amount_cfa) as amount_cfa')
        )
            ->groupBy('direction', 'carrier_name')
            ->get();

        // ✅ Transformer les données en tableau dynamique [charge/revenue par opérateur]
        $groupedData = [];
        foreach ($rawData as $row) {
            $operator = $row->carrier_name;
            $dir = strtolower($row->direction); // charge ou revenue

            if (!isset($groupedData[$operator])) {
                $groupedData[$operator] = [
                    'carrier_name' => $operator,
                    'charge_minutes' => 0,
                    'charge_amount' => 0,
                    'revenue_minutes' => 0,
                    'revenue_amount' => 0,
                ];
            }

            if ($dir === 'charge') {
                $groupedData[$operator]['charge_minutes'] = $row->minutes;
                $groupedData[$operator]['charge_amount'] = $row->amount_cfa;
            } elseif ($dir === 'revenue') {
                $groupedData[$operator]['revenue_minutes'] = $row->minutes;
                $groupedData[$operator]['revenue_amount'] = $row->amount_cfa;
            }
        }

        // ✅ Calculer les totaux
        $totals = [
            'charge_minutes' => 0,
            'charge_amount' => 0,
            'revenue_minutes' => 0,
            'revenue_amount' => 0,
        ];

        foreach ($groupedData as $row) {
            $totals['charge_minutes'] += $row['charge_minutes'];
            $totals['charge_amount'] += $row['charge_amount'];
            $totals['revenue_minutes'] += $row['revenue_minutes'];
            $totals['revenue_amount'] += $row['revenue_amount'];
        }

        // ✅ Opérateurs disponibles pour le filtre
        $allOperators = DB::connection('inter_traffic')
            ->table('BILLING_STAT')
            ->distinct()
            ->pluck('carrier_name')
            ->sort()
            ->values();

        return view('billing.billing', [
            'data' => array_values($groupedData), // ✅ plus besoin de paginate ici
            'totals' => $totals,
            'operators' => $allOperators,
            'filters' => $request->all(), // pour réutiliser dans la vue
        ]);
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

            $daily_trend_incoming = DB::connection('inter_traffic')
                ->table('live_roaming_trend')
                ->where('direction', 'Incoming')
                ->orderBy('start_hour', 'ASC')
                ->get();


            $daily_trend_incoming_MAX = DB::connection('inter_traffic')
                ->table('live_roaming_trend')
                ->where('direction', '=', 'Incoming')
                ->max('duration_min');

            $daily_trend_incoming_MIN = DB::connection('inter_traffic')
                ->table('live_roaming_trend')
                ->where('direction', '=', 'Incoming')
                ->min('duration_min');

            $daily_trend_outgoing = DB::connection('inter_traffic')
                ->table('live_roaming_trend')
                ->selectRaw('*')
                ->where('direction', '=', 'Outgoing')
                ->orderBy('start_hour', 'ASC')
                ->get();

            return view('roaming.roaming', compact('daily_trend_incoming', 'daily_trend_outgoing', 'daily_trend_incoming_MIN', 'daily_trend_incoming_MAX'))->render();
        }
        return view('index');
    }
    public function add_credit()
    {
        $rcredits = Rcredit::orderBy('date', 'DESC')->get();

        //dd( $rcredits);
        return view('BI.add_credit', compact('rcredits'));
    }

    public function add_roaming_credit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|between:0,99999999999999999999.99',
            'date' => 'required|date|unique:rcredit,date',
        ], [
            'date.unique' => 'Un crédit journalier avec cette date existe déjà.',
        ]);

        $data = $request->only(['amount', 'date']);

        try {


            Rcredit::create($data);
            Journal::create([
                'action' => "Ajout du crédit journalier " . $data['amount'],
                'user_id' => session('id'),
            ]);
            return redirect()->back()->with('flash_message_success', 'Crédit ajouté avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('flash_message_error', 'Erreur lors de l\'ajout du crédit : ' . $e->getMessage());
        }
    }

    public function update_credit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|between:0,99999999999999999999.99',

        ]);

        $data = $request->only(['amount', 'id']);



        rcredit::where('id', $data['id'])->update(['amount' => $data['amount']]);

        Journal::create([
            'action' => "Modification du crédit journalier " . $data['amount'],
            'user_id' => session('id'),
        ]);

        return redirect()->back()->with('flash_message_success', 'Crédit mis à jour avec succès.');
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->back()->with('success', 'Fichier importé avec succès.');
    }

    public function interco_details(Request $request)
    {
        if (session('id') != null) {

            $reports = DB::connection('inter_traffic')
                ->table('BI_STAT')
                ->selectRaw("
                direction,
                start_date,
                SUM(minutes) AS total_minutes,
                SUM(amount_cfa) AS total_amount_XOF,
                SUM(roaming_minutes) AS roaming_minutes,
                SUM(roaming_amount_cfa) AS roaming_amount_XOF
            ")
                ->where('direction', 'like', 'revenue')
                ->groupBy('direction', 'start_date')
                ->orderBy('direction')
                ->orderBy('start_date')
                ->get();

            return view('BI.interco_details', compact('reports'))->render();
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
