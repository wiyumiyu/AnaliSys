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
                                        id="password"
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
                                        id="password_confirmation"
                                        class="form-control"
                                        placeholder="Confirme su nueva contraseña"
                                        required
                                        >
                                </div>

                                <div class="mt-3">
                                    <ul class="small" id="passwordChecklist">
                                        <li id="length" class="text-danger">Mínimo 8 caracteres</li>
                                        <li id="uppercase" class="text-danger">Una mayúscula</li>
                                        <li id="number" class="text-danger">Un número</li>
                                        <li id="symbol" class="text-danger">Un símbolo</li>
                                        <li id="match" class="text-danger">Las contraseñas coinciden</li>
                                    </ul>
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
<script>
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const submitBtn = document.querySelector('button[type="submit"]');

    const lengthItem = document.getElementById('length');
    const uppercaseItem = document.getElementById('uppercase');
    const numberItem = document.getElementById('number');
    const symbolItem = document.getElementById('symbol');
    const matchItem = document.getElementById('match');

    submitBtn.disabled = true;

    function validarPassword() {
        const password = passwordInput.value;
        const confirm = confirmInput.value;

        const reglas = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            number: /[0-9]/.test(password),
            symbol: /[\W_]/.test(password),
            match: password === confirm && password.length > 0
        };

        actualizarEstado(lengthItem, reglas.length);
        actualizarEstado(uppercaseItem, reglas.uppercase);
        actualizarEstado(numberItem, reglas.number);
        actualizarEstado(symbolItem, reglas.symbol);
        actualizarEstado(matchItem, reglas.match);

        const valido = Object.values(reglas).every(v => v === true);
        submitBtn.disabled = !valido;

        if (valido) {
            passwordInput.classList.remove('is-invalid');
            passwordInput.classList.add('is-valid');
            confirmInput.classList.remove('is-invalid');
            confirmInput.classList.add('is-valid');
        } else {
            passwordInput.classList.remove('is-valid');
            passwordInput.classList.add('is-invalid');
            confirmInput.classList.remove('is-valid');
            confirmInput.classList.add('is-invalid');
        }
    }

    function actualizarEstado(elemento, cumple) {
        if (cumple) {
            elemento.classList.remove('text-danger');
            elemento.classList.add('text-success');
        } else {
            elemento.classList.remove('text-success');
            elemento.classList.add('text-danger');
        }
    }

    passwordInput.addEventListener('input', validarPassword);
    confirmInput.addEventListener('input', validarPassword);
</script>
@endsection