@extends('partials.layouts.master')

@section('title', 'Nuevo Usuario')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <br>
        <div class="card">

            <div class="card-header">
                <div class="d-flex flex-wrap gap-4 justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Nuevo usuario</h5>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-primary">
                        ← Volver
                    </a>
                </div>
            </div>

            <div class="card-body">

                {{-- ERRORES --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('usuarios.store') }}">
                    @csrf

                    {{-- ================= DATOS PERSONALES ================= --}}
                    <h5 class="fw-semibold mb-3">Datos personales</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nombre</label>
                            <input name="nombre"
                                   class="form-control"
                                   value="{{ old('nombre') }}"
                                   required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Primer apellido</label>
                            <input name="apellido1"
                                   class="form-control"
                                   value="{{ old('apellido1') }}"
                                   required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Segundo apellido</label>
                            <input name="apellido2"
                                   class="form-control"
                                   value="{{ old('apellido2') }}"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Cédula</label>
                            <input name="cedula"
                                   class="form-control"
                                   value="{{ old('cedula') }}"
                                   required>
                        </div>
                    </div>

                    {{-- ================= ROL ================= --}}
                    <br>
                    <h5 class="fw-semibold mb-3">Rol del usuario</h5>

                    <div class="col-md-6">
                        <select name="rol_id" class="form-select" required>
                            <option value="">Seleccione un rol</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id }}"
                                    @selected(old('rol_id') == $rol->id)>
                                    {{ $rol->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ================= CONTRASEÑA ================= --}}
                    <br><br>
                    <h5 class="fw-semibold">Contraseña</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Contraseña</label>
                            <input type="password"
                                   name="password_nueva"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirmar contraseña</label>
                            <input type="password"
                                   name="password_confirmar"
                                   class="form-control"
                                   required>
                        </div>
                    </div>

                    <small class="text-muted d-block mt-2">
                        Mínimo 8 caracteres, una mayúscula, un número y un símbolo.
                    </small>

                    {{-- ================= CORREOS ================= --}}
                    <br><br>
                    <h5 class="fw-semibold">Correos electrónicos</h5>

                    <div id="correos-container"></div>

                    <button type="button"
                            class="btn btn btn-outline-secondary btn-sm mt-2"
                            onclick="agregarCorreo()">
                        Agregar correo
                    </button>

                    {{-- ================= TELÉFONOS ================= --}}
                    <br><br><br>
                    <h5 class="fw-semibold">Teléfonos</h5>

                    <div id="telefonos-container"></div>

                    <button type="button"
                            class="btn btn-outline-secondary btn-sm mt-2"
                            onclick="agregarTelefono()">
                        Agregar teléfono
                    </button>

                    {{-- ================= BOTONES ================= --}}
                    <br><br>
                    <hr class="my-4">

                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-primary">Crear usuario</button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-light-primary">Cancelar</a>
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

<!-- Bootstrap -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

<!-- DataTables CORE -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<!-- FabKin Datatable Init -->
<script src="{{ asset('js/table/datatable.init.js') }}"></script>

<script src="{{ asset('libs/simplebar/simplebar.min.js') }}"></script>
<!-- FabKin App -->
<script src="{{ asset('js/app.js') }}"></script>

@endsection


@section('js')
<!-- Bootstrap -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('libs/simplebar/simplebar.min.js') }}"></script>
<!-- FabKin App -->
<script src="{{ asset('js/app.js') }}"></script>

<script>
/* ==========================================================
   AGREGAR CORREO
========================================================== */
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
                <button type="button"
                        class="btn btn-outline-secondary w-100"
                        onclick="this.closest('.row').remove()">—</button>
            </div>
        </div>
    `);
}

/* ==========================================================
   AGREGAR TELÉFONO
========================================================== */
const opcionesTelefono = `
@foreach($tiposTelefono as $tt)
    <option value="{{ $tt->id }}">{{ $tt->nombre }}</option>
@endforeach
`;

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
                <button type="button"
                        class="btn btn-outline-secondary w-100"
                        onclick="this.closest('.row').remove()">—</button>
            </div>
        </div>
    `);
}
</script>
@endsection
