@extends('partials.layouts.master_auth')

@section('title', 'Iniciar Sesión | AnaliSys')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger text-center">
        {{ $errors->first() }}
    </div>
@endif

<div>
    <img src="{{ asset('/images/auth/login_bg.jpg') }}" alt="Auth Background"
         class="auth-bg light w-full h-full opacity-60 position-absolute top-0">
    <img src="{{ asset('/images/auth/auth_bg_dark.jpg') }}" alt="Auth Background"
         class="auth-bg d-none dark">

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-10">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card mx-xxl-8">
                    <div class="card-body py-12 px-8">

                        <img src="{{ asset('/images/logo-light.svg') }}" alt="Logo"
                             height="70" class="mb-4 mx-auto d-block">

                        <h6 class="mb-3 mb-8 fw-medium text-center">
                            Inicia sesión para continuar
                        </h6>

                        <!-- 🔐 LOGIN FORM -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row g-4">

                                <!-- USUARIO / CORREO -->
                                <div class="col-12">
                                    <label class="form-label">
                                        Usuario o correo <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <input type="text"
                                               name="email"
                                               class="form-control"
                                               placeholder="usuario"
                                               value="{{ old('email') }}"
                                               required>
                                        
                                    </div>

                                    <small class="text-muted">
                                        Si es correo UCR, puede escribir solo el usuario
                                    </small>
                                </div>

                                <!-- PASSWORD -->
                                <div class="col-12">
                                    <label class="form-label">
                                        Contraseña <span class="text-danger">*</span>
                                    </label>
                                    <input type="password"
                                           name="password"
                                           class="form-control"
                                           placeholder="••••••••"
                                           required>
                                </div>

                                <!-- FORGOT -->
                                <div class="col-12 text-end">
                                    <a href="{{ route('password.request') }}"
                                       class="link link-primary text-muted text-decoration-underline">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                </div>

                                <!-- SUBMIT -->
                                <div class="col-12 mt-8">
                                    <button type="submit" class="btn btn-primary w-full mb-4">
                                        Entrar
                                        <i class="bi bi-box-arrow-in-right ms-1 fs-16"></i>
                                    </button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
@endsection
