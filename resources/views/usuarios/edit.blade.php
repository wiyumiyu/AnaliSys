@extends('partials.layouts.master')

@section('title', 'Editar Usuario')
@section('content')

<div class="row">
    <div class="col-lg-12">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Editar usuario</h4>

            <a href="{{ route('usuarios.index') }}"
               class="btn btn-light">
                ← Volver
            </a>
        </div>

        <div class="card">
            <div class="card-body">

                <form method="POST">
                    @csrf
                    @method('PUT')

                    {{-- DATOS PERSONALES --}}
                    <h5 class="fw-semibold mb-3">Datos personales</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nombre</label>
                            <input name="nombre" class="form-control"
                                   value="{{ $usuario->nombre }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Primer apellido</label>
                            <input name="apellido1" class="form-control"
                                   value="{{ $usuario->apellido1 }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Segundo apellido</label>
                            <input name="apellido2" class="form-control"
                                   value="{{ $usuario->apellido2 }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Cédula</label>
                            <input name="cedula" class="form-control"
                                   value="{{ $usuario->cedula }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="1" @selected($usuario->id_estado == 1)>Activo</option>
                                <option value="0" @selected($usuario->id_estado == 0)>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    {{-- ROL (solo admin) --}}
                    @unless($fromPerfil)
                        <hr class="my-4">

                        <h5 class="fw-semibold mb-3">Rol del usuario</h5>

                        <div class="col-md-6">
                            <select name="rol_id" class="form-select">
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->id }}"
                                        @selected($rol->id == $rolActualId)>
                                        {{ $rol->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endunless

                    <hr class="my-4">

                    {{-- CORREOS --}}
                    @include('usuarios.partials.correos')

                    <hr class="my-4">

                    {{-- TELÉFONOS --}}
                    @include('usuarios.partials.telefonos')

                    {{-- CONTRASEÑA --}}
                    @if(session('id_persona') == $usuario->id_persona)
                        <hr class="my-4">
                        @include('usuarios.partials.password')
                    @endif

                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-primary">Guardar cambios</button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-light">Cancelar</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
        </div><!--End container-fluid-->
    </main><!--End app-wrapper-->
{{-- MODALES --}}
@include('usuarios.partials.modal-delete')
@if($passwordError)
    @include('usuarios.partials.modal-password-error')
@endif

@endsection



@section('js')
<!--    <script src="/libs/apexcharts/apexcharts.min.js"></script>-->

<!--    <script src="/js/dashboard/academy.init.js"></script>-->
    <script src="/js/bootstrap.bundle.min.js"></script>
    <!-- App js -->
    <script src="/js/app.js"></script>
@endsection