@extends('adminlte::auth.auth-page', ['auth_type' => 'register'])

@php
$loginUrl = view()->getSection('login_url') ?? config('adminlte.login_url', 'login');
$registerUrl = view()->getSection('register_url') ?? config('adminlte.register_url', 'register');

if (config('adminlte.use_route_url', false)) {
$loginUrl = $loginUrl ? route($loginUrl) : '';
$registerUrl = $registerUrl ? route($registerUrl) : '';
} else {
$loginUrl = $loginUrl ? url($loginUrl) : '';
$registerUrl = $registerUrl ? url($registerUrl) : '';
}
@endphp

@section('adminlte_css_pre')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@stop

@section('auth_header', 'Créer un nouveau compte')

@section('auth_body')
<div class="animate__animated animate__fadeIn">
    <p class="login-box-msg text-muted mb-4">Inscrivez-vous pour accéder à la plateforme</p>

    <form action="{{ $registerUrl }}" method="post">
        @csrf

        {{-- Nom field --}}
        <div class="input-group mb-3 animate__animated animate__fadeInLeft">
            <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                value="{{ old('nom') }}" placeholder="Nom" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('nom')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        {{-- Prénom field --}}
        <div class="input-group mb-3 animate__animated animate__fadeInLeft animate__delay-1s">
            <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                value="{{ old('prenom') }}" placeholder="Prénom">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('prenom')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        {{-- Email field --}}
        <div class="input-group mb-3 animate__animated animate__fadeInLeft animate__delay-2s">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="Adresse email">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        {{-- Lieu de résidence field --}}
        <div class="input-group mb-3 animate__animated animate__fadeInLeft animate__delay-3s">
            <input type="text" name="lieu_de_residence" class="form-control @error('lieu_de_residence') is-invalid @enderror"
                value="{{ old('lieu_de_residence') }}" placeholder="Lieu de résidence">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-map-marker-alt {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('lieu_de_residence')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        {{-- Rôle field --}}
        <div class="input-group mb-3 animate__animated animate__fadeInLeft animate__delay-4s">
            <select name="role" class="form-control @error('role') is-invalid @enderror">
                <option value="" disabled selected>Choisir un rôle</option>
                <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client</option>
                <option value="vendeur" {{ old('role') == 'vendeur' ? 'selected' : '' }}>Vendeur</option>
                <!-- <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option> -->
            </select>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user-tag {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('role')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3 animate__animated animate__fadeInLeft animate__delay-5s">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="Mot de passe">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        {{-- Confirm password field --}}
        <div class="input-group mb-3 animate__animated animate__fadeInLeft animate__delay-5s">
            <input type="password" name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                placeholder="Confirmer le mot de passe">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password_confirmation')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        {{-- Register button --}}
        <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }} animate__animated animate__fadeInUp animate__delay-6s">
            <span class="fas fa-user-plus"></span>
            S'inscrire
        </button>
    </form>
</div>
@stop

@section('auth_footer')
<div class="animate__animated animate__fadeInUp animate__delay-6s">
    <p class="my-0">
        <a href="{{ $loginUrl }}" class="text-center">
            J'ai déjà un compte
        </a>
    </p>
</div>
@stop

@section('css')
<style>
    .register-box {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .card {
        border: none;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .input-group-text {
        background-color: #f4f6f9;
        border-right: none;
    }

    .form-control {
        border-left: none;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        border-color: #28a745;
    }

    .btn-primary {
        background-color: #28a745;
        border-color: #28a745;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #218838;
        border-color: #1e7e34;
        transform: translateY(-2px);
    }

    .login-box-msg {
        font-size: 1.1rem;
        font-weight: 300;
    }

    a {
        color: #28a745;
        transition: all 0.3s ease;
    }

    a:hover {
        color: #218838;
        text-decoration: none;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Animation de focus sur les champs
        $('input, select').focus(function() {
            $(this).parent().addClass('animate__animated animate__pulse');
            $(this).parent().css('border-color', '#28a745');
        }).blur(function() {
            $(this).parent().removeClass('animate__animated animate__pulse');
            if (!$(this).val()) {
                $(this).parent().css('border-color', '');
            }
        });

        // Focus automatique sur le premier champ
        setTimeout(function() {
            $('input[name="nom"]').focus();
        }, 500);
    });
</script>
@stop