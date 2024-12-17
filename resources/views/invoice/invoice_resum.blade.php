
<div class="modal fade bd-example-modal-lg" id="{{ 'invoice' . $operation->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
       

            
                
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="invoice-title">
                                        <div class="row">
                                            <div class="col-6">
                                                <img style="height: 50px; width: 50px;" alt="image"
                                                src="{{ asset('assets/img/logo.png') }}" class="rounded author-box-picture">
                                            <br>
                                            TOGOCEL | TOGO TELECOM <br>
                                            Filiales du Groupe Togocom
                                            </div>
                                            <div class="col-6">
                                                <div class="mt-3  text-right  m-auto">
                                                    <strong style="font-size: 25px;">{{$operator->name}}</strong>
                                                    <br>
                                                    <address>
                                                        {{$operator->adresse}}<br>
                                                        <strong>Tel: </strong>{{$operator->tel}}<br>
                                                        <strong>Email: </strong>{{$operator->email}}<br>
                                                    </address>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="mt-3  col-12 text-center  m-auto">
                                             
                                                    
                                                    
                                                @if($operation->operation_type == '1')
                                            <strong  style="font-size: 20px;"> FACTURE TOGO TELECOM </strong>
                                               <br>
                                                FACTURE DE TRACFIC
                                                 @endif

                                                 @if($operation->operation_type == '2')
                                                <strong  style="font-size: 20px;"> FACTURE {{$operator->name}} </strong>
                                                <br>
                                                FACTURE DE TRACFIC
                                                 @endif

                                                 @if($operation->operation_type == '3')
                                                <strong  style="font-size: 20px;"> REGLEMENT NETTING </strong><br>
                                               {{$operation->invoice->invoice_type}}
                                                 @endif

                                               
                                                
                                            </div>

                                        </div>
                                        <hr>
                                        @if($operation->operation_type == '1'|| $operation->operation_type == '2')

                                        <div class="row">
                                            <div class="col-5">
                                                <strong>N° FACTURE:</strong> {{$operation->invoice->invoice_number}}
                                            </div>
                                            <div class="col-3">
                                                <strong>PERIODE:</strong> {{periodePrint($operation->invoice->period)}}
                                            </div>
                                            <div class="col-4">
                                                <strong>DATE FACTURE:</strong> {{$operation->invoice->invoice_date}}
                                            </div>

                                        </div>

                                        @endif

                                        @if($operation->operation_type == '3')

                                        <div class="row">
                                            <div class="col-4">
                                                <strong>N° FACTURE:</strong> -----
                                            </div>
                                            <div class="col-3">
                                                <strong>PERIODE:</strong>  -----
                                            </div>
                                            <div class="col-5">
                                                <strong>DATE DE VALEUR:</strong> {{$operation->invoice->invoice_date}}
                                            </div>

                                        </div>

                                        @endif

                                    </div>

                                    
                            

                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">

                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-md">
                                            <tr style="background-color: #03a04f; border: 1px solid white;
                                            border-collapse: collapse;">
                                                <th  class="text-center">PRESTATION</th>
                                                <th  class="text-center">TYPE</th>
                                                <th  class="text-center">NOMBRE D'APPELS</th>
                                                <th  class="text-center">MINUTES</th>
                                                <th class="text-right">MONTANT</th>
                                            </tr>

                                            @if($operation->operation_type == '1'|| $operation->operation_type == '2')
                                           
   
                                            <tr style="background-color: #fcca29; border: 1px solid white;
                                            border-collapse: collapse;">
                                                <td  class="text-center">{{$operation->operation_name}}</td>

                                                @if($operation->invoice_type == 'real')
                                                    <td  class="text-center">Facture réelle</td>
                                                 @endif

                                                 @if($operation->invoice_type == 'litigious')
                                                 <td  class="text-center">Facture litigieuse</td>
                                                 @endif

                                                 @if($operation->invoice_type == 'estimated')
                                                 <td  class="text-center">Facture Estimée</td>
                                                 @endif

                                                <td class="text-center">{{$operation->invoice->number_of_call}}</td>
                                                <td class="text-center">{{$operation->invoice->call_volume}}</td>
                                                <td class="text-center">{{number_format($operation->invoice->amount, 2, ',', ' ')}} {{$operator->currency}}</td>
                                            </tr>
                                          
                                            @endif

                                            @if($operation->operation_type == '3')
                                          
   
                                            <tr style="background-color: #fcca29; border: 1px solid white;
                                            border-collapse: collapse;">
                                                <td  class="text-center">{{$operation->operation_name}}</td>
                                                 <td  class="text-center"> {{$operation->invoice->invoice_type}}</td>
                                               
                                                <td class="text-center">---------</td>
                                                <td class="text-center">---------</td>
                                                <td class="text-center">{{number_format($operation->invoice->amount, 2, ',', ' ')}} {{$operator->currency}}</td>
                                            </tr>
                                          
                                            @endif

                                         

                                        </table>
                                    </div>

                                    
                                  
                                    <div class="row">
                                        <div class="col-4 text-center">
                                            <strong>CREANCE TOGCOCOM: <br></strong> <span  style="color: #ec1f28; font-weight: bold;"> {{number_format($operation->new_receivable, 2, ',', ' ')}}  {{$operator->currency}}</span>
                                        </div>
                                        <div class="col-4 text-center">
                                            <strong>DETTE TOGOCOM: <br></strong>  <span  style="color: #ec1f28; font-weight: bold;"> {{number_format($operation->new_debt, 2, ',', ' ')}}  {{$operator->currency}}</span> 
                                        </div>
                                        <div class="col-4 text-center">
                                            <strong>NETTING : 
                                             </strong> <br><span  style="color: #ec1f28; font-weight: bold;">  {{number_format($operation->new_netting, 2, ',', ' ')}}   {{$operator->currency}}</span>
                                        </div>

                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-lg-12  text-center">
                                            <p class="section-lead">{{ $operation->invoice->comment }}</p>
    
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>

                       

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
                                        <span>
                                            Ajouter le : {{$operation->invoice->created_at}}
                                        </span>

                                    </div>

                                </div>

                        </div>



                    </div>
               
           

        



    </div>
</div>
</div>
</div>

<div class="modal fade bd-example-modal-lg" id="{{ 'invoice' . $operation->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
       

            
                
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="invoice-title">
                                        <div class="row">
                                            <div class="col-6">
                                                <img style="height: 50px; width: 50px;" alt="image"
                                                src="{{ asset('assets/img/logo.png') }}" class="rounded author-box-picture">
                                            <br>
                                            TOGOCEL | TOGO TELECOM <br>
                                            Filiales du Groupe Togocom
                                            </div>
                                            <div class="col-6">
                                                <div class="mt-3  text-right  m-auto">
                                                    <strong style="font-size: 25px;">{{$operator->name}}</strong>
                                                    <br>
                                                    <address>
                                                        {{$operator->adresse}}<br>
                                                        <strong>Tel: </strong>{{$operator->tel}}<br>
                                                        <strong>Email: </strong>{{$operator->email}}<br>
                                                    </address>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="mt-3  col-12 text-center  m-auto">
                                             
                                                    
                                                    
                                                @if($operation->operation_type == '1')
                                            <strong  style="font-size: 20px;"> FACTURE TOGO TELECOM </strong>
                                               <br>
                                                FACTURE DE TRACFIC
                                                 @endif

                                                 @if($operation->operation_type == '2')
                                                <strong  style="font-size: 20px;"> FACTURE {{$operator->name}} </strong>
                                                <br>
                                                FACTURE DE TRACFIC
                                                 @endif

                                                 @if($operation->operation_type == '3')
                                                <strong  style="font-size: 20px;"> REGLEMENT NETTING </strong><br>
                                               {{$operation->invoice->invoice_type}}
                                                 @endif

                                               
                                                
                                            </div>

                                        </div>
                                        <hr>
                                        @if($operation->operation_type == '1'|| $operation->operation_type == '2')

                                        <div class="row">
                                            <div class="col-5">
                                                <strong>N° FACTURE:</strong> {{$operation->invoice->invoice_number}}
                                            </div>
                                            <div class="col-3">
                                                <strong>PERIODE:</strong> {{periodePrint($operation->invoice->period)}}
                                            </div>
                                            <div class="col-4">
                                                <strong>DATE FACTURE:</strong> {{$operation->invoice->invoice_date}}
                                            </div>

                                        </div>

                                        @endif

                                        @if($operation->operation_type == '3')

                                        <div class="row">
                                            <div class="col-4">
                                                <strong>N° FACTURE:</strong> -----
                                            </div>
                                            <div class="col-3">
                                                <strong>PERIODE:</strong>  -----
                                            </div>
                                            <div class="col-5">
                                                <strong>DATE DE VALEUR:</strong> {{$operation->invoice->invoice_date}}
                                            </div>

                                        </div>

                                        @endif

                                    </div>

                                    
                            

                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">

                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-md">
                                            <tr style="background-color: #03a04f; border: 1px solid white;
                                            border-collapse: collapse;">
                                                <th  class="text-center">PRESTATION</th>
                                                <th  class="text-center">TYPE</th>
                                                <th  class="text-center">NOMBRE D'APPELS</th>
                                                <th  class="text-center">MINUTES</th>
                                                <th class="text-right">MONTANT</th>
                                            </tr>

                                            @if($operation->operation_type == '1'|| $operation->operation_type == '2')
                                           
   
                                            <tr style="background-color: #fcca29; border: 1px solid white;
                                            border-collapse: collapse;">
                                                <td  class="text-center">{{$operation->operation_name}}</td>

                                                @if($operation->invoice_type == 'real')
                                                    <td  class="text-center">Facture réelle</td>
                                                 @endif

                                                 @if($operation->invoice_type == 'litigious')
                                                 <td  class="text-center">Facture litigieuse</td>
                                                 @endif

                                                 @if($operation->invoice_type == 'estimated')
                                                 <td  class="text-center">Facture Estimée</td>
                                                 @endif

                                                <td class="text-center">{{$operation->invoice->number_of_call}}</td>
                                                <td class="text-center">{{$operation->invoice->call_volume}}</td>
                                                <td class="text-center">{{number_format($operation->invoice->amount)}} {{$operator->currency}}</td>
                                            </tr>
                                          
                                            @endif

                                            @if($operation->operation_type == '3')
                                          
   
                                            <tr style="background-color: #fcca29; border: 1px solid white;
                                            border-collapse: collapse;">
                                                <td  class="text-center">{{$operation->operation_name}}</td>
                                                 <td  class="text-center"> {{$operation->invoice->invoice_type}}</td>
                                               
                                                <td class="text-center">---------</td>
                                                <td class="text-center">---------</td>
                                                <td class="text-center">{{number_format($operation->invoice->amount)}} {{$operator->currency}}</td>
                                            </tr>
                                          
                                            @endif

                                         

                                        </table>
                                    </div>

                                    
                                  
                                    <div class="row">
                                        <div class="col-4 text-center">
                                            <strong>CREANCE TOGCOCOM: <br></strong> <span  style="color: #ec1f28; font-weight: bold;"> {{number_format($operation->new_receivable)}}  {{$operator->currency}}</span>
                                        </div>
                                        <div class="col-4 text-center">
                                            <strong>DETTE TOGOCOM: <br></strong>  <span  style="color: #ec1f28; font-weight: bold;"> {{number_format($operation->new_debt)}}  {{$operator->currency}}</span> 
                                        </div>
                                        <div class="col-4 text-center">
                                            <strong>NETTING : 
                                             </strong> <br><span  style="color: #ec1f28; font-weight: bold;">  {{number_format($operation->new_netting)}}   {{$operator->currency}}</span>
                                        </div>

                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-lg-12  text-center">
                                            <p class="section-lead">{{ $operation->invoice->comment }}</p>
    
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>

                       

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
                                        <span>
                                            Ajouter le : {{$operation->invoice->created_at}}
                                        </span>
                                    </div>

                                </div>

                        </div>



                    </div>
               
           

        



    </div>
</div>
</div>
</div>
