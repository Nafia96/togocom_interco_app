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
                                    <h4>Connexion</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{url('/')}}">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="login">Nom d'utilisateur</label>
                                        <input id="login" type="login" placeholder="Votre nom d'utilisateur" class="form-control" name="login" required value="{{old('login')}}">
                                        <div class="invalid-feedback">
                                            Veuillez remplir votre nom d'utilisateur
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="d-block">
                                            <label for="password" class="control-label">Mot de passe</label>
                                            <div class="float-right">
                                                <a href="{{route('forgot_password')}}" class="text-small">
                                                    Mot de passe oublié?
                                                </a>
                                            </div>
                                        </div>
                                        <input id="password" type="password" placeholder="Votre mot de passe" class="form-control" name="password" tabindex="2" required>
                                        <div class="invalid-feedback">
                                            Renseigner votre mot de passe
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                                            <label class="custom-control-label" for="remember-me">Se souvenir de moi
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                   <button type="submit" class="btn btn-primary btn-lg btn-block"   tabindex="4">
                                        Connexion
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
