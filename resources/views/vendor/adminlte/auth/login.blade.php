@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@stop

@php
$loginUrl = view()->getSection('login_url') ?? config('adminlte.login_url', 'login');
$registerUrl = view()->getSection('register_url') ?? config('adminlte.register_url', 'register');
$passResetUrl = view()->getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset');

if (config('adminlte.use_route_url', false)) {
$loginUrl = $loginUrl ? route($loginUrl) : '';
$registerUrl = $registerUrl ? route($registerUrl) : '';
$passResetUrl = $passResetUrl ? route($passResetUrl) : '';
} else {
$loginUrl = $loginUrl ? url($loginUrl) : '';
$registerUrl = $registerUrl ? url($registerUrl) : '';
$passResetUrl = $passResetUrl ? url($passResetUrl) : '';
}
@endphp

@section('auth_header', 'Connexion à votre compte')

@section('auth_body')
<div class="animate__animated animate__fadeIn">
    <p class="login-box-msg text-muted mb-4">Connectez-vous pour démarrer votre session</p>

    <form action="{{ route('login') }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-3 animate__animated animate__fadeInLeft">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="Adresse email" autofocus>

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

        {{-- Password field --}}
        <div class="input-group mb-3 animate__animated animate__fadeInLeft animate__delay-1s">
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

        {{-- Login field --}}
        <div class="row">
            <div class="col-6">
                <div class="icheck-primary animate__animated animate__fadeInUp animate__delay-2s" title="Se souvenir de moi pendant 30 jours">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label for="remember">
                        Se souvenir de moi
                    </label>
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }} animate__animated animate__fadeInUp animate__delay-2s">
                    <span class="fas fa-sign-in-alt"></span>
                    Se connecter
                </button>
            </div>
        </div>
    </form>
</div>
@stop

@section('auth_footer')
<div class="animate__animated animate__fadeInUp animate__delay-3s">
    {{-- Password reset link --}}
    @if($passResetUrl)
    <p class="my-0">
        <a href="{{ $passResetUrl }}" class="text-center">
            J'ai oublié mon mot de passe
        </a>
    </p>
    @endif

    {{-- Register link --}}
    @if($registerUrl)
    <p class="my-0 mt-2">
        <a href="{{ $registerUrl }}" class="text-center">
            Créer un nouveau compte
        </a>
    </p>
    @endif
</div>
@stop

@section('css')
<style>
    .login-box {
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
        $('input').focus(function() {
            $(this).parent().addClass('animate__animated animate__pulse');
            $(this).parent().css('border-color', '#28a745');
        }).blur(function() {
            $(this).parent().removeClass('animate__animated animate__pulse');
            if (!$(this).val()) {
                $(this).parent().css('border-color', '');
            }
        });

        // Focus automatique sur le champ email
        setTimeout(function() {
            $('input[name="email"]').focus();
        }, 500);
    });
</script>
@stop