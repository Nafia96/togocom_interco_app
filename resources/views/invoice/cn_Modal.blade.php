<div class="modal fade bd-example-modal-lg" id="{{ 'cn' . $operation->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <div class="row container-fluid mt-3 ">
                    <div class="mt-3 col-12 text-center  m-auto">
                        <h6 class="modal-title" style="background-color:#03a04f; color: aliceblue ; "
                            id="myLargeModalLabel">AJOUT D'UNE NOTE DE CREDIT </h6>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('add_cn') }}" enctype="multipart/form-data" method="post">
                    @csrf

                    @if (isset($operation->invoice->contestation->amount))
                        <div class="mt-3  col-12 text-center  m-auto">
                            <div class="author-box-name mb-3" style="color: #03a04f; font-weight: bold;">
                                <h5>{{ $operation->operator->name }} - {{ $operation->operator->tel }} - {{$operation->operator->email }}</h5>
                            </div>
                        </div>



                        <div class="tab-content tab-bordered" id="myTab3Content">



                            <input type="hidden" name="id_operator" value="{{ $operation->operator->id }}">
                            <input type="hidden" name="operation_id" value="{{ $operation->id }}">
                            <input type="hidden" name="invoice_id" value="{{ $operation->invoice->id }}">
                            <input type="hidden" name="contestation_id"
                                value="{{ $operation->invoice->contestation->id }}">

                            <br>



                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label>Montant contesté</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                {{ $operation->operator->currency }}
                                            </div>
                                        </div>
                                        <input disabled name="amount" type="number" step="0.01" min="0" lang="en"
                                            class="form-control  @error('amount') is-invalid @enderror"
                                            value="{{ $operation->invoice->contestation->amount }}" placeholder=""
                                            required>

                                        @error('amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>

                               

                                <div class="form-group col-md-6">
                                    <label>Sur</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                {{ $operation->operator->currency }}
                                            </div>
                                        </div>
                                        <input disabled name="olde_amount" type="number" step="0.01" min="0" lang="en"
                                            class="form-control  @error('olde_amount') is-invalid @enderror"
                                            value="{{ $operation->invoice->amount }}" placeholder="" required>

                                        @error('olde_amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>





                            </div>

                            @if ($operation->invoice->contestation->comment != null)
                            <div class="form-group col-md-12">
                                 Commentaire:
                                 <span style="color: #ec1f28;   font-size: 12px;"> {{ $operation->invoice->contestation->comment }} </span>

                            </div>
                        @endif


                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label>Montant du note de credit</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                {{ $operation->operator->currency }}
                                            </div>
                                        </div>
                                        <input name="amount" type="number" step="0.01" min="0" lang="en"
                                            class="form-control  @error('amount') is-invalid @enderror" value=""
                                            placeholder="" required>

                                        @error('amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="form-group col-md-6">

                                    <label for="inputEmail4">En diminution de : </label>
                                    <select name="cn_type" id="inputState" class="form-control">
                                        <option selected value="debt">DETTE</option>
                                        <option value="receivable">CREANCE</option>

                                    </select>
                                </div>





                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-12">
                                    <label>Commentaire sur la note de credit</label>
                                    <textarea name="comment" class="form-control ">{{ $operation->invoice->comment }}</textarea>


                                </div>

                            </div>


                        </div>



            </div>


            <div class="d-flex justify-content-end container-fluid">

                <button type="submit" class="btn btn-primary waves-effect mb-2">AJOUTER LA NOTE DE CREDIT</button>
            </div>
        </div>
        @endif

        </form>
    </div>
</div>
</div>
</div>
