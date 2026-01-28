@extends('partials.layouts.master_auth')

@section('title', 'Restablecer contraseña | AnaliSys')

@section('content')

<!-- START -->
<div>
    <img src="/images/auth/login_bg.jpg" alt="Auth Background"
         class="auth-bg light w-full h-full opacity-60 position-absolute top-0">
    <img src="/images/auth/auth_bg_dark.jpg" alt="Auth Background" class="auth-bg d-none dark">

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-10">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card mx-xxl-8">
                    <div class="card-body py-12 px-8">

                        {{-- LOGO --}}
                        <img src="/images/logo-light.svg"
                             alt="Logo Dark"
                             height="60"
                             class="mb-4 mx-auto d-block">

                        {{-- TITULO --}}
                        <h6 class="mb-3 fw-medium text-center">
                            Restablecer contraseña
                        </h6>

                        {{-- DESCRIPCIÓN --}}
                        <p class="text-muted text-center mb-8 fs-13">
                            Ingresa tu nueva contraseña para recuperar el acceso a tu cuenta.
                        </p>

                        {{-- FORMULARIO --}}
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            {{-- TOKEN --}}
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="row g-4">

                                {{-- NUEVA CONTRASEÑA --}}
                                <div class="col-12">
                                    <label class="form-label">
                                        Nueva contraseña <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="password"
                                        name="password"
                                        class="form-control"
                                        placeholder="Digite su nueva contraseña"
                                        required
                                    >
                                </div>

                                {{-- CONFIRMAR CONTRASEÑA --}}
                                <div class="col-12">
                                    <label class="form-label">
                                        Confirmar contraseña <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        class="form-control"
                                        placeholder="Confirme su nueva contraseña"
                                        required
                                    >
                                </div>

                                {{-- BOTÓN --}}
                                <div class="col-12 mt-6">
                                    <button type="submit"
                                            class="btn btn-success w-full">
                                        Cambiar contraseña
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
