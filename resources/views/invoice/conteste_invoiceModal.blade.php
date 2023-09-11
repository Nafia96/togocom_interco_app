<div class="modal fade bd-example-modal-lg" id="{{ 'contest_invoice' . $operation->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <div class="row container-fluid mt-3 ">
                    <div class="mt-3 col-12 text-center  m-auto">
                        <h6 class="modal-title" style="background-color:#03a04f; color: aliceblue ; "
                            id="myLargeModalLabel">AJOUT D'UNE CONTESTATION POUR CETTE FACTURE </h6>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('add_contestation') }}" enctype="multipart/form-data" method="post">
                    @csrf

                    @if (isset($operation->operator))
                        <div class="mt-3  col-12 text-center  m-auto">
                            <div class="author-box-name mb-3" style="color: #03a04f; font-weight: bold;">
                                <h5>{{ $operation->operator->name }} - {{ $operation->operator->tel }} - {{ $operation->operator->email }}</h5>
                            </div>
                        </div>



                        <div class="tab-content tab-bordered" id="myTab3Content">
                           


                            <input type="hidden" name="id_operator" value="{{ $operation->operator->id }}">
                            <input type="hidden" name="operation_id" value="{{ $operation->id }}">
                            <input type="hidden" name="invoice_id" value="{{ $operation->invoice->id }}">

                            <br>

                        

                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label>Montant contesté</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                {{ $operation->operator->currency }}
                                            </div>
                                        </div>
                                        <input name="amount" type="numeric"
                                            class="form-control  @error('amount') is-invalid @enderror"
                                            value="{{ @old('amount') }}" placeholder="" required>

                                        @error('amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Sur</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                {{ $operation->operator->currency }}
                                            </div>
                                        </div>
                                        <input disabled name="olde_amount" type="numeric"
                                            class="form-control  @error('olde_amount') is-invalid @enderror"
                                            value="{{ $operation->invoice->amount }}" placeholder="" required>

                                        @error('olde_amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Date de contestation  :</label>
                                    <input name="contesation_date" type="text" class="form-control datepicker">
                                </div>


                               

                            </div>
                            <div class="form-row">

                                <div class="form-group col-md-12">
                                    <label>Commentaire sur la contestation</label>
                                    <textarea  name="comment" class="form-control ">{{ $operation->invoice->comment }}</textarea>


                                </div>

                            </div>


                        </div>



            </div>


            <div class="d-flex justify-content-end container-fluid">

                <button type="submit" class="btn btn-primary waves-effect mb-2">AJOUTER LA CONTESTATION</button>
            </div>
        </div>
        @endif

        </form>
    </div>
</div>
</div>
</div>
