@extends('layouts.master')

@section('title', 'Gestión de Usuarios')

@section('content')

<div class="container-fluid page-inner">

    {{-- TITLE + BUTTON --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Gestión de Usuarios</h1>

        <a href="{{ route('usuarios.create') }}" class="btn btn-primary px-4">
            + Nuevo usuario
        </a>
    </div>

    {{-- CARD --}}
    <div class="card shadow-sm">

        <div class="card-header pb-2">
            <h5 class="card-title mb-1 fw-bold fs-5">
                Listado de usuarios del sistema
            </h5>
            <div class="text-muted fs-13">
                Usuarios autorizados a ingresar y operar en la plataforma.
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">

                    <thead>
                        <tr class="border-bottom">
                            <th class="text-muted fw-bold">Nombre</th>
                            <th class="text-muted fw-bold">Cédula</th>
                            <th class="text-muted fw-bold">Rol</th>
                            <th class="text-muted fw-bold">Estado</th>
                            <th class="text-muted fw-bold text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($users as $u)
                        <tr class="border-bottom">

                            <td>{{ $u->nombre_completo }}</td>

                            <td class="fw-semibold text-primary">
                                {{ $u->cedula }}
                            </td>

                            <td>{{ $u->rol ?? 'Sin rol' }}</td>

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

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">

                                    {{-- EDITAR --}}
                                    <a href="{{ route('usuarios.edit', $u->id_persona) }}"
                                       class="btn btn-action btn-action-edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- ELIMINAR --}}
                                    <button class="btn btn-action btn-action-delete"
                                            onclick="confirmarEliminacionUsuario({{ $u->id_persona }})">
                                        <i class="bi bi-trash"></i>
                                    </button>

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

{{-- MODAL CONFIRMAR ELIMINACIÓN --}}
<div class="modal fade" id="confirmDeleteUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-danger">Eliminar usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                ¿Está seguro que desea eliminar este usuario?
                <br>
                <small class="text-muted">
                    El usuario quedará inactivo.
                </small>
            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <form method="POST" id="deleteUserForm">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">
                        Eliminar
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let deleteForm = document.getElementById('deleteUserForm');

    function confirmarEliminacionUsuario(id) {
        deleteForm.action = `/usuarios/${id}`;
        new bootstrap.Modal(
            document.getElementById('confirmDeleteUserModal')
        ).show();
    }
</script>
@endsection
