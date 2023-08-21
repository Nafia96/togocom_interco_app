@extends('template.register_login_tamplate')

@section('title','login')

@section('content')

    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4> Mot de passe oublié</h4>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{'Nous avons envoyé votre lien de réinitialisation de mot de passe!'}}
                                    </div>
                                @endif
                                    Nous vous enverrons un lien pour réinitialiser votre mot de passe</p>
                                <form method="POST" action="/forget_password">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                            Réinitialiser le mot de passe

                                        </button>
                                    </div>
                                </form>
                                <div class="form-group">
                                    <a  href="{{url('/')}}">    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Retour à la connexion

                                    </button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
