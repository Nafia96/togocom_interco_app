<div class="modal fade bd-example-modal-lg" id="{{ 'addInvoiceModal' . $operator->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <div class="row container-fluid mt-3 ">
                    <div class="mt-3 col-12 text-center  m-auto">
                        <h6 class="modal-title" style="background-color:#03a04f; color: aliceblue ; "
                            id="myLargeModalLabel">AJOUT D'UNE FACTURATION DE {{ $operator->name }} À TOGOCOM   </h6>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="ope_to_tgc_invoice" enctype="multipart/form-data" method="post">
                    @csrf

                    @if (isset($operator))
                        <div class="mt-3  col-12 text-center  m-auto">
                            <div class="author-box-name mb-3" style="color: #03a04f; font-weight: bold;">
                                <h5>{{ $operator->name }} - {{ $operator->tel }} - {{ $operator->email }}</h5>
                            </div>
                        </div>



                        <div class="tab-content tab-bordered" id="myTab3Content">
                            <div class="tab-pane fade show active" id="about" role="tabpanel"
                                aria-labelledby="home-tab2"
                                style="background-color:#fcca29; color: aliceblue;  border-radius: 13px;">

                                <div class="row">
                                    <div class="col-md-6 col-6 b-r">
                                        <strong>Dette du TOGOCOM envers {{ $operator->name }} </strong>
                                        <br>
                                        <p class="text " style="color: #ec1f28; font-weight: bold;">{{ $operator->account->debt }}
                                            {{ $operator->currency }} </p>
                                    </div>
                                    <div class="col-md-6 col-6 b-r">
                                        <strong>Créance de {{ $operator->name }} en faveur de TOGOCOM</strong>
                                        <br>
                                        <p class="text " style="color: #ec1f28; font-weight: bold;">{{ $operator->account->receivable }}
                                            {{ $operator->currency }}</p>
                                    </div>
                                </div>




                            </div>

                            <input type="hidden" name="id_operator" value="{{ $operator->id }}">
                            <br>

                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label>De (Periode de facturation) :</label>
                                    <input name="start_period" type="text" class="form-control datepicker">
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Au (Periode de facturation) :</label>
                                    <input name="end_period" type="text" class="form-control datepicker">
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Date facturé :</label>
                                    <input name="invoice_date" type="text" class="form-control datepicker">
                                </div>

                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label for="inputEmail4">Numeros de la facture</label>
                                    <input name="invoice_number" type="text"
                                        class="form-control  @error('invoice_number') is-invalid @enderror"
                                        value="{{ @old('invoice_number') }}" placeholder="" required>

                                    @error('invoice_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">

                                    <label for="inputEmail4">Type de facture</label>
                                    <select name="invoice_type" id="inputState" class="form-control">
                                        <option selected value="real">Facture reèl</option>
                                        <option value="estimated">Facture Estimé</option>
                                        <option value="litigious">Facture Litigieux</option>

                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Montant de la facture</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                {{ $operator->currency }}
                                            </div>
                                        </div>
                                        <input name="amount" type="numeric" class="form-control  @error('amount') is-invalid @enderror" value="{{ @old('amount') }}"
                                            placeholder="" required>

                                        @error('amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>

                            </div>
                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label>Volume d'appel (En minute)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                        </div>
                                        <input name="call_volume" type="text" class="form-control
                                            @error('call_volume') is-invalid @enderror"
                                            value="{{ @old('call_volume') }}" placeholder="" required>

                                        @error('call_volume')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="form-group col-md-8">
                                    <label>Commentaire sur la facture</label>
                                    <textarea name="comment" class="form-control @error('comment') is-invalid @enderror">{{ @old('comment') }}</textarea>

                                    @error('comment')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                            </div>


                        </div>



            </div>


            <div class="d-flex justify-content-end container-fluid">

                <button type="submit" class="btn btn-primary waves-effect mb-2">Ajouter la facture</button>
            </div>
        </div>
        @endif

        </form>
    </div>
</div>
</div>
</div>
