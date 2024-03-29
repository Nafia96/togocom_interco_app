<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\Account;
use App\Models\Contestation;
use App\Models\Creditnote;
use App\Models\Journal;
use App\Models\Operation;
use App\Models\Resum;
use App\Models\User;
use Illuminate\Http\Request;

class OperationController extends Controller
{
    //Togocom to operator

    public function tgc_to_ope_invoice(Request $request)
    {


        $request->validate([

            'invoice_number' => 'required|string',
            'amount' => 'required|numeric|between:0,99999999999999999999.99',
            'call_volume' => 'required|numeric|between:0,9999999999999999999999.99',
            'number_of_call' => 'required|integer',
            'description' => 'nullable|string|max:500',

        ]);


        $data = $request->all();
     
        $operator = Operator::where('id', $data['id_operator'])->first();

        $op_account = Account::where('id_operator', $operator->id)->first();

        $tgc_account = Account::where('account_number', 000)->first();

        $resum = Resum::where([
            'id_operator' =>  $operator->id,
            'is_delete' =>  0,
            'period' => $data['period']
        ])->first();



        if ($resum == null) {

            $facture_name =0;

            if ($request->hasfile('the_file')) {
    
                $imageIcon = $request->file('the_file');
                $exten = $imageIcon->getClientOriginalExtension();
                $imageIconName = date('Y-m').'-'.$request->invoice_number . uniqid() . '.' . $exten;
                $destinationPath = public_path('/facture');
                $ulpoadImageSuccess = $imageIcon->move($destinationPath, $imageIconName);
                $facture_name = "/facture/" . $imageIconName;
            }
    


            $invoice = Invoice::create([
                //tgc_invoice is 1 if the invoice is Togocom own
                'tgc_invoice' => 1,
                'invoice_type' => $data['invoice_type'],
                'invoice_number' => $data['invoice_number'],
                'period' =>  $data['period'],
                'periodDate' => periodeDate($data['period']),
                'operator_id' =>  $operator->id,

                'invoice_date' =>  $data['invoice_date'],
                'call_volume' =>  $data['call_volume'],
                'number_of_call' =>  $data['number_of_call'],
                'add_by' => session('id'),
                'amount' => $data['amount'],
                'comment' =>  $data['comment'],
                'facture_name' =>  $facture_name,




            ]);




            if ($data['invoice_type'] == 'real') {

                $operation = Operation::create([
                    //Operation type 1 it mees that the facturation is receivable
                    'operation_type' => 1,
                    'account_number' => $op_account->account_number,
                    'operation_name' =>  'Facture de service voix (CREANCE)',
                    'comment' =>  $data['comment'],
                    'id_op_account' =>  $op_account->id,
                    'id_operator' =>  $operator->id,
                    'add_by' => session('id'),
                    'amount' => $data['amount'],
                    'new_debt' => $op_account->debt,
                    'new_receivable' => $op_account->receivable + $data['amount'],
                    'new_netting' => $op_account->netting + $data['amount'],
                    'invoice_type' => $data['invoice_type'],
                    'id_invoice' =>  $invoice->id,
                    'facture_name' =>  $facture_name,




                ]);



                Account::where(['id' => $tgc_account->id])->update([
                    'receivable' => $tgc_account->receivable + $data['amount'],

                ]);

                Account::where(['id' => $op_account->id])->update([
                    'receivable' => $operation->new_receivable,
                    'netting' => $operation->new_netting,

                ]);

                if ($operator->currency == 'EUR') {
                    $resum = Resum::create([

                        'service' => 'Facture de service voix',
                        'id_operator' => $operator->id,
                        'period' => $data['period'],
                        'periodDate' => periodeDate($data['period']),
                        'receivable' =>  $data['amount'],
                        'receivable_cfa' =>  $data['amount'] * $tgc_account->operator->euro_conversion,

                        'netting' => $operation->new_netting,
                        'id_invoice_1' =>  $invoice->id,
                        'id_invoice_2' =>  $invoice->id,
                        'id_operation_1' =>  $operation->id,
                        'id_operation_2' =>  $operation->id,
                        'facture_name1' =>  $facture_name,

                    ]);
                } elseif ($operator->currency == 'USD') {
                    $resum = Resum::create([

                        'service' => 'Facture de service voix',
                        'id_operator' => $operator->id,
                        'period' => $data['period'],
                        'periodDate' => periodeDate($data['period']),

                        'receivable' =>  $data['amount'],
                        'receivable_cfa' =>  $data['amount'] * $tgc_account->operator->dollar_conversion,

                        'netting' => $operation->new_netting,
                        'id_invoice_1' =>  $invoice->id,
                        'id_invoice_2' =>  $invoice->id,
                        'id_operation_1' =>  $operation->id,
                        'id_operation_2' =>  $operation->id,
                        'facture_name1' =>  $facture_name,

                    ]);
                } elseif ($operator->currency == 'XAF') {
                    $resum = Resum::create([

                        'service' => 'Facture de service voix',
                        'id_operator' => $operator->id,
                        'period' => $data['period'],
                        'periodDate' => periodeDate($data['period']),

                        'receivable' =>  $data['amount'],
                        'receivable_cfa' =>  $data['amount'] * $tgc_account->operator->xaf_conversion,

                        'netting' => $operation->new_netting,
                        'id_invoice_1' =>  $invoice->id,
                        'id_invoice_2' =>  $invoice->id,
                        'id_operation_1' =>  $operation->id,
                        'id_operation_2' =>  $operation->id,
                        'facture_name1' =>  $facture_name,

                    ]);
                } elseif ($operator->currency == 'XOF') {
                    $resum = Resum::create([

                        'service' => 'Facture de service voix',
                        'id_operator' => $operator->id,
                        'period' => $data['period'],
                        'periodDate' => periodeDate($data['period']),

                        'receivable' =>  $data['amount'],
                        'receivable_cfa' =>  $data['amount'],

                        'netting' => $operation->new_netting,
                        'id_invoice_1' =>  $invoice->id,
                        'id_invoice_2' =>  $invoice->id,
                        'id_operation_1' =>  $operation->id,
                        'id_operation_2' =>  $operation->id,
                        'facture_name1' =>  $facture_name,

                    ]);
                } else {

                    dd('la conversion concernant la devise de cet opérteur est inexistante. Mais la facture est enregisré...');
                }
            } elseif ($data['invoice_type'] == 'estimated') {

                $operation = Operation::create([
                    //Operation type 1 it meen that the facturation is receivable
                    'operation_type' => 1,
                    'account_number' => $op_account->account_number,
                    'operation_name' =>  'Facture de service voix (CREANCE)',
                    'comment' =>  $data['comment'],
                    'id_op_account' =>  $op_account->id,
                    'id_operator' =>  $operator->id,
                    'add_by' => session('id'),
                    'amount' => $data['amount'],
                    'new_debt' => $op_account->debt,
                    'new_receivable' => $op_account->receivable + $data['amount'],
                    'new_netting' => $op_account->netting + $data['amount'],
                    'invoice_type' => $data['invoice_type'],
                    'id_invoice' =>  $invoice->id,
                    'facture_name' =>  $facture_name,




                ]);

                Account::where(['id' => $tgc_account->id])->update([
                    'receivable' => $tgc_account->receivable + $data['amount'],

                ]);

                Account::where(['id' => $op_account->id])->update([
                    'receivable' => $operation->new_receivable,
                    'netting' => $operation->new_netting,

                ]);


                if ($operator->currency == 'EUR') {
                    $resum = Resum::create([

                        'service' => 'Facture de service voix',
                        'id_operator' => $operator->id,
                        'period' => $data['period'],
                        'periodDate' => periodeDate($data['period']),

                        'receivable' =>  $data['amount'],
                        'receivable_cfa' =>  $data['amount'] * $tgc_account->operator->euro_conversion,

                        'netting' => $operation->new_netting,

                        'id_operation_1' =>  $operation->id,
                        'id_operation_2' =>  $operation->id,
                        'facture_name1' =>  $facture_name,


                    ]);
                } elseif ($operator->currency == 'USD') {
                    $resum = Resum::create([

                        'service' => 'Facture de service voix',
                        'id_operator' => $operator->id,
                        'period' => $data['period'],
                        'periodDate' => periodeDate($data['period']),

                        'receivable' =>  $data['amount'],
                        'receivable_cfa' =>  $data['amount'] * $tgc_account->operator->dollar_conversion,

                        'netting' => $operation->new_netting,

                        'id_operation_1' =>  $operation->id,
                        'id_operation_2' =>  $operation->id,
                        'facture_name1' =>  $facture_name,


                    ]);
                } elseif ($operator->currency == 'XAF') {
                    $resum = Resum::create([

                        'service' => 'Facture de service voix',
                        'id_operator' => $operator->id,
                        'period' => $data['period'],
                        'periodDate' => periodeDate($data['period']),

                        'receivable' =>  $data['amount'],
                        'receivable_cfa' =>  $data['amount'] * $tgc_account->operator->xaf_conversion,

                        'netting' => $operation->new_netting,

                        'id_operation_1' =>  $operation->id,
                        'id_operation_2' =>  $operation->id,
                        'facture_name1' =>  $facture_name,


                    ]);
                } elseif ($operator->currency == 'XOF') {
                    $resum = Resum::create([

                        'service' => 'Facture de service voix',
                        'id_operator' => $operator->id,
                        'period' => $data['period'],
                        'periodDate' => periodeDate($data['period']),

                        'receivable' =>  $data['amount'],
                        'receivable_cfa' =>  $data['amount'],

                        'netting' => $operation->new_netting,

                        'id_operation_1' =>  $operation->id,
                        'id_operation_2' =>  $operation->id,
                        'facture_name1' =>  $facture_name,


                    ]);
                } else {

                    dd('la conversion concernant la devise de cet opérteur est inexistante. Mais la facture est enregisré...');
                }
            }





            Journal::create([
                'action' => "Ajout de la facture N° " . $invoice->invoice_number . " pour l'opérateur " . $operator->name . " d'un montant de " . $data['amount'] . $operator->currency,
                'user_id' => session('id'),
            ]);


            return redirect()->route('ope_dashboard', ['id' => $operator->id])->with('flash_message_success', 'Facture ajouté avec succès!');
        } else {

            return redirect()->back()->with('error', 'Cette facture existe déjà!');
        }
    }

    //Ajout d'une dette à Togocom

    public function ope_to_tgc_invoice(Request $request)
    {
        $data = $request->all();

        $request->validate([

            'invoice_number' => 'required|string',
            'amount' => 'required|numeric|between:0,99999999999999999999.99',
            'call_volume' => 'required|numeric|between:0,99999999999999999999.99',
            'description' => 'nullable|string|max:500',

        ]);


        $data = $request->all();

        $operator = Operator::where('id', $data['id_operator'])->first();

        $op_account = Account::where('id_operator', $operator->id)->first();

        $resum = Resum::where([
            'id_operator' =>  $operator->id,
            'period' => $data['period'],
            'is_delete' => 0
        ])->first();


        $tgc_account = Account::where('account_number', 000)->first();

    


        if ($resum != null) {

            $facture_name =0;
            
        if ($request->hasfile('the_file')) {

            $imageIcon = $request->file('the_file');
            $exten = $imageIcon->getClientOriginalExtension();
            $imageIconName = date('Y-m').'-'.$request->invoice_number . uniqid() . '.' . $exten;
            $destinationPath = public_path('/facture');
            $ulpoadImageSuccess = $imageIcon->move($destinationPath, $imageIconName);
            $facture_name = "/facture/" . $imageIconName;
        }


            if ($resum->debt == null) {

                $invoice = Invoice::create([
                    //tgc_invoice is 2 if the invoice is debt
                    'tgc_invoice' => 2,
                    'invoice_type' => $data['invoice_type'],
                    'invoice_number' => $data['invoice_number'],
                    'period' =>  $data['period'],
                    'periodDate' => periodeDate($data['period']),
                    'operator_id' =>  $operator->id,
                    'invoice_date' =>  $data['invoice_date'],
                    'call_volume' =>  $data['call_volume'],
                    'number_of_call' =>  $data['number_of_call'],
                    'add_by' => session('id'),
                    'amount' => $data['amount'],
                    'comment' =>  $data['comment'],
                    'facture_name' =>  $facture_name,



                ]);




                if ($data['invoice_type'] == 'real') {

                    $operation = Operation::create([
                        //Operation type 2 it mees that the facturation is debt
                        'operation_type' => 2,
                        'account_number' => $op_account->account_number,
                        'operation_name' =>  'Facture de service voix (DETTE)',
                        'comment' =>  $data['comment'],
                        'id_invoice' =>  $invoice->id,
                        'id_op_account' =>  $op_account->id,
                        'id_operator' =>  $operator->id,
                        'add_by' => session('id'),
                        'amount' => $data['amount'],
                        'new_receivable' => $op_account->receivable,
                        'new_debt' => $op_account->debt + $data['amount'],
                        'new_netting' => $op_account->netting - $data['amount'],
                        'invoice_type' => $data['invoice_type'],
                        'facture_name' =>  $facture_name,



                    ]);

                    Account::where(['id' => $tgc_account->id])->update([
                        'debt' => $tgc_account->debt + $data['amount'],

                    ]);

                    Account::where(['id' => $op_account->id])->update([
                        'debt' => $operation->new_debt,
                        'netting' => $operation->new_netting,

                    ]);


                    if ($operator->currency == 'EUR') {
                        Resum::where([
                            'id_operator' =>  $operator->id,
                            'period' => $data['period'],
                            'is_delete' => 0

                        ])->update([

                            'debt' =>  $data['amount'],
                            'debt_cfa' =>  $data['amount'] * $tgc_account->operator->euro_conversion,


                            'netting' => $resum->netting - $data['amount'],
                            'id_operation_2' =>  $operation->id,
                            
                            'facture_name2' =>  $facture_name,

                        ]);
                    } elseif ($operator->currency == 'USD') {
                        Resum::where([
                            'id_operator' =>  $operator->id,
                            'period' => $data['period'],
                            'is_delete' => 0

                        ])->update([
                            'debt' =>  $data['amount'],
                            'debt_cfa' =>  $data['amount'] * $tgc_account->operator->dollar_conversion,


                            'netting' => $resum->netting - $data['amount'],
                            'id_operation_2' =>  $operation->id,
                            'facture_name2' =>  $facture_name,


                        ]);
                    } elseif ($operator->currency == 'XAF') {
                        Resum::where([
                            'id_operator' =>  $operator->id,
                            'period' => $data['period'],
                            'is_delete' => 0
                        ])->update([

                            'debt' =>  $data['amount'],
                            'debt_cfa' =>  $data['amount'] * $tgc_account->operator->xaf_conversion,


                            'netting' => $resum->netting - $data['amount'],
                            'id_operation_2' =>  $operation->id,
                            'facture_name2' =>  $facture_name,


                        ]);
                    } elseif ($operator->currency == 'XOF') {
                        Resum::where([
                            'id_operator' =>  $operator->id,
                            'period' => $data['period'],
                            'is_delete' => 0
                        ])->update([

                            'debt' =>  $data['amount'],
                            'debt_cfa' =>  $data['amount'],


                            'netting' => $resum->netting - $data['amount'],
                            'id_operation_2' =>  $operation->id,
                            'facture_name2' =>  $facture_name,

                        ]);
                    } else {

                        dd('la conversion concernant la devise de cet opérteur est inexistante. Mais la facture est enregisré...');
                    }
                } elseif ($data['invoice_type'] == 'estimated') {

                    $operation = Operation::create([
                        //Operation type 2 it mees that the facturation is debt
                        'operation_type' => 2,
                        'account_number' => $op_account->account_number,
                        'operation_name' =>  'Facture de service voix (DETTE)',
                        'comment' =>  $data['comment'],
                        'id_invoice' =>  $invoice->id,
                         
                        'id_op_account' =>  $op_account->id,
                        'id_operator' =>  $operator->id,
                        'add_by' => session('id'),
                        'amount' => $data['amount'],
                        'new_receivable' => $op_account->receivable,
                        'new_debt' => $op_account->debt + $data['amount'],
                        'new_netting' => $op_account->netting - $data['amount'],
                        'invoice_type' => $data['invoice_type'],
                        'facture_name' =>  $facture_name,



                    ]);

                    Account::where(['id' => $tgc_account->id])->update([
                        'debt' => $tgc_account->debt + $data['amount'],

                    ]);

                    Account::where(['id' => $op_account->id])->update([
                        'debt' => $operation->new_debt,
                        'netting' => $operation->new_netting,

                    ]);


                    if ($operator->currency == 'EUR') {
                        Resum::where([

                            
                            'id_operator' =>  $operator->id,
                            'period' => $data['period'],
                            'is_delete' => 0
                        ])->update([

                            'debt' =>  $data['amount'],
                            'debt_cfa' =>  $data['amount'] * $tgc_account->operator->euro_conversion,
                            'facture_name2' =>  $facture_name,


                            'netting' => $resum->netting - $data['amount'],
                            'id_operation_2' =>  $operation->id,

                        ]);
                    } elseif ($operator->currency == 'USD') {
                        Resum::where([
                            'id_operator' =>  $operator->id,
                            'period' => $data['period'],
                            'is_delete' => 0

                        ])->update([

                            
                            'debt' =>  $data['amount'],
                            'debt_cfa' =>  $data['amount'] * $tgc_account->operator->dollar_conversion,
                            'facture_name2' =>  $facture_name,


                            'netting' => $resum->netting - $data['amount'],
                            'id_operation_2' =>  $operation->id,


                        ]);
                    } elseif ($operator->currency == 'XAF') {
                        Resum::where([
                            'id_operator' =>  $operator->id,
                            'period' => $data['period'],
                            'is_delete' => 0

                        ])->update([
                            'debt' =>  $data['amount'],
                            'debt_cfa' =>  $data['amount'] * $tgc_account->operator->xaf_conversion,

                            'facture_name2' =>  $facture_name,

                            'netting' => $resum->netting - $data['amount'],
                            'id_operation_2' =>  $operation->id,



                        ]);
                    } elseif ($operator->currency == 'XOF') {
                        Resum::where([
                            'id_operator' =>  $operator->id,
                            'period' => $data['period'],
                            'is_delete' => 0
                        ])->update([

                            'debt' =>  $data['amount'],
                            'debt_cfa' =>  $data['amount'],

                            'facture_name2' =>  $facture_name,

                            'netting' => $resum->netting - $data['amount'],
                            'id_operation_2' =>  $operation->id,

                        ]);
                    } else {

                        dd('la conversion concernant la devise de cet opérteur est inexistante. Mais la facture est enregisré...');
                    }
                }






                Journal::create([
                    'action' => "Ajout de la facture N° " . $invoice->invoice_number . " de l'opérateur " . $operator->name . " à TOGOCOM d'un montant de " . $data['amount'] . $operator->currency,
                    'user_id' => session('id'),
                ]);


                return redirect()->route('ope_dashboard', ['id' => $operator->id])->with('flash_message_success', 'Facture ajouté avec succès!');
            } else {

                return redirect()->back()->with('error', 'Cette facture existe déjà!');
            }
        } else {

            return redirect()->back()->with('error', 'La facture de créance pour le mois sélectionné est inexistante, veuillez ajouter la facture de créance avant la dette!');
        }
    }

    //Togocom to operator

    public function cancel_operation($id_operation)
    {

        $operation = Operation::where('id', $id_operation)->first();


        $op_account = Account::where('id_operator', $operation->operator->id)->first();

        $tgc_account = Account::where('account_number', 000)->first();

        if ($operation->operation_type == 2) {


            Invoice::where([
                'id' =>  $operation->invoice->id,
                'is_delete' => 0

            ])->update([
                'is_delete' =>  1,

            ]);

            Operation::where([
                'id' =>  $operation->id,
                'is_delete' => 0

            ])->update([
                'is_delete' =>  1,

            ]);
            
            Account::where(['id' => $tgc_account->id])->update([
                'debt' => $tgc_account->debt - $operation->amount,

            ]);

            Account::where(['id' => $op_account->id])->update([
                'debt' => $op_account->debt - $operation->amount,
                'netting' => $op_account->netting + $operation->amount,

            ]);

            Resum::where([
                'id' =>  $operation->resum2->id,
                'is_delete' => 0

            ])->update([
                'debt' =>  null,
                'debt_cfa' =>  null,
                'netting' => $operation->resum2->netting + $operation->amount,

            ]);


           
        } elseif ($operation->operation_type == 1) {

            $resum = Resum::where('id', $operation->resum->id)->first();

            Invoice::where([
                'id' =>  $operation->invoice->id,
            ])->update([
                'is_delete' =>  1,

            ]);

            Operation::where([
                'id' =>  $operation->id,
            ])->update([
                'is_delete' =>  1,

            ]);

            Resum::where([
                'id' =>  $operation->resum->id,
            ])->update([
                'is_delete' =>  1,

            ]);


            Account::where(['id' => $tgc_account->id])->update([
                'receivable' => $tgc_account->receivable - $operation->amount,

            ]);

            Account::where(['id' => $op_account->id])->update([
                'receivable' => $op_account->receivable - $operation->amount,
                'netting' => $op_account->netting - $operation->amount,

            ]);
            if ($resum->operation2->id != $resum->operation1->id) {



                Invoice::where([
                    'id' =>  $resum->operation2->invoice->id,
                ])->update([
                    'is_delete' =>  1,

                ]);

                Operation::where([
                    'id' =>  $resum->operation2->id,
                ])->update([
                    'is_delete' =>  1,

                ]);


                Account::where(['id' => $tgc_account->id])->update([
                    'debt' => $tgc_account->debt - $resum->operation2->amount,

                ]);

                Account::where(['id' => $op_account->id])->update([
                    'debt' => $op_account->debt - $resum->operation2->amount,
                    'netting' => $op_account->netting + $resum->operation2->amount,

                ]);
            }
        } elseif ($operation->operation_type == 3) {

            if ($operation->invoice_type == 'Encaissement') {


                Invoice::where([
                    'id' =>  $operation->invoice->id,
                ])->update([
                    'is_delete' =>  1,

                ]);

                Operation::where([
                    'id' =>  $operation->id,
                ])->update([
                    'is_delete' =>  1,

                ]);

                Resum::where([
                    'id' =>  $operation->resum->id,
                ])->update([
                    'is_delete' =>  1,

                ]);


                Account::where(['id' => $tgc_account->id])->update([
                    'receivable' => $tgc_account->receivable + $operation->amount,

                ]);

                Account::where(['id' => $op_account->id])->update([
                    'receivable' => $op_account->receivable + $operation->amount,
                    'netting' => $op_account->netting + $operation->amount,

                ]);
            } else {

                Invoice::where([
                    'id' =>  $operation->invoice->id,
                ])->update([
                    'is_delete' =>  1,

                ]);

                Operation::where([
                    'id' =>  $operation->id,
                ])->update([
                    'is_delete' =>  1,

                ]);

                Resum::where([
                    'id' =>  $operation->resum->id,
                ])->update([
                    'is_delete' =>  1,

                ]);


                Account::where(['id' => $tgc_account->id])->update([
                    'debt' => $tgc_account->debt + $operation->amount,

                ]);

                Account::where(['id' => $op_account->id])->update([
                    'debt' => $op_account->debt + $operation->amount,
                    'netting' => $op_account->netting - $operation->amount,

                ]);
            }
        } elseif ($operation->operation_type == 4) {
            //Si c'est une note de credit
        }

        Journal::create([
            'action' => "Anullation de l'opperation du facture N° " . $operation->invoice->invoice_number . " pour l'opérateur " . $operation->operator->name . " d'un montant de " . $operation->amount . $operation->operator->currency,
            'user_id' => session('id'),
        ]);


        return redirect()->back()->with('flash_message_success', 'Opperation annulée avec succès!');
    }

    //Mise à jour de la facture estimé
    public function update_estimated_invoice(Request $request)
    {

        $data = $request->all();

        $request->validate([

            'amount' => 'required|numeric|between:0,99999999999999999999.99',

        ]);


        $data = $request->all();

        $operator = Operator::where('id', $data['id_operator'])->first();

        $op_account = Account::where('id_operator', $operator->id)->first();


        $tgc_account = Account::where('account_number', 000)->first();

        $resum = Resum::where('id', $data['id_resum'])->first();

        $invoice = Invoice::where('id', $data['invoice_id'])->first();

        $operation = Operation::where(['id' => $data['operation_id'], 'is_delete' => 0])->first();

        $new_debt = $resum->debt -  $data['amount'];

        //dd($resum->netting + $resum->debt -  $data['amount']);




        if ($operator->currency == 'EUR') {
            Resum::where([
                'id' =>  $data['id_resum'],
                'is_delete' => 0

            ])->update([
                'debt' =>  $data['amount'],
                'debt_cfa' =>  $data['amount'] * $tgc_account->operator->euro_conversion,

                'netting' => $resum->netting + $resum->debt -  $data['amount'],

            ]);
        } elseif ($operator->currency == 'USD') {
            Resum::where([
                'id' =>  $data['id_resum'],
                'is_delete' => 0

            ])->update([
                'debt' =>  $data['amount'],
                'debt_cfa' =>  $data['amount'] * $tgc_account->operator->dollar_conversion,

                'netting' => $resum->netting + $resum->debt -  $data['amount'],

            ]);
        } elseif ($operator->currency == 'XAF') {
            Resum::where([
                'id' =>  $data['id_resum'],
                'is_delete' => 0

            ])->update([
                'debt' =>  $data['amount'],
                'debt_cfa' =>  $data['amount'] * $tgc_account->operator->xaf_conversion,

                'netting' => $resum->netting + $resum->debt -  $data['amount'],

            ]);
        } elseif ($operator->currency == 'XOF') {
            Resum::where([
                'id' =>  $data['id_resum'],
                'is_delete' => 0

            ])->update([
                'debt' =>  $data['amount'],
                'debt_cfa' =>  $data['amount'],

                'netting' => $resum->netting + $resum->debt -  $data['amount'],

            ]);
        }

        Invoice::where([
            'id' =>  $data['invoice_id'],
            'is_delete' => 0

        ])->update([
            'amount' =>  $data['amount'],
            'invoice_type' => 'real',

        ]);



        Operation::where([
            'id' =>  $data['operation_id'],
            'is_delete' => 0

        ])->update([
            'invoice_type' => 'real',
            'new_debt' => ($op_account->debt - $operation->amount) + $data['amount'],
            'new_netting' => ($op_account->netting +  $operation->amount) - $data['amount'],
        ]);


        //  dd('Operation ok');


        Account::where(['id' => $tgc_account->id])->update([
            'debt' => ($tgc_account->debt - $operation->amount) + $data['amount'],

        ]);

        Account::where(['id' => $op_account->id])->update([
            'debt' => ($op_account->debt - $operation->amount) + $data['amount'],
            'netting' => ($op_account->netting +  $operation->amount) - $data['amount'],

        ]);


        Journal::create([
            'action' => "Mise à jours de la facture (Estimé) N° " . $invoice->invoice_number . " de l'opérateur " . $operator->name . " à TOGOCOM qui était estimé à : " . $invoice->debt . $operator->currency . "  est changer par : " . $data['amount'] . $operator->currency,
            'user_id' => session('id'),
        ]);


        return redirect()->route('ope_dashboard', ['id' => $operator->id])->with('flash_message_success', 'Mises à jour effectuées avec succès!');
    }

    //Mise à jour de la facture estimé
    public function update_all_invoice(Request $request)
    {

        $data = $request->all();

        $request->validate([

            'amount' => 'required|numeric|between:0,99999999999999999999.99',

        ]);


        $data = $request->all();

        $operator = Operator::where('id', $data['id_operator'])->first();

        $op_account = Account::where('id_operator', $operator->id)->first();


        $tgc_account = Account::where('account_number', 000)->first();

        $operation = Operation::where(['id' => $data['operation_id'], 'is_delete' => 0])->first();


        if ($operation->operation_type == 2) {

            $id_resum = $operation->resum2->id;


            $resum = Resum::where('id', $id_resum)->first();

            $invoice = Invoice::where('id', $data['invoice_id'])->first();




            if ($operator->currency == 'EUR') {
                Resum::where([
                    'id' =>   $id_resum,
                    'is_delete' => 0

                ])->update([
                    'debt' =>  $data['amount'],
                    'debt_cfa' =>  $data['amount'] * $tgc_account->operator->euro_conversion,

                    'netting' => $resum->netting + $resum->debt -  $data['amount'],

                ]);
            } elseif ($operator->currency == 'USD') {
                Resum::where([
                    'id' =>   $id_resum,
                    'is_delete' => 0

                ])->update([
                    'debt' =>  $data['amount'],
                    'debt_cfa' =>  $data['amount'] * $tgc_account->operator->dollar_conversion,

                    'netting' => $resum->netting + $resum->debt -  $data['amount'],

                ]);
            } elseif ($operator->currency == 'XAF') {
                Resum::where([
                    'id' =>   $id_resum,
                    'is_delete' => 0

                ])->update([
                    'debt' =>  $data['amount'],
                    'debt_cfa' =>  $data['amount'] * $tgc_account->operator->xaf_conversion,

                    'netting' => $resum->netting + $resum->debt -  $data['amount'],

                ]);
            } elseif ($operator->currency == 'XOF') {
                Resum::where([
                    'id' =>   $id_resum,
                    'is_delete' => 0

                ])->update([
                    'debt' =>  $data['amount'],
                    'debt_cfa' =>  $data['amount'],

                    'netting' => $resum->netting + $resum->debt -  $data['amount'],

                ]);
            }

            Invoice::where([
                'id' =>  $data['invoice_id'],
            ])->update([
                'amount' =>  $data['amount'],
                'invoice_number' =>  $data['invoice_number'],
                'period' =>  $data['period'],
                'periodDate' => periodeDate($data['period']),

                'invoice_date' =>  $data['invoice_date'],
                'call_volume' =>  $data['call_volume'],
                'comment' =>  $data['comment'],
                'number_of_call' =>  $data['number_of_call'],
                'invoice_type' => $data['invoice_type'],

            ]);



            Operation::where([
                'id' =>  $data['operation_id'],
            ])->update([
                'comment' =>  $data['comment'],
                'amount' =>  $data['amount'],

                'invoice_type' =>  $data['invoice_type'],
                'new_debt' => ($op_account->debt - $operation->amount) + $data['amount'],
                'new_netting' => ($op_account->netting +  $operation->amount) - $data['amount'],
            ]);


            //  dd('Operation ok');


            Account::where(['id' => $tgc_account->id])->update([
                'debt' => ($tgc_account->debt - $operation->amount) + $data['amount'],

            ]);

            Account::where(['id' => $op_account->id])->update([
                'debt' => ($op_account->debt - $operation->amount) + $data['amount'],
                'netting' => ($op_account->netting +  $operation->amount) - $data['amount'],

            ]);
        } else {


            $id_resum = $operation->resum->id;


            $resum = Resum::where('id', $id_resum)->first();

            $invoice = Invoice::where('id', $data['invoice_id'])->first();



            if ($operator->currency == 'EUR') {
                Resum::where([
                    'id' =>   $id_resum,
                    'is_delete' => 0

                ])->update([
                    'receivable' =>  $data['amount'],
                    'receivable_cfa' =>  $data['amount'] * $tgc_account->operator->euro_conversion,

                    'netting' => $resum->netting - $resum->receivable +  $data['amount'],

                ]);
            } elseif ($operator->currency == 'USD') {
                Resum::where([
                    'id' =>   $id_resum,
                    'is_delete' => 0

                ])->update([
                    'receivable' =>  $data['amount'],
                    'receivable_cfa' =>  $data['amount'] * $tgc_account->operator->dollar_conversion,

                    'netting' => $resum->netting - $resum->receivable +  $data['amount'],

                ]);
            } elseif ($operator->currency == 'XAF') {
                Resum::where([
                    'id' =>   $id_resum,
                    'is_delete' => 0

                ])->update([
                    'receivable' =>  $data['amount'],
                    'receivable_cfa' =>  $data['amount'] * $tgc_account->operator->xaf_conversion,

                    'netting' => $resum->netting - $resum->receivable +  $data['amount'],

                ]);
            } elseif ($operator->currency == 'XOF') {
                Resum::where([
                    'id' =>   $id_resum,
                    'is_delete' => 0

                ])->update([
                    'receivable' =>  $data['amount'],
                    'receivable_cfa' =>  $data['amount'],

                    'netting' => $resum->netting - $resum->receivable +  $data['amount'],

                ]);
            }

            Invoice::where([
                'id' =>  $data['invoice_id'],
            ])->update([
                'amount' =>  $data['amount'],
                'invoice_number' =>  $data['invoice_number'],
                'period' =>  $data['period'],
                'periodDate' => periodeDate($data['period']),

                'invoice_date' =>  $data['invoice_date'],
                'call_volume' =>  $data['call_volume'],
                'comment' =>  $data['comment'],
                'number_of_call' =>  $data['number_of_call'],
                'invoice_type' => $data['invoice_type'],

            ]);



            Operation::where([
                'id' =>  $data['operation_id'],
            ])->update([
                'comment' =>  $data['comment'],
                'amount' =>  $data['amount'],

                'invoice_type' =>  $data['invoice_type'],
                'new_receivable' => ($op_account->receivable - $operation->amount) + $data['amount'],
                'new_netting' => ($op_account->netting -  $operation->amount) + $data['amount'],
            ]);


            //  dd('Operation ok');


            Account::where(['id' => $tgc_account->id])->update([
                'receivable' => ($tgc_account->receivable - $operation->amount) + $data['amount'],

            ]);

            Account::where(['id' => $op_account->id])->update([
                'receivable' => ($op_account->receivable - $operation->amount) + $data['amount'],
                'netting' => ($op_account->netting -  $operation->amount) + $data['amount'],

            ]);
        }

        Journal::create([
            'action' => "Mise à jours de la facture N° " . $invoice->invoice_number,
            'user_id' => session('id'),
        ]);

        return redirect()->back()->with('flash_message_success', 'Mises à jour effectuées avec succès!');
    }

    //Ajout d'une contestation de facture
    public function add_contestation(Request $request)
    {

        $data = $request->all();

        $request->validate([

            'amount' => 'required|numeric|between:0,99999999999999999999.99',
            'description' => 'nullable|string|max:500',


        ]);
        $operator = Operator::where('id', $data['id_operator'])->first();
        $invoice = Invoice::where('id', $data['invoice_id'])->first();


        $data = $request->all();


        Contestation::create([
            'id_operator' => $data['id_operator'],
            'operation_id' => $data['operation_id'],
            'id_invoice' => $data['invoice_id'],
            'amount' =>  $data['amount'],
            'contesation_date' => $data['contesation_date'],
            'comment' =>  $data['comment'],

        ]);

        Invoice::where([
            'id' =>  $data['invoice_id'],
        ])->update([

            'invoice_type' => 'litigious',

        ]);


        Operation::where([
            'id' =>  $data['operation_id'],
        ])->update([

            'invoice_type' => 'litigious',

        ]);




        Journal::create([
            'action' => "Contestation d'un montant de : " . $data['amount'] . $operator->currency . " de la facture N°:" . $invoice->invoice_number,
            'user_id' => session('id'),
        ]);


        return redirect()->back()->with('flash_message_success', 'Contestation ajouté avec avec succès!');
    }

    // Ajout d'une note de credit


    public function add_cn(Request $request)
    {

        $data = $request->all();

        $request->validate([

            'amount' => 'required|numeric|between:0,99999999999999999999.99',
            'description' => 'nullable|string|max:500',


        ]);
        $operator = Operator::where('id', $data['id_operator'])->first();

        $invoice = Invoice::where('id', $data['invoice_id'])->first();

        $op_account = Account::where('id_operator', $operator->id)->first();

        $tgc_account = Account::where('account_number', 000)->first();

        $data = $request->all();


        if ($data['cn_type'] == 'debt') {

            Creditnote::create([
                'id_operator' => $data['id_operator'],
                'operation_id' => $data['operation_id'],
                'id_invoice' => $data['invoice_id'],
                'contestation_id' => $data['contestation_id'],
                'debt' =>  $data['amount'],
                'comment' =>  $data['comment'],

            ]);

            Invoice::where([
                'id' =>  $data['invoice_id'],
            ])->update([

                'invoice_type' => 'real',

            ]);

            Operation::where([
                'id' =>  $data['operation_id'],
            ])->update([

                'invoice_type' => 'real',
                'new_debt' => $op_account->debt - $data['amount'],
                'new_netting' => $op_account->netting + $data['amount'],

            ]);



            // Le compte de togocom
            Account::where(['id' => $tgc_account->id])->update([
                'debt' => $tgc_account->debt - $data['amount'],

            ]);

            //Le compte de l'opeateur en question
            Account::where(['id' => $op_account->id])->update([
                'debt' => $op_account->debt - $data['amount'],
                'netting' => $op_account->netting + $data['amount'],

            ]);



            if ($operator->currency == 'EUR') {
                Resum::create([
                    'id_operator' => $data['id_operator'],
                    'id_operation_1' => $data['operation_id'],
                    'id_operation_2' => $data['operation_id'],
                    'debt' =>  -$data['amount'],
                    'debt_cfa' =>  -$data['amount'] * $tgc_account->operator->euro_conversion,

                    'comment' =>  $data['comment'],
                    'service' =>  'Note de crédit ' . $invoice->period,
                    'period' =>  $invoice->period,
                    'periodDate' => periodeDate($invoice->period),

                    'netting' => $op_account->netting + $data['amount'],


                ]);
            } elseif ($operator->currency == 'USD') {
                Resum::create([
                    'id_operator' => $data['id_operator'],
                    'id_operation_1' => $data['operation_id'],
                    'id_operation_2' => $data['operation_id'],
                    'debt' =>  -$data['amount'],
                    'debt_cfa' => -$data['amount'] * $tgc_account->operator->dollar_conversion,

                    'comment' =>  $data['comment'],
                    'service' =>  'Note de crédit ' . $invoice->period,
                    'period' =>  $invoice->period,
                    'periodDate' => periodeDate($invoice->period),

                    'netting' => $op_account->netting + $data['amount'],


                ]);
            } elseif ($operator->currency == 'XAF') {
                Resum::create([
                    'id_operator' => $data['id_operator'],
                    'id_operation_1' => $data['operation_id'],
                    'id_operation_2' => $data['operation_id'],
                    'debt' =>  -$data['amount'],
                    'debt_cfa' =>  -$data['amount'] * $tgc_account->operator->xaf_conversion,

                    'comment' =>  $data['comment'],
                    'service' =>  'Note de crédit ' . $invoice->period,
                    'period' =>  $invoice->period,
                    'periodDate' => periodeDate($invoice->period),

                    'netting' => $op_account->netting + $data['amount'],


                ]);
            } elseif ($operator->currency == 'XOF') {
                Resum::create([
                    'id_operator' => $data['id_operator'],
                    'id_operation_1' => $data['operation_id'],
                    'id_operation_2' => $data['operation_id'],
                    'debt' =>  -$data['amount'],
                    'debt_cfa' =>  -$data['amount'],

                    'comment' =>  $data['comment'],
                    'service' =>  'Note de crédit ' . $invoice->period,
                    'period' =>  $invoice->period,
                    'periodDate' => periodeDate($invoice->period),

                    'netting' => $op_account->netting + $data['amount'],


                ]);
            }







            Journal::create([
                'action' => "Ajout d'une note de credit, d'un montant de : " . $data['amount'] . $operator->currency . " de la facture N°:" . $invoice->invoice_number . " en diminution de dette",
                'user_id' => session('id'),
            ]);
        } else {

            Creditnote::create([
                'id_operator' => $data['id_operator'],
                'operation_id' => $data['operation_id'],
                'id_invoice' => $data['invoice_id'],
                'contestation_id' => $data['contestation_id'],
                'receivable' =>  $data['amount'],
                'comment' =>  $data['comment'],

            ]);

            Invoice::where([
                'id' =>  $data['invoice_id'],
            ])->update([

                'invoice_type' => 'real',

            ]);

            Operation::where([
                'id' =>  $data['operation_id'],
            ])->update([

                'invoice_type' => 'real',
                'new_receivable' => $op_account->receivable - $data['amount'],
                'new_netting' => $op_account->netting - $data['amount'],

            ]);



            // Le compte de togocom
            Account::where(['id' => $tgc_account->id])->update([
                'receivable' => $tgc_account->receivable - $data['amount'],

            ]);

            //Le compte de l'opeateur en question
            Account::where(['id' => $op_account->id])->update([
                'receivable' => $op_account->receivable - $data['amount'],
                'netting' => $op_account->netting - $data['amount'],

            ]);



            if ($operator->currency == 'EUR') {
                Resum::create([
                    'id_operator' => $data['id_operator'],
                    'id_operation_1' => $data['operation_id'],
                    'id_operation_2' => $data['operation_id'],
                    'receivable' =>  -$data['amount'],
                    'receivable_cfa' =>  -$data['amount'] * $tgc_account->operator->euro_conversion,

                    'comment' =>  $data['comment'],
                    'service' =>  'Note de crédit ' . $invoice->period,
                    'period' =>  $invoice->period,
                    'periodDate' => periodeDate($invoice->period),

                    'netting' => $op_account->netting - $data['amount'],


                ]);
            } elseif ($operator->currency == 'USD') {
                Resum::create([
                    'id_operator' => $data['id_operator'],
                    'id_operation_1' => $data['operation_id'],
                    'id_operation_2' => $data['operation_id'],
                    'receivable' =>  -$data['amount'],
                    'receivable_cfa' =>  -$data['amount'] * $tgc_account->operator->dollar_conversion,

                    'comment' =>  $data['comment'],
                    'service' =>  'Note de crédit ' . $invoice->period,
                    'period' =>  $invoice->period,
                    'periodDate' => periodeDate($invoice->period),

                    'netting' => $op_account->netting - $data['amount'],


                ]);
            } elseif ($operator->currency == 'XAF') {
                Resum::create([
                    'id_operator' => $data['id_operator'],
                    'id_operation_1' => $data['operation_id'],
                    'id_operation_2' => $data['operation_id'],
                    'receivable' =>  -$data['amount'],
                    'receivable_cfa' =>  -$data['amount'] * $tgc_account->operator->xaf_conversion,

                    'comment' =>  $data['comment'],
                    'service' =>  'Note de crédit ' . $invoice->period,
                    'period' =>  $invoice->period,
                    'periodDate' => periodeDate($invoice->period),

                    'netting' => $op_account->netting - $data['amount'],


                ]);
            } elseif ($operator->currency == 'XOF') {
                Resum::create([
                    'id_operator' => $data['id_operator'],
                    'id_operation_1' => $data['operation_id'],
                    'id_operation_2' => $data['operation_id'],
                    'receivable' =>  -$data['amount'],
                    'receivable_cfa' => -$data['amount'],

                    'comment' =>  $data['comment'],
                    'service' =>  'Note de crédit ' . $invoice->period,
                    'period' =>  $invoice->period,
                    'periodDate' => periodeDate($invoice->period),

                    'netting' => $op_account->netting - $data['amount'],


                ]);
            }




            Journal::create([
                'action' => "Ajout d'une note de credit, d'un montant de : " . $data['amount'] . $operator->currency . " de la facture N°:" . $invoice->invoice_number . " en diminution de créance",
                'user_id' => session('id'),
            ]);
        }


        return redirect()->route('ope_dashboard', ['id' => $operator->id])->with('flash_message_success', 'Note de crédit ajouté avec avec succès!');
    }


    public function add_settlement(Request $request)
    {
        $data = $request->all();

        $request->validate([

            'amount' => 'required|numeric|between:0,99999999999999999999.99',
            'description' => 'nullable|string|max:500',

        ]);


        $data = $request->all();

        $operator = Operator::where('id', $data['id_operator'])->first();

        $op_account = Account::where('id_operator', $operator->id)->first();

        $tgc_account = Account::where('account_number', 000)->first();


        if ($data['type'] == 'Encaissement') {

            $invoice = Invoice::create([
                // 3 if the invoice is Reglement 
                'tgc_invoice' => 3,
                'invoice_type' => 'Encaissement',
                'invoice_date' =>  $data['invoice_date'],
                'operator_id' =>  $operator->id,

                'add_by' => session('id'),
                'amount' => $data['amount'],
                'comment' =>  $data['comment'],



            ]);



            $operation = Operation::create([
                //Operation type 3 it mees that it is the settlement
                'operation_type' => 3,
                'account_number' => $op_account->account_number,
                'operation_name' =>  'Encaissement - ' . $data['invoice_date'],
                'comment' =>  $data['comment'],
                'id_invoice' =>  $invoice->id,
                'id_op_account' =>  $op_account->id,
                'id_operator' =>  $operator->id,
                'add_by' => session('id'),
                'amount' => $data['amount'],
                'new_receivable' => $op_account->receivable - $data['amount'],
                'new_debt' => $op_account->debt,
                'new_netting' => $op_account->netting - $data['amount'],
                'invoice_type' => 'Encaissement',



            ]);



            Account::where(['id' => $tgc_account->id])->update([
                'receivable' => $tgc_account->receivable - $data['amount'],

            ]);

            Account::where(['id' => $op_account->id])->update([
                'debt' => $operation->new_debt,
                'receivable' => $operation->new_receivable,
                'netting' => $operation->new_netting,

            ]);




            if ($operator->currency == 'EUR') {
                Resum::create([
                    'id_operator' => $operator->id,
                    'incoming_payement' =>  $data['amount'],
                    'incoming_payement_cfa' =>  $data['amount'] * $tgc_account->operator->euro_conversion,
                    'netting' =>  $operation->new_netting,
                    'id_invoice_1' =>  $invoice->id,
                    'id_invoice_2' =>  $invoice->id,
                    'id_operation_1' =>  $operation->id,
                    'id_operation_2' =>  $operation->id,
                    'service' =>  'Encaissment - ' . $data['invoice_date'],
                    'periodDate' =>  $data['invoice_date'],

                ]);
            } elseif ($operator->currency == 'USD') {
                Resum::create([
                    'id_operator' => $operator->id,
                    'incoming_payement' =>  $data['amount'],
                    'incoming_payement_cfa' =>  $data['amount'] * $tgc_account->operator->dollar_conversion,
                    'netting' =>  $operation->new_netting,
                    'id_invoice_1' =>  $invoice->id,
                    'id_invoice_2' =>  $invoice->id,
                    'id_operation_1' =>  $operation->id,
                    'id_operation_2' =>  $operation->id,
                    'service' =>  'Encaissment - ' . $data['invoice_date'],
                    'periodDate' =>  $data['invoice_date'],

                ]);
            } elseif ($operator->currency == 'XAF') {
                Resum::create([
                    'id_operator' => $operator->id,
                    'incoming_payement' =>  $data['amount'],
                    'incoming_payement_cfa' =>  $data['amount'] * $tgc_account->operator->xaf_conversion,
                    'netting' =>  $operation->new_netting,
                    'id_invoice_1' =>  $invoice->id,
                    'id_invoice_2' =>  $invoice->id,
                    'id_operation_1' =>  $operation->id,
                    'id_operation_2' =>  $operation->id,
                    'service' =>  'Encaissment - ' . $data['invoice_date'],
                    'periodDate' =>  $data['invoice_date'],

                ]);
            } elseif ($operator->currency == 'XOF') {
                Resum::create([
                    'id_operator' => $operator->id,
                    'incoming_payement' =>  $data['amount'],
                    'incoming_payement_cfa' =>  $data['amount'],
                    'netting' =>  $operation->new_netting,
                    'id_invoice_1' =>  $invoice->id,
                    'id_invoice_2' =>  $invoice->id,
                    'id_operation_1' =>  $operation->id,
                    'id_operation_2' =>  $operation->id,
                    'service' =>  'Encaissment - ' . $data['invoice_date'],
                    'periodDate' =>  $data['invoice_date'],

                ]);
            }







            Journal::create([
                'action' => "Ajout d'un encaissment d'une valeur de  " . $data['amount'] . $operator->currency . " de l'opérateur " . $operator->name . " en faveur de TOGOCOM ",
                'user_id' => session('id'),
            ]);


            return redirect()->route('ope_dashboard', ['id' => $operator->id])->with('flash_message_success', 'Encaissement ajouté avec succès!');
        } else {

            $invoice = Invoice::create([
                //tgc_invoice is 4 if the invoice is disbursement  
                'tgc_invoice' => 4,
                'invoice_type' => 'Decaissement',
                'invoice_date' =>  $data['invoice_date'],
                'operator_id' =>  $operator->id,

                'add_by' => session('id'),
                'amount' => $data['amount'],
                'comment' =>  $data['comment'],
            ]);




            $operation = Operation::create([
                //Operation type 4 it mees that it is the disbursement 
                'operation_type' => 4,
                'account_number' => $op_account->account_number,
                'operation_name' =>  'Decaissement - ' . $data['invoice_date'],
                'comment' =>  $data['comment'],
                'id_invoice' =>  $invoice->id,
                'id_op_account' =>  $op_account->id,
                'id_operator' =>  $operator->id,
                'add_by' => session('id'),
                'amount' => $data['amount'],
                'new_receivable' => $op_account->receivable,
                'new_debt' => $op_account->debt - $data['amount'],
                'new_netting' => $op_account->netting + $data['amount'],
                'invoice_type' => 'Decaissement',



            ]);



            Account::where(['id' => $tgc_account->id])->update([
                'debt' => $tgc_account->debt - $data['amount'],

            ]);

            Account::where(['id' => $op_account->id])->update([
                'debt' => $operation->new_debt,
                'receivable' => $operation->new_receivable,
                'netting' => $operation->new_netting,

            ]);


            if ($operator->currency == 'EUR') {
                Resum::create([
                    'id_operator' => $operator->id,
                    'payout' =>  $data['amount'],
                    'payout_cfa' =>  $data['amount'] * $tgc_account->operator->euro_conversion,
                    'netting' =>  $operation->new_netting,
                    'id_invoice_1' =>  $invoice->id,
                    'id_invoice_2' =>  $invoice->id,
                    'id_operation_1' =>  $operation->id,
                    'id_operation_2' =>  $operation->id,
                    'service' =>  'Decaissement - ' . $data['invoice_date'],
                    'periodDate' =>  $data['invoice_date'],

                ]);
            } elseif ($operator->currency == 'USD') {
                Resum::create([
                    'id_operator' => $operator->id,
                    'payout' =>  $data['amount'],
                    'payout_cfa' =>  $data['amount'] * $tgc_account->operator->dollar_conversion,
                    'netting' =>  $operation->new_netting,
                    'id_invoice_1' =>  $invoice->id,
                    'id_invoice_2' =>  $invoice->id,
                    'id_operation_1' =>  $operation->id,
                    'id_operation_2' =>  $operation->id,
                    'service' =>  'Decaissement - ' . $data['invoice_date'],
                    'periodDate' =>  $data['invoice_date'],

                ]);
            } elseif ($operator->currency == 'XAF') {
                Resum::create([
                    'id_operator' => $operator->id,
                    'payout' =>  $data['amount'],
                    'payout_cfa' =>  $data['amount'] * $tgc_account->operator->xaf_conversion,
                    'netting' =>  $operation->new_netting,
                    'id_invoice_1' =>  $invoice->id,
                    'id_invoice_2' =>  $invoice->id,
                    'id_operation_1' =>  $operation->id,
                    'id_operation_2' =>  $operation->id,
                    'service' =>  'Decaissement - ' . $data['invoice_date'],
                    'periodDate' =>  $data['invoice_date'],

                ]);
            } elseif ($operator->currency == 'XOF') {
                Resum::create([
                    'id_operator' => $operator->id,
                    'payout' =>  $data['amount'],
                    'payout_cfa' =>  $data['amount'],
                    'netting' =>  $operation->new_netting,
                    'id_invoice_1' =>  $invoice->id,
                    'id_invoice_2' =>  $invoice->id,
                    'id_operation_1' =>  $operation->id,
                    'id_operation_2' =>  $operation->id,
                    'service' =>  'Decaissement - ' . $data['invoice_date'],
                    'periodDate' =>  $data['invoice_date'],

                ]);
            }




            Journal::create([
                'action' => "Ajout d'un decaissement d'une valeur de  " . $data['amount'] . $operator->currency . " En faveur de  " . $operator->name,
                'user_id' => session('id'),
            ]);


            return redirect()->route('ope_dashboard', ['id' => $operator->id])->with('flash_message_success', 'Decaissement ajouté avec succès!');
        }
    }





    //Operator operation list

    public function operations_list($id_operator)
    {


        $operator = Operator::where('id', $id_operator)->first();

        $operations = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0])
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('operator.operation_list', compact('operations', 'operator'))->render();
    }

    //Operator invoice list

    public function invoice_list($id_operator)
    {

        $operator = Operator::where('id', $id_operator)->first();


        $operations = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0])
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('invoice.invoice_list', compact('operations', 'operator'))->render();
    }


    //Operator invoice list

    public function all_invoice_list()
    {


        $operations = Operation::where('is_delete', 0)
            ->orderBy('updated_at', 'DESC')
            ->get();

        //  dd(gettype($operations[1]->invoice->amount));

        return view('operator.all_invoice', compact('operations'))->render();
    }


    //Operator invoice list

    public function all_resum_list()
    {


      
        $resums = Resum::where(['is_delete' => 0])
            ->orderBy('updated_at', 'DESC')
            ->get();

       
      //  dd($resums[1]->operator->name);


        return view('operator.all_resum_list', compact('resums'))->render();
    }


    //Operator invoice list

    public function delete_invoice_list()
    {


        $operations = Operation::where('is_delete', 1)
            ->orderBy('updated_at', 'DESC')
            ->get();

        //  dd(gettype($operations[1]->invoice->amount));

        return view('operator.all_delete_invoice', compact('operations'))->render();
    }



    //All Operator operations list

    public function all_operations()
    {


        $operations = Operation::where('is_delete', 0)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('operator.all_operation_list', compact('operations'))->render();
    }

    public function all_cancel_operations()
    {


        $operations = Operation::where('is_delete', 1)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('operator.all_cancel_operation_list', compact('operations'))->render();
    }

    //Liste des creances et des dettes

    public function receivable_debt($id_operator)
    {

        $operator = Operator::where('id', $id_operator)->first();

        $resums = Resum::where(['id_operator' => $id_operator, 'is_delete' => 0])
            ->orderBy('updated_at', 'DESC')
            ->get();

        $operations = Operation::where(['id_operator' => $id_operator, 'is_delete' => 0])
            ->orderBy('updated_at', 'DESC')
            ->get();


        return view('operator.raceivable_debt_list', compact('operations', 'resums', 'operator'))->render();
    }


    //cotisation tontine

    public function cotisation_tontine(Request $request)
    {

        $request->validate([
            'nombre_de_jour' => 'required',
            'id_compte' => 'required',

        ]);

        $data = $request->all();

        $compte = Account::where('id', $data['id_compte'])->first();

        $agence = Operator::where('id', session('id_agence'))->first();

        if ($data['comission'] == 1) {

            $entre = ($data['nombre_de_jour'] - 1) * $compte->taux_cotisation;
            $new_solde_actuel = $compte->solde_actuelle + $entre;

            $operation = Operation::create([
                'libelle_operation' => 'Cotisation tontine',
                'type_compte' => $compte->type_compte,
                'type_operation' => 'cotisation',
                'nombre_jour' => $data['nombre_de_jour'],
                'taux_cotisation' => $compte->taux_cotisation,
                'entre' => $data['nombre_de_jour'] * $compte->taux_cotisation,
                'benefice' => $compte->taux_cotisation,
                'tous_entre' => $data['nombre_de_jour'] * $compte->taux_cotisation,
                'sortie' => 0,
                'solde_restant' => $new_solde_actuel,
                'account_number' => $compte->account_number,
                'add_by' => session('id'),
                'id_compte' => $compte->id,
                'id_client' => $compte->id_client,
                'id_agence' => session('id_agence'),
            ]);

            Account::where(['id' => $data['id_compte']])->update([
                'solde_actuelle' => $new_solde_actuel,
                'beniefice_accumule' => $compte->beniefice_accumule + $compte->taux_cotisation,
                'nombre_de_retrait' => $compte->nombre_de_retrait + ($data['nombre_de_jour'] - 1),

            ]);

            $solde_total_agence = $agence->solde_total + ($data['nombre_de_jour'] * $compte->taux_cotisation);
            $benefice_total_agence = $agence->beniefice_accumule + $compte->taux_cotisation;

            Operator::where(['id' => session('id_agence')])->update([
                'solde_total' => $solde_total_agence,
                'beniefice_accumule' => $benefice_total_agence,

            ]);
        } else {

            $entre = $data['nombre_de_jour'] * $compte->taux_cotisation;


            $new_solde_actuel = $compte->solde_actuelle + $entre;

            $operation = Operation::create([
                'libelle_operation' => 'Cotisation tontine',
                'type_compte' => $compte->type_compte,
                'type_operation' => 'cotisation',
                'nombre_jour' => $data['nombre_de_jour'],
                'taux_cotisation' => $compte->taux_cotisation,
                'entre' => $entre,
                'tous_entre' => $data['nombre_de_jour'] * $compte->taux_cotisation,
                'benefice' => 0,
                'sortie' => 0,
                'solde_restant' => $new_solde_actuel,
                'account_number' => $compte->account_number,
                'add_by' => session('id'),
                'id_compte' => $compte->id,
                'id_client' => $compte->id_client,
                'id_agence' => session('id_agence'),
            ]);

            Account::where(['id' => $data['id_compte']])->update([
                'solde_actuelle' => $new_solde_actuel,
                'nombre_de_retrait' => $compte->nombre_de_retrait + $data['nombre_de_jour'],


            ]);

            $solde_total_agence = $agence->solde_total + ($data['nombre_de_jour'] * $compte->taux_cotisation);

            Operator::where(['id' => session('id_agence')])->update([
                'solde_total' => $solde_total_agence,

            ]);
        }

        $user = $compte->client->user;

        Journal::create([
            'action' => "Cotisation de " . $entre . " sur le compte " . $compte->account_number . "du client " . $user->first_name . " " . $user->last_name,
            'user_id' => session('id'),
        ]);



        if ($agence->solde_total >= $agence->quota_maximum + ($data['nombre_de_jour'] * $compte->taux_cotisation)) {

            return redirect('agence_operations')->with('flash_message_success', 'Cotisation reussi, vous avez atteint votre quotta maximum pour effectuer votre versement!');
        }


        return redirect('agence_operations')->with('flash_message_success', 'Cotisation ajouté sur le compte avec succès!');
    }


    //function de retrait

    public function retrait(Request $request)
    {

        $request->validate([
            'somme' => 'required',

        ]);

        $data = $request->all();

        $compte = Account::where('id', $data['id_compte'])->first();

        $agence = Operator::where('id', session('id_agence'))->first();

        if ($data['somme'] == $compte->solde_actuelle  || $data['somme'] < $compte->solde_actuelle) {

            $sortie = ($data['somme']);

            $new_solde_actuel = $compte->solde_actuelle - $sortie;

            $operation = Operation::create([
                'libelle_operation' => 'Retait sur le compte',

                'type_operation' => 'retrait',
                'taux_cotisation' => $compte->taux_cotisation,
                'entre' => 0,
                'sortie' => $sortie,
                'benefice' => 0,

                'solde_restant' => $new_solde_actuel,
                'account_number' => $compte->account_number,
                'add_by' => session('id'),
                'id_compte' => $compte->id,
                'type_compte' => $compte->type_compte,
                'id_client' => $compte->id_client,
                'id_agence' => session('id_agence'),
            ]);


            Account::where(['id' => $data['id_compte']])->update([
                'solde_actuelle' => $new_solde_actuel,

            ]);

            $solde_total_agence = $agence->solde_total - $sortie;

            Operator::where(['id' => session('id_agence')])->update([
                'solde_total' => $solde_total_agence,

            ]);


            $user = $compte->client->user;

            Journal::create([
                'action' => "Retrait de " . $data['somme'] . " sur le compte  " . $compte->account_number . "du client " . $user->first_name . " " . $user->last_name,
                'user_id' => session('id'),
            ]);

            return redirect()->back()->with('flash_message_success', 'Retrait effectuer  avec succès!');
        } else {

            return redirect()->back()->with('flash_message_error', 'Solde insuffisant sur le compte  pour effectuer cette opération!');
        }
    }



    public function versement()
    {
        $id_agence = session('id_agence');

        $entre = Operation::where(['id_agence' => session('id_agence'), 'is_delete' => 0])->sum('entre');
        $benefice = Operation::where(['id_agence' => session('id_agence'), 'is_delete' => 0])->sum('benefice');
        $sortie = Operation::where(['id_agence' => $id_agence, 'is_delete' => 0])->sum('sortie');
        $versement = Operation::where(['id_agence' => $id_agence, 'is_delete' => 0])->sum('versement');
        $agence = Operator::where(['id' => $id_agence, 'is_delete' => 0])->first();

        $solde = $entre - ($versement + $sortie);

        return view('comptes.versement', compact('solde', 'versement'));
    }




    public function versement_save(Request $request)
    {

        $request->validate([
            'somme' => 'required',

        ]);

        $data = $request->all();

        $compte = Account::where('account_number', 1)->first();

        $id_agence = session('id_agence');

        $entre = Operation::where(['id_agence' => $id_agence, 'is_delete' => 0])->sum('entre');
        $benefice = Operation::where(['id_agence' => $id_agence, 'is_delete' => 0])->sum('benefice');
        $sortie = Operation::where(['id_agence' => $id_agence, 'is_delete' => 0])->sum('sortie');
        $versement = Operation::where(['id_agence' => $id_agence, 'is_delete' => 0])->sum('versement');
        $agence = Operator::where(['id' => $id_agence, 'is_delete' => 0])->first();


        $solde = $entre - ($versement + $sortie);


        if ($data['somme'] < $solde) {

            $versement = ($data['somme']);

            $new_solde_actuel = $solde - $data['somme'];

            $operation = Operation::create([
                'libelle_operation' => "Versement à l'agence principal",
                'type_compte' => 'principal',
                'type_operation' => 'versement',
                'entre' => 0,
                'sortie' => 0,
                'benefice' => 0,
                'versement' => $versement,
                'solde_restant' => $new_solde_actuel,
                'add_by' => session('id'),
                'id_compte' => $compte->id,
                'type_compte' => $compte->type_compte,

                'id_client' => 1,
                'id_agence' => session('id_agence'),
            ]);

            $new_solde_admin_account = $compte->solde_actuell + $data['somme'];

            Account::where(['id' => 1])->update([
                'solde_actuelle' => $new_solde_admin_account,

            ]);

            $solde_total_agence = $new_solde_actuel;

            Operator::where(['id' => session('id_agence')])->update([
                'solde_total' => $solde_total_agence,

            ]);


            $user = $compte->client->user;

            Journal::create([
                'action' => "Versement de " . $data['somme'] . "Fr cfa  sur le compte  " . $compte->account_number . "de l'agence principal par l'agence : " . $agence->nom . ". Le nouveau solde de l'agence est:" . $solde_total_agence . "Fr cfa et le nouveau solde de l'agence principal est:" . $new_solde_admin_account,
                'user_id' => session('id'),
            ]);

            return redirect('agence_operations')->with('flash_message_success', 'Versement effectuer  avec succès!');
        } else {

            return redirect()->back()->with('flash_message_error', 'Solde agence insuffisant   pour effectuer cette opération!');
        }
    }


    public function retrait_admin()
    {
        $versement = Operation::where(['is_delete' => 0])->sum('versement');
        $benefice = Operation::where(['is_delete' => 0])->sum('benefice');
        $retrait_admin = Operation::where(['is_delete' => 0, 'type_operation' => 'retrait_admin'])->sum('sortie');


        $benefice_act = $benefice - $retrait_admin;

        $solde = $versement - $retrait_admin;




        return view('comptes.retrait_admin', compact('solde', 'retrait_admin', 'versement', 'benefice_act'));
    }



    public function retrait_admin_save(Request $request)
    {

        $request->validate([
            'somme' => 'required',

        ]);

        $data = $request->all();

        $compte = Account::where('account_number', 1)->first();


        if ($data['somme'] <= $data['solde']) {

            $sortie = $data['somme'];

            $new_solde_actuel = $data['solde'] - $data['somme'];

            $operation = Operation::create([
                'libelle_operation' => "Retrait sur le compte  principal de la société",

                'type_operation' => 'retrait_admin',
                'entre' => 0,
                'benefice' => 0,
                'versement' => 0,
                'sortie' => $sortie,
                'solde_restant' => $new_solde_actuel,
                'add_by' => session('id'),
                'id_compte' => $compte->id,
                'type_compte' => $compte->type_compte,

                'id_client' => 1,
                'id_agence' => session('id_agence'),
            ]);


            Account::where(['id' => 1])->update([
                'solde_actuelle' => $new_solde_actuel,

            ]);

            Journal::create([
                'action' => "Retrai de " . $data['somme'] . "Fr cfa sur le compte   de l'agence principal. Le nouveau solde sur le compte de la société est: " . $new_solde_actuel,
                'user_id' => session('id'),
            ]);

            return redirect('dashboard')->with('flash_message_success', 'Retrait effectuer  avec succès!');
        } else {

            return redirect()->back()->with('flash_message_error', 'Solde insuffisant sur le compte de la société  pour effectuer cette opération!');
        }
    }





    public function liste_operations($id_client, $id_compte)
    {

        $client = Client::where('id', $id_client)->first();

        $operations = Operation::where(['id_client' => $id_client, 'id_compte' => $id_compte, 'is_delete' => 0])
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('comptes.liste_operations', compact('operations', 'client'))->render();
    }

    public function agence_operations()
    {

        if (getUserType()->type_user == 2) {

            $operations = Operation::where(['id_agence' => session('id_agence'), 'is_delete' => 0])
                ->orderBy('updated_at', 'DESC')
                ->get();

            return view('comptes.agence_liste_operations', compact('operations'))->render();
        } elseif (getUserType()->type_user == 3) {
            return redirect('agent_operations');
        }
    }

    public function agent_operations()
    {


        $operations = Operation::where([
            'id_agence' => session('id_agence'),
            'add_by' => session('id'),
            'is_delete' => 0
        ])
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('agents.agent_liste_operations', compact('operations'))->render();
    }

    public function client_operations()
    {


        $user = User::where(['id' => session('id')])->first();
        $client = Client::where(['id_user' => session('id')])->first();
        $id_client = $client->id;


        $operations = Operation::where(['id_client' => $id_client, 'is_delete' => 0])->orderBy('updated_at', 'DESC')
            ->get();

        return view('client.client_liste_operations', compact('operations'))->render();
    }

    public function admin_operations()
    {


        $operations = Operation::where('is_delete', 0)->get();


        return view('comptes.agence_liste_operations', compact('operations'))->render();
    }


    public function facture($id_operation)
    {


        $operation = Operation::where('id', $id_operation)->first();


        return view('facture.facture', compact('operation'))->render();
    }


    public function new_carnet($id_compte)
    {




        $compte = Account::where('id', $id_compte)->first();

        $agence = Operator::where('id', session('id_agence'))->first();


        $benefice = 100;
        $carnet = 200;

        $new_benefice_actuel = $compte->beniefice_accumule + $benefice;

        Operation::create([
            'libelle_operation' => 'Achat de carnet',
            'type_operation' => 'carnet',
            'entre' => 300,
            'sortie' => 0,
            'benefice' => 100,
            'tous_entre' => 300,
            'account_number' => $compte->account_number,
            'type_compte' => $compte->type_compte,
            'solde_restant' => $compte->solde_actuelle,
            'add_by' => session('id'),
            'id_compte' => $compte->id,
            'id_client' => $compte->id_client,
            'id_agence' => session('id_agence'),
        ]);



        Account::where(['id' => $id_compte])->update([
            'beniefice_accumule' => $new_benefice_actuel,

        ]);

        $new_benefice_accumule = $agence->beniefice_accumule + $benefice;

        Operator::where(['id' => session('id_agence')])->update([
            'beniefice_accumule' => $new_benefice_accumule,

        ]);


        $user = $compte->client->user;

        Journal::create([
            'action' => "Ajout de 300fr (frais d'achat du carnet) sur le compte  " . $compte->account_number . " du client " . $user->first_name . " " . $user->last_name,
            'user_id' => session('id'),
        ]);

        return redirect()->back()->with('flash_message_success', 'Ajout de 300fr pour achat de carnet effectuer  avec succès!');
    }


    public function delete_operation($id)
    {

        $operation = Operation::where('id', $id)->first();

        Operation::where(['id' => $id])->update([
            'is_delete' => 1,
        ]);


        $compte = Account::where('id', $operation->id_compte)->first();
        $agence = Operator::where('id', $operation->id_agence)->first();


        if ($operation->type_operation == "new") {

            //   dd($compte);


            $new_solde_actuel = $compte->solde_actuelle - ($operation->entre - 5500);

            Account::where(['id' => $operation->id_compte])->update([

                'solde_actuelle' => $new_solde_actuel,
                'solde_total' => $new_solde_actuel,
                'beniefice_accumule' => $compte->beniefice_accumule - 5500,


            ]);
        } elseif ($operation->type_operation == "depot") {

            $new_solde_actuel = $compte->solde_actuelle - $operation->entre;


            Account::where(['id' => $operation->id_compte])->update([

                'solde_actuelle' => $new_solde_actuel


            ]);
        } elseif ($operation->type_operation == "retrait") {

            $new_solde_actuel = $compte->solde_actuelle + $operation->sortie;


            Account::where(['id' => $operation->id_compte])->update([

                'solde_actuelle' => $new_solde_actuel


            ]);
        } elseif ($operation->type_operation == "versement") {

            $new_solde_actuel = $agence->solde_actuelle + $operation->versement;
            $cmpt_solde_actuel = $compte->solde_actuelle - $operation->versement;


            Operator::where(['id' => $operation->add_by])->update([

                'solde_total' => $new_solde_actuel


            ]);

            Account::where(['id' => $operation->id_compte])->update([

                'solde_actuelle' => $cmpt_solde_actuel


            ]);
        } elseif ($operation->type_operation == "carnet") {

            $new_solde_actuel = $agence->beniefice_accumule - $operation->benefice;
            $cmpt_solde_actuel = $compte->beniefice_accumule - $operation->benefice;


            Operator::where(['id' => $operation->add_by])->update([

                'beniefice_accumule' => $new_solde_actuel


            ]);

            Account::where(['id' => $operation->id_compte])->update([

                'beniefice_accumule' => $cmpt_solde_actuel


            ]);
        } elseif ($operation->type_operation == "cotisation") {

            $new_solde_actuel = $agence->solde_total - $operation->entre;
            $cmpt_solde_actuel = $compte->solde_actuelle - $operation->entre;
            $cmpt_beniefice_accumule = $compte->beniefice_accumule - $operation->benefice;
            if ($operation->benefice != 0) {

                $cmpt_nombre_de_retrait = $compte->nombre_de_retrait - ($operation->entre / $operation->taux_cotisation) + 1;
                $cmpt_solde_actuel = $compte->solde_actuelle - $operation->entre + $operation->taux_cotisation;
            } else {

                $cmpt_nombre_de_retrait = $compte->nombre_de_retrait - ($operation->entre / $operation->taux_cotisation);
                $cmpt_solde_actuel = $compte->solde_actuelle - $operation->entre;
            }


            Operator::where(['id' => $operation->add_by])->update([

                'solde_total' => $new_solde_actuel


            ]);

            Account::where(['id' => $operation->id_compte])->update([

                'solde_actuelle' => $cmpt_solde_actuel,
                'beniefice_accumule' => $cmpt_beniefice_accumule,
                'nombre_de_retrait' => $cmpt_nombre_de_retrait,


            ]);
        } elseif ($operation->type_operation == "retrait_admin") {

            $new_solde_actuel = $compte->solde_actuelle + $operation->sortie;


            Account::where(['id' => $operation->id_compte])->update([

                'solde_actuelle' => $new_solde_actuel


            ]);
        }


        Journal::create([
            'action' => "Annulation de l'operation  sur le compte : " . $operation->account_number,
            'user_id' => session('id'),
        ]);
        return redirect()->back()->with('flash_message_success', 'Operation annulé avec succès!');
    }
}
