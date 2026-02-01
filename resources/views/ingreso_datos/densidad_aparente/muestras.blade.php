@extends('partials.layouts.master')

@section('title', 'Muestras del archivo - Densidad aparente')

@section('css')
<!-- Datatables CSS -->
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
                <div class="d-flex gap-4 justify-content-between align-items-center">

                    {{-- TÍTULO --}}
                    <h5 class="mb-0 fw-semibold">
                        Archivo {{ $archivo }} – Muestras
                    </h5>

                    {{-- VOLVER --}}
                    <a href="{{ route('densidad_aparente.index') }}"
                       class="btn btn-primary">
                        ← Volver
                    </a>

                </div>
            </div>

            <div class="card-body">

                {{-- TABLE --}}
                <table id="default_datatable"
                       class="table table-nowrap align-middle">

                    <thead>
                        <tr>
                            <th>ID Lab</th>
                            <th>Rep</th>
                            <th>Altura Cilindro</th>
                            <th>Diametro Cilindro</th>
                            <th>Peso Seco</th>
                            <th>Peso cilindro</th>
                            <th>Temperatura secado</th>
                            <th>Tiempo Secado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($muestras as $m)
                            @php
                                $filaInactiva = $m->estado == 0 ? 'opacity-40' : '';
                            @endphp

                            <tr>

                                {{-- ID LAB --}}
                                <td class="{{ $filaInactiva }}">
                                    <a href="{{ route('densidad_aparente.muestra.edit', $m->id_muestra) }}"
                                       class="fw-semibold text-reset text-decoration-none">
                                        {{ $m->idlab }}
                                    </a>
                                </td>

                                <td class="{{ $filaInactiva }}">{{ $m->rep }}</td>
                                <td class="{{ $filaInactiva }}">{{ $m->altura }}</td>
                                <td class="{{ $filaInactiva }}">{{ $m->diametro }}</td>
                                <td class="{{ $filaInactiva }}">{{ $m->peso_cilindro_suelo }}</td>
                                <td class="{{ $filaInactiva }}">{{ $m->peso_cilindro }}</td>
                                <td class="{{ $filaInactiva }}">{{ $m->temperatura }}</td>
                                <td class="{{ $filaInactiva }}">{{ $m->secado }}</td>

                                {{-- ACCIONES --}}
                                <td class="text-end">
                                    <div class="hstack gap-2 fs-15 justify-content-end">

                                        {{-- ANULAR / ACTIVAR --}}
                                        <button type="button"
                                                class="btn {{ $m->estado == 1
                                                    ? 'bg-warning-subtle text-warning'
                                                    : 'bg-success-subtle text-success' }} btn-sm"
                                                onclick="confirmarEstadoMuestra({{ $m->id_muestra }}, {{ $m->estado }})">
                                            <i class="{{ $m->estado == 1
                                                ? 'ri-close-circle-line'
                                                : 'ri-refresh-line' }}"></i>
                                        </button>

                                        {{-- ELIMINAR --}}
                                        <button type="button"
                                                class="btn bg-danger-subtle text-danger btn-sm"
                                                onclick="confirmarEliminarMuestra({{ $m->id_muestra }})">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9"
                                    class="text-center text-muted py-4">
                                    No hay muestras registradas para este archivo.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>

    </div>
</div>

{{-- MODAL CONFIRMACIÓN --}}
<div class="modal fade" id="confirmMuestraModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-semibold" id="modalTitle"></h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="modalBody"></div>

            <div class="modal-footer border-0">
                <button class="btn btn-light"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>

                <form method="POST" id="modalForm">
                    @csrf
                    <input type="hidden" name="_method" id="modalMethod">
                    <button class="btn" id="modalConfirmBtn"></button>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')

<!-- Bootstrap -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script src="{{ asset('js/table/datatable.init.js') }}"></script>
<script src="{{ asset('js/table/buscarEnTabla.js') }}"></script>

<script src="{{ asset('libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script>
let muestraModal;
let modalForm;
let modalTitle;
let modalBody;
let modalBtn;
let modalMethod;

document.addEventListener('DOMContentLoaded', function () {
    muestraModal = new bootstrap.Modal(
        document.getElementById('confirmMuestraModal')
    );

    modalForm   = document.getElementById('modalForm');
    modalTitle  = document.getElementById('modalTitle');
    modalBody   = document.getElementById('modalBody');
    modalBtn    = document.getElementById('modalConfirmBtn');
    modalMethod = document.getElementById('modalMethod');
});

/* ===============================
 * ANULAR / ACTIVAR
 * =============================== */
function confirmarEstadoMuestra(id, estado) {

    modalForm.action = `/ingreso-datos/densidad-aparente/muestra/${id}/estado`;
    modalMethod.value = 'PATCH';

    if (estado === 1) {
        modalTitle.textContent = 'Anular muestra';
        modalTitle.className = 'modal-title text-warning fw-semibold';

        modalBody.innerHTML = `
            ¿Está seguro que desea <strong>anular</strong> esta muestra?
            <br>
            <small class="text-muted">
                La muestra seguirá visible, pero no se considerará activa.
            </small>
        `;

        modalBtn.textContent = 'Anular';
        modalBtn.className = 'btn btn-warning';

    } else {
        modalTitle.textContent = 'Reactivar muestra';
        modalTitle.className = 'modal-title text-success fw-semibold';

        modalBody.innerHTML = `
            ¿Desea <strong>reactivar</strong> esta muestra?
        `;

        modalBtn.textContent = 'Reactivar';
        modalBtn.className = 'btn btn-success';
    }

    muestraModal.show();
}

/* ===============================
 * ELIMINAR
 * =============================== */
function confirmarEliminarMuestra(id) {

    modalForm.action = `/ingreso-datos/densidad-aparente/muestra/${id}`;
    modalMethod.value = 'DELETE';

    modalTitle.textContent = 'Eliminar muestra';
    modalTitle.className = 'modal-title text-danger fw-semibold';

    modalBody.innerHTML = `
        Esta acción eliminará la muestra y <strong>todos sus resultados</strong>.
        <br>
        <small class="text-muted">
            Una vez eliminado no se puede deshacer.
        </small>
    `;

    modalBtn.textContent = 'Eliminar';
    modalBtn.className = 'btn btn-danger';

    muestraModal.show();
}
</script>

@endsection