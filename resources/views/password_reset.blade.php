@extends('template.register_login_tamplate')

@section('title','login')

@section('content')

    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="card card-primary">
                            <div class="row container-fluid mt-3 ">
                                <div class="mt-3 col-12 text-center m-auto" >
                                    <h4>Réinitialiser votre mot de passe</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{url('/add_new_password')}}">
                                    {{ csrf_field() }}

                                    <div class="form-group">
                                        <div class="d-block">
                                            <label for="password" class="control-label">Nouveau mot de passe</label>

                                        </div>
                                        <input type="password"  name="password" placeholder="Votre nouveau mot de passe" class="form-control  @error('password') is-invalid @enderror" tabindex="2" required>

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                         </span>
                                        @enderror

                                    </div>
                                    <input type="hidden" name="token" value="{{$token}}">
                                    <div class="form-group">
                                        <div class="d-block">
                                            <label for="password" class="control-label">Confirmer nouveau mot de passe</label>

                                        </div>
                                        <input type="password" name="password_confirmation" placeholder="Confirmer le nouveau mot de passe" class="form-control @error('password_confirmation') is-invalid @enderror" tabindex="2" required>

                                        @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                         </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                            Réinitialiser mot de passe
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
