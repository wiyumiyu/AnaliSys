@extends('partials.layouts.master')

@section('title', 'Editar Usuario')

@section('content')

<div class="row">
    <div class="col-lg-12">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Editar usuario</h4>

            <a href="{{ route('usuarios.index') }}" class="btn btn-light">
                ← Volver
            </a>
        </div>

        <div class="card">
            <div class="card-body">

                <form method="POST" action="{{ route('usuarios.update', $usuario->id_persona) }}">
                    @csrf
                    @method('PUT')

                    {{-- ================= DATOS PERSONALES ================= --}}
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

                    {{-- ================= ROL ================= --}}
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

                    {{-- ================= CORREOS ================= --}}
                    <hr class="my-4">
                    <h5 class="fw-semibold">Correos electrónicos</h5>

                    <div id="correos-container">
                        @foreach($correos as $c)
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <input class="form-control" value="{{ $c->correo }}" disabled>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" value="{{ $c->descripcion }}" disabled>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ url()->current() }}?del_correo={{ $c->id }}"
                                       class="btn btn-outline-danger w-100">✖</a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button"
                            class="btn btn-outline-primary btn-sm mt-2"
                            onclick="agregarCorreo()">
                        Agregar correo
                    </button>

                    {{-- ================= TELÉFONOS ================= --}}
                    <hr class="my-4">
                    <h5 class="fw-semibold">Teléfonos</h5>

                    <div id="telefonos-container">
                        @foreach($telefonos as $t)
                            <div class="row g-2 mb-2 telefono-row">
                                <div class="col-md-5">
                                    <input name="telefono_existente[{{ $t->id }}]"
                                           class="form-control telefono-input"
                                           value="{{ $t->telefono }}" readonly>
                                </div>

                                <div class="col-md-5">
                                    <select name="telefono_tipo_existente[{{ $t->id }}]"
                                            class="form-select telefono-select d-none">
                                        @foreach($tiposTelefono as $tt)
                                            <option value="{{ $tt->id }}"
                                                @selected($tt->id == $t->id_telefono_tipo)>
                                                {{ $tt->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input class="form-control telefono-tipo-text"
                                           value="{{ $t->tipo }}" readonly>
                                </div>

                                <div class="col-md-2">
                                    <a href="{{ url()->current() }}?del_tel={{ $t->id }}"
                                       class="btn btn-outline-danger w-100">✖</a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button"
                            class="btn btn-outline-primary btn-sm mt-2"
                            onclick="agregarTelefono()">
                        Agregar teléfono
                    </button>

                    {{-- ================= BOTONES ================= --}}
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
@endsection

@section('js')

<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/app.js"></script>

<script>
    const opcionesTelefono = `
        @foreach($tiposTelefono as $tt)
            <option value="{{ $tt->id }}">{{ $tt->nombre }}</option>
        @endforeach
    `;

    document.querySelectorAll('.telefono-row').forEach(row => {
        row.addEventListener('mouseenter', () => {
            row.querySelector('.telefono-input').readOnly = false;
            row.querySelector('.telefono-select').classList.remove('d-none');
            row.querySelector('.telefono-tipo-text').classList.add('d-none');
        });
        row.addEventListener('mouseleave', () => {
            row.querySelector('.telefono-input').readOnly = true;
            row.querySelector('.telefono-select').classList.add('d-none');
            row.querySelector('.telefono-tipo-text').classList.remove('d-none');
        });
    });

    function agregarCorreo() {
        document.getElementById('correos-container').insertAdjacentHTML('beforeend', `
            <div class="row g-2 mb-2">
                <div class="col-md-6">
                    <input type="email" name="nuevo_correo[]" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <select name="correo_desc[]" class="form-select">
                        <option value="SECUNDARIO">Secundario</option>
                        <option value="PRINCIPAL">Principal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary w-100"
                        onclick="this.closest('.row').remove()">—</button>
                </div>
            </div>
        `);
    }

    function agregarTelefono() {
        document.getElementById('telefonos-container').insertAdjacentHTML('beforeend', `
            <div class="row g-2 mb-2">
                <div class="col-md-5">
                    <input name="nuevo_telefono[]" class="form-control" required>
                </div>
                <div class="col-md-5">
                    <select name="telefono_tipo[]" class="form-select" required>
                        ${opcionesTelefono}
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary w-100"
                        onclick="this.closest('.row').remove()">—</button>
                </div>
            </div>
        `);
    }
</script>
@endsection
