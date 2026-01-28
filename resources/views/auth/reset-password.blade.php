@extends('partials.layouts.master')

@section('title', 'Restablecer contraseña')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-6">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Restablecer contraseña</h4>

            <a href="{{ route('login') }}" class="btn btn-light">
                ← Volver al login
            </a>
        </div>

        <div class="card">
            <div class="card-body">

                {{-- MENSAJE --}}
                <p class="text-muted mb-4">
                    Ingresa tu nueva contraseña para recuperar el acceso a tu cuenta.
                </p>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    {{-- TOKEN --}}
                    <input type="hidden" name="token" value="{{ $token }}">

                    {{-- EMAIL (opcional pero recomendado) --}}
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ request('email') }}"
                               readonly>
                    </div>

                    {{-- NUEVA CONTRASEÑA --}}
                    <div class="mb-3">
                        <label class="form-label">Nueva contraseña</label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               required>
                    </div>

                    {{-- CONFIRMAR --}}
                    <div class="mb-4">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               required>
                    </div>

                    {{-- BOTONES --}}
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary">
                            Cambiar contraseña
                        </button>

                        <a href="{{ route('login') }}" class="btn btn-light">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection
