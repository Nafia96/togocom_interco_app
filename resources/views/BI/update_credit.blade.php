<div class="modal fade bd-example-modal-lg" id="{{ 'update_all_invoice' . $rcredit->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <div class="row container-fluid mt-3 ">
                    <div class="mt-3 col-12 text-center  m-auto">
                        <h6 class="modal-title" style="background-color:#03a04f; color: aliceblue ; "
                            id="myLargeModalLabel">MISE A JOUR DU MONTANT </h6>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('update_credit') }}" enctype="multipart/form-data" method="post">
                    @csrf

                    @if (isset($rcredits))
                     <div class="tab-content tab-bordered" id="myTab3Content">
                             <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label>Date :</label>
                                    <input name="invoice_date" value="{{ $rcredit->date }}"
                                           type="text" class="form-control datepicker" disabled>
                                </div>

                                <input type="text" hidden name="id" value="{{ $rcredit->id }}">

                                 <div class="form-group col-md-6">
                                    <label>Montant</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                               XOF
                                            </div>
                                        </div>
                                        <input name="amount" type="number" step="0.01" min="0" lang="en"
                                            class="form-control  @error('amount') is-invalid @enderror"
                                            value="{{ $rcredit->amount }}" placeholder="" required>

                                        @error('amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                            </div>
                        </div>



            </div>


            <div class="d-flex justify-content-end container-fluid">

                <button type="submit" class="btn btn-primary waves-effect mb-2">Mettre à jour le montant</button>
            </div>
        </div>
        @endif

        </form>
    </div>
</div>
</div>
</div>
