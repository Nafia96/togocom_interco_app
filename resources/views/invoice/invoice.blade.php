
<div class="modal fade bd-example-modal-lg" id="{{ 'invoice' . $operation->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <section class="section">
                <div class="section-body" class="background-image">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="invoice-title">
                                        <img style="height: 50px; width: 50px;" alt="image"
                                            src="{{ asset('assets/img/logo.png') }}" class="rounded author-box-picture">
                                        <br>
                                        TOGOCEL | TOGO TELECOM <br>
                                        Filiales du Groupe Togocom
                                        <div class="row">
                                            <div class="mt-3  col-12 text-center  m-auto">
                                                <strong style="font-size: 25px;">TOGO TELECOM</strong>
                                                <br>
                                                FACTURE DE TRACFIC
                                            </div>

                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <address>
                                                <strong>Période de facturation: </strong> {{$operation->invoice->start_period}} - {{$operation->invoice->end_period}}<br>
                                                <strong>Date de facturation:</strong> {{$operation->invoice->invoice_date}}<br>
                                                <strong>N° de facture:</strong> {{$operation->invoice->invoice_number}}<br>

                                            </address>
                                        </div>
                                        <div class="col-md-6 text-md-right">
                                            <address>
                                                <strong style="font-size: 20px;">{{$operator->name}}</strong><br>
                                                {{$operator->adresse}}<br>
                                                <strong>BP: </strong>0000, {{$operator->country}}<br>
                                                <strong>Tel: </strong>{{$operator->tel}}<br>
                                                <strong>Email: </strong>{{$operator->email}}<br>
                                                <strong>Fax: </strong>+220 0000000<br>
                                            </address>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">

                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-md">
                                            <tr>
                                                <th>SERVICE ET PRODUITS FACTURES</th>
                                                <th class="text-center">NOMBRE D'APPELS</th>
                                                <th class="text-center">MINUTES</th>
                                                <th class="text-right">DEVISE</th>
                                                <th class="text-right">MONTANT</th>
                                            </tr>
                                            <tr style="background-color: #fcca29; border: 1px solid white;
                                            border-collapse: collapse;">
                                                <td >TRAFIC INTERNATIONAL ENTRANT</td>
                                                <td class="text-center">13619</td>
                                                <td class="text-center">{{$operation->invoice->call_volume}}</td>
                                                <td class="text-right">{{$operator->currency}}</td>
                                                <td class="text-right">{{number_format($operation->invoice->amount)}}</td>
                                            </tr>
                                            <tr style="background-color: #fcca29;">

                                                <td>Montant Facture HT</td>
                                                <td class="text-center">13619</td>
                                                <td class="text-center">{{$operation->invoice->call_volume}}</td>
                                                <td class="text-right">{{$operator->currency}}</td>
                                                <td class="text-right">{{number_format($operation->invoice->amount)}}</td>
                                            </tr>
                                            <tr style="background-color: #fcca29;">

                                                <td>Montant Total TVA</td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-right">{{$operator->currency}}</td>
                                                <td class="text-right">0</td>
                                            </tr>
                                            <tr style="background-color: #fcca29;">

                                                <td>Montant Total TTC</td>
                                                <td class="text-center"></td>
                                                <td class="text-center">    </td>
                                                <td class="text-right">{{$operator->currency}}</td>
                                                <td class="text-right">{{number_format($operation->invoice->amount)}}</td>
                                            </tr>


                                        </table>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-lg-12">
                                            <p class="section-lead">Arrêté cette facture à la somme de  {{ number_format($operation->invoice->amount) }}</p>
                                            <p class="section-lead">Payable dans un délai de 30 jours au maximum sur le Compte Bancaire de TOGO TELECOM ci-dessous:</p>
                                            <p class="section-lead">Titulaire du compte: Société des Télécommunications du <br>
                                            Togo (TOGO TELECOM) <br>
                                            Nom de la Banque: UNION TOGOLAISE DE BANQUE <br>
                                            Numéro de Compte: 034257000400 <br>
                                            Code Banque: TG0009 <br>
                                            Code Guichet: 01032 <br>
                                            Code Swift: UNTBTGTG <br>
                                            RIB: 27 <br>
                                        </p>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-lg-6"></div>
                            <div class="col-lg-6 text-md-right">
                                LE DIRECTEUR GENERAL PAR INTERIM <br>
                                <hr>


                                <span  class="text-center">Tarik BOUDIAF</span><br>
                            </div>

                        </div>

                        <div class="row">
                                <div class="col-lg-8">
                                    <div class="text-md-left" style="font-size: 10px;">
                                        <div class="float-lg-left mb-lg-0 mb-3">
                                           Place de la Réconciliation - (Quartier Atchanté) <br>
                                           Boite postale: 333 - Lomé - Togo <br> <br>
                                           <strong> <span style="color: #03a04f; font-weight: bold;"> Avancer.</span> <span  style="color: #fcca29; font-weight: bold;">Pour vous.</span>  <span  style="color: #ec1f28; font-weight: bold;">Pour Tous.</span> </strong>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-lg-4">
                                    <div class="text-md-left" style="font-size: 10px;">
                                        <div class="float-lg-left mb-lg-0 mb-3">
                                           Téléphone: +228 22 53 44 01 <br>
                                           E-mail: spdgtgt@togotelecom.tg <br>
                                           Site web: togocom.tg
                                        </div>

                                    </div>

                                </div>

                        </div>



                    </div>
                </div>
            </section>

        </div>



    </div>
</div>
</div>
</div>
