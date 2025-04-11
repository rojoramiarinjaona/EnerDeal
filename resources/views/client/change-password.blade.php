@extends('adminlte::page')

@section('title', 'Changer mon mot de passe')

@section('content_header')
    <h1>Changer mon mot de passe</h1>
@stop

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Modification du mot de passe</h3>
                    </div>
                    
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('client.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="current_password">Mot de passe actuel</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="password">Nouveau mot de passe</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-save mr-2"></i> Mettre à jour le mot de passe
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('client.profile.show') }}" class="btn btn-secondary btn-block">
                                        <i class="fas fa-arrow-left mr-2"></i> Retour au profil
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header bg-warning">
                        <h3 class="card-title">Consignes de sécurité</h3>
                    </div>
                    <div class="card-body">
                        <ul class="pl-3">
                            <li>Votre mot de passe doit contenir au moins 8 caractères</li>
                            <li>Utilisez une combinaison de lettres majuscules et minuscules</li>
                            <li>Incluez au moins un chiffre et un caractère spécial</li>
                            <li>Évitez d'utiliser des informations personnelles facilement devinables</li>
                            <li>Ne réutilisez pas un mot de passe que vous utilisez sur d'autres sites</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            margin-bottom: 1rem;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Page de changement de mot de passe chargée!');
    </script>
@stop 