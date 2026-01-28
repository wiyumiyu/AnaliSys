@extends('partials.layouts.master_auth')

@section('title', 'Cambiar contraseña | AnaliSys')

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

                        <img src="/images/logo-light.svg"
                             alt="Logo Dark"
                             height="60"
                             class="mb-4 mx-auto d-block">

                        <h6 class="mb-3 mb-8 fw-medium text-center">
                            ¿Olvidaste tu contraseña?
                        </h6>

                        {{-- FORMULARIO --}}
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="row g-4">
                                <div class="col-12">
                                    <label for="email" class="form-label">
                                        Email <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="form-control"
                                        placeholder="Digite su correo electrónico"
                                        value="{{ old('email') }}"
                                        required
                                    >
                                </div>

                                <div class="col-12 mt-8">
                                    <button type="submit"
                                            class="btn btn-primary w-full mb-5">
                                        Enviar correo de recuperación
                                    </button>
                                </div>
                            </div>

                            <p class="mb-0 fw-normal position-relative text-center fs-12">
                                Regresar al
                                <a href="{{ route('login') }}"
                                   class="text-decoration-underline text-primary">
                                    Inicio de sesión
                                </a>
                            </p>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL ERROR CORREO ================= --}}
<div class="modal fade"
     id="errorEmailModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    Error en la recuperación
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <p class="mb-0">
                    {{ $errors->first('email') }}
                </p>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')

{{-- ABRIR MODAL AUTOMÁTICAMENTE SI HAY ERROR --}}
@if ($errors->has('email'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(
            document.getElementById('errorEmailModal')
        );
        modal.show();
    });
</script>
@endif

@endsection
