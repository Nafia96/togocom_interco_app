@extends('template.register_login_tamplate')

@section('title','Réinitialiser votre mot de passe')

@section('content')

    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="card card-primary">

                            <div class="card-body">
                                <p class="text-muted">

                                    Nous vous avons envoyé ce lien  pour réinitialiser votre mot de passe</p>

                                <div class="form-group">
                                    <a  href="{{url('forget_password_confirme/?t='.$admin['token'])}}">    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Cliquez ici pour réinitialiser votre mot de passe

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
