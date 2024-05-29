<div class="modal fade bd-example-modal-lg" id="{{ 'settlementModal' . $operator->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <div class="row container-fluid mt-3 ">
                    <div class="mt-3 col-12 text-center  m-auto">
                        <h6 class="modal-title" style="background-color:#fcca29; color: #ec1f28 ; "
                            id="myLargeModalLabel">AJOUT D'UN RÈGLEMENT DE {{ $operator->name }} AVEC TOGOCOM   </h6>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('add_settlement') }}" enctype="multipart/form-data" method="post">
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
                                style="background-color:#E5E8E8; color: #03a04f;  border-radius: 13px;">

                                <div class="row">

                                    <div class="col-md-6 col-6 b-r">
                                        <strong>CREANCES TOGOCOM AVEC {{ $operator->name }} </strong>
                                        <br>
                                        <p class="text " style="color: #ec1f28; font-weight: bold;">{{ number_format($operator->account->receivable, 2, ',', ' ') }}
                                            {{ $operator->currency }}</p>
                                    </div>

                                    <div class="col-md-6 col-6 b-r">
                                        <strong>DETTES TOGOCOM AVEC {{ $operator->name }} </strong>
                                        <br>
                                        <p class="text " style="color: #ec1f28; font-weight: bold;">{{ number_format($operator->account->debt, 2, ',', ' ') }}
                                            {{ $operator->currency }} </p>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="mt-3  col-12 text-center  m-auto">
                                       <h2> <strong>NETING ACTUEL</strong> </h2>
                                        <br>
                                    <h3>    <p class="text " style="color: #ec1f28; font-weight: bold; ">{{  number_format($operator->account->receivable -  $operator->account->debt, 2, ',', ' ') }}
                                            {{ $operator->currency }} </p> </h3>
                                    </div>

                                </div>





                            </div>

                            <input type="hidden" name="id_operator" value="{{ $operator->id }}">
                            <br>

                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label>Date de valeur  :</label>
                                    <input name="invoice_date" type="text" class="form-control datepicker">
                                </div>



                                <div class="form-group col-md-4">
                                    <label>Valeur (montant)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                {{ $operator->currency }}
                                            </div>
                                        </div>
                                        <input name="amount" type="number" step="0.01" min="0" lang="en"
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

                                    <label for="inputEmail4">En faveur de: </label>
                                    <select name="type" id="inputState" class="form-control">
                                        <option selected value="Encaissement">TOGOCOM</option>
                                        <option value="Decaissement">{{ $operator->name }}</option>

                                    </select>
                                </div>

                            </div>


                            <div class="form-row">

                                <div class="form-group col-md-12">
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
