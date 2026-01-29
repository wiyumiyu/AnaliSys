@extends('partials.layouts.master')

@section('title', 'Gestión de Usuarios')

@section('css')
<!-- Datatables CSS (FabKin style) -->
<link rel="stylesheet"
      href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css"/>
<link rel="stylesheet"
      href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css"/>
@endsection

@section('content')


<div class="row">
    <div class="col-lg-12">

        <br>



        {{-- CARD --}}
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap gap-4 justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Usuarios del sistema</h5>
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <div class="form-icon">
                            <input type="text" class="form-control form-control-icon" id="firstNameLayout4"
                                   placeholder="Buscar ..." required>
                            <i class="ri-search-2-line text-muted"></i>
                        </div>
                        <a href="{{ route('usuarios.create') }}"
                           class="btn btn-primary">
                            <i class="ri-user-add-line me-1"></i>
                            Nuevo usuario
                        </a>

                    </div>
                </div>
            </div>            


            <div class="card-body">

                {{-- TABLE --}}
                <table id="default_datatable" class="table table-nowrap align-middle">

                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Cédula</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $u)
                        <tr>

                            {{-- USUARIO --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="/images/avatar/dummy-avatar.jpg"
                                         class="avatar-sm rounded-circle"
                                         alt="avatar">
                                    <div>
                                        <h6 class="mb-0">
                                            {{ $u->nombre_completo }}
                                        </h6>
                                        <small class="text-muted">
                                            ID {{ $u->id_persona }}
                                        </small>
                                    </div>
                                </div>
                            </td>

                            {{-- CÉDULA --}}
                            <td>{{ $u->cedula }}</td>

                            {{-- ROL --}}
                            <td>
                                <span class="badge bg-info-subtle text-info">
                                    {{ $u->rol ?? 'Sin rol' }}
                                </span>
                            </td>

                            {{-- ESTADO --}}
                            <td>
                                @if($u->id_estado == 1)
                                <span class="badge bg-success-subtle text-success">
                                    Activo
                                </span>
                                @else
                                <span class="badge bg-danger-subtle text-danger">
                                    Inactivo
                                </span>
                                @endif
                            </td>

                            {{-- ACCIONES --}}
                            <td class="text-end">
                                <div class="hstack gap-2 fs-15 justify-content-end">

                                    {{-- EDITAR --}}
                                    <a href="{{ route('usuarios.edit', $u->id_persona) }}"
                                       class="btn bg-primary-subtle text-primary btn-sm">
                                        <i class="ri-edit-line"></i>
                                    </a>

                                    @if($u->id_estado == 1)
                                    {{-- INACTIVAR --}}
                                    <button class="btn bg-danger-subtle text-danger btn-sm"
                                            onclick="confirmarCambioEstado({{ $u->id_persona }}, 'inactivar')">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                    @else
                                    {{-- ACTIVAR --}}
                                    <button class="btn bg-success-subtle text-success btn-sm"
                                            onclick="confirmarCambioEstado({{ $u->id_persona }}, 'activar')">
                                        <i class="ri-refresh-line"></i>
                                    </button>
                                    @endif

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No hay usuarios registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>

    </div>
</div>

{{-- MODAL CONFIRMAR CAMBIO DE ESTADO --}}
<div class="modal fade" id="confirmUserStatusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="modalBody"></div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <form method="POST" id="userStatusForm">
                    @csrf
                    @method('PATCH')

                    <button class="btn" id="modalConfirmBtn"></button>
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

<!-- buscar elemento de la tabla -->
<script src="{{ asset('js/table/buscarEnTabla.js') }}"></script>


<script>
    let statusModal;
    let statusForm;

    document.addEventListener('DOMContentLoaded', function () {
        statusModal = new bootstrap.Modal(
            document.getElementById('confirmUserStatusModal')
        );

        statusForm = document.getElementById('userStatusForm');
    });

    function confirmarCambioEstado(id, accion) {

        const title = document.getElementById('modalTitle');
        const body = document.getElementById('modalBody');
        const btn = document.getElementById('modalConfirmBtn');

        statusForm.action = `/usuarios/${id}/estado`;

        if (accion === 'inactivar') {
            title.textContent = 'Inactivar usuario';
            title.className = 'modal-title text-danger';

            body.innerHTML = `
                ¿Está seguro que desea inactivar este usuario?
                <br>
                <small class="text-muted">
                    El usuario no podrá acceder al sistema.
                </small>
            `;

            btn.textContent = 'Inactivar';
            btn.className = 'btn btn-danger';

        } else {
            title.textContent = 'Activar usuario';
            title.className = 'modal-title text-success';

            body.innerHTML = `
                ¿Desea activar nuevamente este usuario?
                <br>
                <small class="text-muted">
                    El usuario recuperará el acceso al sistema.
                </small>
            `;

            btn.textContent = 'Activar';
            btn.className = 'btn btn-success';
        }

        statusModal.show();
    }
</script>
