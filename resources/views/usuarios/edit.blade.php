@extends('partials.layouts.master')

@section('title', 'Editar Usuario')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <br>
        <div class="card">

            <div class="card-header">
                <div class="d-flex flex-wrap gap-4 justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Editar usuario</h5>
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <a href="{{ route('usuarios.index') }}"class="btn btn-primary">
                            ← Volver
                        </a>



                    </div>
                </div>
            </div>               

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
                    @unless( $esPerfilPropio)
                    <!--                    <hr class="my-4">  esto es una linea -->
                    <br>
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
                    <br><br>
                    <h5 class="fw-semibold">Correos electrónicos</h5>

                    <div id="correos-container">

                        {{-- CORREOS EXISTENTES --}}
                        @foreach($correos as $c)
                        <div class="row g-2 mb-2 align-items-center correo-row">

                            {{-- CORREO --}}
                            <div class="col-md-6">
                                <input type="email"
                                       name="correo_existente[{{ $c->id }}]"
                                       class="form-control"
                                       value="{{ $c->correo }}"
                                       required>
                            </div>

                            {{-- TIPO --}}
                            <div class="col-md-4">
                                <select name="correo_tipo_existente[{{ $c->id }}]"
                                        class="form-select"
                                        required>
                                    <option value="PRINCIPAL"
                                            @selected($c->descripcion === 'PRINCIPAL')>
                                        Principal
                                    </option>
                                    <option value="SECUNDARIO"
                                            @selected($c->descripcion === 'SECUNDARIO')>
                                        Secundario
                                    </option>
                                </select>
                            </div>

                            {{-- ELIMINAR --}}
                            <div class="col-md-2">
                                <button type="button"
                                        class="btn btn-outline-danger w-100 btn-delete-correo"
                                        data-id="{{ $c->id }}">
                                    ✖
                                </button>
                            </div>

                        </div>
                        @endforeach

                    </div>

                    {{-- AGREGAR NUEVO --}}
                    <button type="button"
                            class="btn btn-outline-secondary btn-sm mt-2"
                            onclick="agregarCorreo()">
                        Agregar correo
                    </button>

                    {{-- ================= TELÉFONOS ================= --}}
                    <br><br><br>
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
                                <button type="button"
                                        class="btn btn-outline-danger w-100 btn-delete-telefono"
                                        data-id="{{ $t->id }}">
                                    ✖
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>


                    <button type="button"
                            class="btn btn-outline-secondary btn-sm mt-2"
                            onclick="agregarTelefono()">
                        Agregar teléfono
                    </button>

                    {{-- ================= CAMBIAR CONTRASEÑA (SOLO PERFIL PROPIO) ================= --}}
                    @if(session('id_persona') == $usuario->id_persona)

                    <br><br><br>
                    <h5 class="fw-semibold">Cambiar contraseña (opcional)</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Contraseña actual</label>
                            <input type="password"
                                   name="password_actual"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nueva contraseña</label>
                            <input type="password"
                                   name="password_nueva"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Confirmar nueva contraseña</label>
                            <input type="password"
                                   name="password_confirmar"
                                   class="form-control">
                        </div>
                    </div>

                    <small class="text-muted d-block mt-2">
                        Mínimo 8 caracteres, una mayúscula, un número y un símbolo.
                    </small>

                    @endif                    





                    {{-- ================= BOTONES ================= --}}
                    <br><br>
                    <hr class="my-4">
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-primary">Guardar cambios</button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-light-primary">Cancelar</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>


<!-- ===============================
     MODAL CONFIRMAR ELIMINACIÓN
================================ -->
<div class="modal fade"
     id="confirmDeleteModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    Confirmar eliminación
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p id="confirmDeleteMessage" class="mb-0">
                    ¿Está seguro que desea eliminar este elemento?
                </p>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button"
                        class="btn btn-danger"
                        id="confirmDeleteBtn">
                    Eliminar
                </button>
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
<script src="{{ asset('libs/simplebar/simplebar.min.js') }}"></script>
<!-- FabKin App -->
<script src="{{ asset('js/app.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ==========================================================
       SEGURIDAD: Bootstrap debe existir
    ========================================================== */
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap no está cargado');
        return;
    }

    /* ==========================================================
       VARIABLES GLOBALES
    ========================================================== */
    let deleteType  = null;   // 'correo' | 'telefono'
    let deleteId    = null;
    let deleteRow   = null;
    let deleteModal = null;

    const csrfToken = '{{ csrf_token() }}';

    /* ==========================================================
       TELÉFONOS – HOVER PARA EDITAR
    ========================================================== */
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

    /* ==========================================================
       AGREGAR CORREO
    ========================================================== */
    window.agregarCorreo = function () {
        document.getElementById('correos-container').insertAdjacentHTML('beforeend', `
            <div class="row g-2 mb-2 correo-row">
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
    };

    /* ==========================================================
       AGREGAR TELÉFONO
    ========================================================== */
    const opcionesTelefono = `
        @foreach($tiposTelefono as $tt)
            <option value="{{ $tt->id }}">{{ $tt->nombre }}</option>
        @endforeach
    `;

    window.agregarTelefono = function () {
        document.getElementById('telefonos-container').insertAdjacentHTML('beforeend', `
            <div class="row g-2 mb-2 telefono-row">
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
    };

    /* ==========================================================
       CLICK GLOBAL – ABRIR MODAL
    ========================================================== */
    document.addEventListener('click', function (e) {

        const modalEl = document.getElementById('confirmDeleteModal');
        if (!modalEl) return;

        // Inicializar el modal UNA sola vez
        if (!deleteModal) {
            deleteModal = new bootstrap.Modal(modalEl);
        }

        /* ---------- CORREO ---------- */
        const btnCorreo = e.target.closest('.btn-delete-correo');
        if (btnCorreo) {
            deleteType = 'correo';
            deleteId   = btnCorreo.dataset.id;
            deleteRow  = btnCorreo.closest('.correo-row');

            document.getElementById('confirmDeleteMessage').textContent =
                '¿Está seguro que desea eliminar este correo electrónico?';

            deleteModal.show();
            return;
        }

        /* ---------- TELÉFONO ---------- */
        const btnTel = e.target.closest('.btn-delete-telefono');
        if (btnTel) {
            deleteType = 'telefono';
            deleteId   = btnTel.dataset.id;
            deleteRow  = btnTel.closest('.telefono-row');

            document.getElementById('confirmDeleteMessage').textContent =
                '¿Está seguro que desea eliminar este teléfono?';

            deleteModal.show();
        }
    });

    /* ==========================================================
       CONFIRMAR ELIMINACIÓN
    ========================================================== */
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {

            if (!deleteType || !deleteId) return;

            let body = '_method=PUT';
            if (deleteType === 'correo')   body += `&del_correo=${deleteId}`;
            if (deleteType === 'telefono') body += `&del_tel=${deleteId}`;

            fetch(`{{ route('usuarios.update', $usuario->id_persona) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body
            }).then(response => {
                if (response.ok && deleteRow) {
                    deleteRow.remove();
                    deleteModal.hide();
                }
            });
        });
    }

});
</script>


@endsection
