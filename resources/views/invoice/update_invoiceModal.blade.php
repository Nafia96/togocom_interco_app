<div class="modal fade bd-example-modal-lg" id="{{ 'update_invoiceModal' . $resum->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <div class="row container-fluid mt-3 ">
                    <div class="mt-3 col-12 text-center  m-auto">
                        <h6 class="modal-title" style="background-color:#03a04f; color: aliceblue ; "
                            id="myLargeModalLabel">MISE A JOURS D'UNE FACTURATION DE {{ $resum->operator->name }} A TOGOCOM  </h6>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{url('update_estimated_invoice')}}"  enctype="multipart/form-data" method="post">
                    @csrf

                    @if (isset($resum->operator))
                        <div class="mt-3  col-12 text-center  m-auto">
                            <div class="author-box-name mb-3" style="color: #03a04f; font-weight: bold;">
                                <h5>{{ $resum->operator->name }} - {{ $resum->operator->tel }} - {{ $resum->operator->email }}</h5>
                            </div>
                        </div>



                        <div class="tab-content tab-bordered" id="myTab3Content">


                            <input type="hidden" name="id_operator" value="{{ $resum->operator->id }}">
                            <input type="hidden" name="operation_id" value="{{ $resum->operation2->id }}">
                            <input type="hidden" name="id_resum" value="{{ $resum->id }}">
                            <input type="hidden" name="invoice_id" value="{{ $resum->operation2->invoice->id }}">

                            <br>

                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label>Periode de facturation:</label>
                                    <input disabled class="form-control" type="month" id="start" name="period" min="2020-01" value="{{$resum->period}}" />
                                </div>



                                <div class="form-group col-md-4">
                                    <label>Date facturé :</label>
                                    <input disabled name="invoice_date" value="{{$resum->operation2->invoice->invoice_date}}" type="text" class="form-control datepicker">
                                </div>


                                <div class="form-group col-md-4">
                                    <label for="inputEmail4">Numeros de la facture</label>
                                    <input disabled name="invoice_number" type="text"
                                        class="form-control "
                                        value="{{ $resum->operation2->invoice->invoice_number }}" placeholder="" required>

                                </div>
                            </div>

                            <div class="form-row">




                                <div class="form-group col-md-4">

                                    <label for="inputEmail4">Type de facture (Automatiquement)</label>
                                    <select name="invoice_type" id="inputState" class="form-control">
                                        <option selected value="real">Facture réelle</option>


                                    </select>
                                </div>


                                <div class="form-group col-md-4">
                                    <label>Montant réelle de la facture</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                {{ $resum->operator->currency }}
                                            </div>
                                        </div>
                                        <input  name="amount" type="number" step="0.01" min="0" lang="en"
                                         class="form-control  @error('amount') is-invalid @enderror" value="{{ @old('amount') }}"
                                            placeholder="" required>

                                        @error('amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Volume d'appel (En minute)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                        </div>
                                        <input disabled name="call_volume" type="text" class="form-control " value="{{ $resum->operation2->invoice->call_volume }}" >


                                    </div>
                                </div>

                            </div>
                            <div class="form-row">


                                <div class="form-group col-md-4">
                                    <label>Nombre d'appels</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                        </div>
                                        <input disabled name="number_of_call" type="text" class="form-control" value="{{ $resum->operation2->invoice->number_of_call }}" >


                                    </div>
                                </div>
                                <div class="form-group col-md-8">
                                    <label>Commentaire sur la facture</label>
                                    <textarea disabled name="comment" class="form-control ">{{  $resum->operation2->invoice->comment }}</textarea>


                                </div>

                            </div>


                        </div>



            </div>


            <div class="d-flex justify-content-end container-fluid">

                <button type="submit" class="btn btn-primary waves-effect mb-2">Mettre à jour  la facture</button>
            </div>
        </div>
        @endif

        </form>
    </div>
</div>
</div>
</div>
