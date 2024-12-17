<div class="modal fade bd-example-modal-lg" id="{{ 'update_all_invoice' . $operation->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <div class="row container-fluid mt-3 ">
                    <div class="mt-3 col-12 text-center  m-auto">
                        <h6 class="modal-title" style="background-color:#03a04f; color: aliceblue ; "
                            id="myLargeModalLabel">MISE A JOUR DE LA FACTURE </h6>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('update_all_invoice') }}" enctype="multipart/form-data" method="post">
                    @csrf

                    @if (isset($operator))
                        <div class="mt-3  col-12 text-center  m-auto">
                            <div class="author-box-name mb-3" style="color: #03a04f; font-weight: bold;">
                                <h5>{{ $operator->name }} - {{ $operator->tel }} - {{ $operator->email }}</h5>
                            </div>
                        </div>



                        <div class="tab-content tab-bordered" id="myTab3Content">


                            <input type="hidden" name="id_operator" value="{{ $operator->id }}">
                            <input type="hidden" name="operation_id" value="{{ $operation->id }}">
                            <input type="hidden" name="invoice_id" value="{{ $operation->invoice->id }}">

                            <br>

                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label>Periode de facturation:</label>
                                    <input  class="form-control" type="month" id="start" name="period"
                                        min="2020-01" value="{{ $operation->invoice->period }}" />
                                </div>



                                <div class="form-group col-md-4">
                                    <label>Date facturé :</label>
                                    <input  name="invoice_date" value="{{ $operation->invoice->invoice_date }}"
                                        type="text" class="form-control datepicker">
                                </div>


                                <div class="form-group col-md-4">
                                    <label for="inputEmail4">Numeros de la facture</label>
                                    <input  name="invoice_number" type="text" class="form-control "
                                        value="{{ $operation->invoice->invoice_number }}" placeholder="" required>

                                </div>
                            </div>

                            <div class="form-row">




                                <div class="form-group col-md-4">

                                    <label for="inputEmail4">Type de facture </label>
                                    <select  name="invoice_type" id="inputState" class="form-control">
                                        <option {{ $operation->invoice->invoice_type == 'real' ? 'selected' : '' }}
                                            value="real">Facture réelle</option>
                                        <option
                                            {{ $operation->invoice->invoice_type == 'estimated' ? 'selected' : '' }}
                                            value="estimated">Facture Estimée</option>



                                    </select>
                                </div>


                                <div class="form-group col-md-4">
                                    <label>Montant réelle de la facture</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                {{ $operator->currency }}
                                            </div>
                                        </div>
                                        <input name="amount" type="number" step="0.01" min="0" lang="en"
                                            class="form-control  @error('amount') is-invalid @enderror"
                                            value="{{ $operation->invoice->amount }}" placeholder="" required>

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
                                        <input  name="call_volume" type="text" class="form-control "
                                            value="{{ $operation->invoice->call_volume }}">


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
                                        <input  name="number_of_call" type="text" class="form-control"
                                            value="{{ $operation->invoice->number_of_call }}">


                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Importer une nouvelle facture</label>
                                    <div class="input-group">

                                        <input name="the_file" type="file" class="form-control" >


                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Commentaire sur la facture</label>
                                    <textarea  name="comment" class="form-control ">{{ $operation->invoice->comment }}</textarea>


                                </div>

                            </div>


                        </div>



            </div>


            <div class="d-flex justify-content-end container-fluid">

                <button type="submit" class="btn btn-primary waves-effect mb-2">Mettre à jour la facture</button>
            </div>
        </div>
        @endif

        </form>
    </div>
</div>
</div>
</div>
