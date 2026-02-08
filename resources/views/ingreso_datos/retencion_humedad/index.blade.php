@extends('partials.layouts.master')

@section('title', 'Retención de Humedad - Archivos')

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
                        Retención de Humedad
                    </h5>

                    {{-- ACCIONES --}}
                    <div class="d-flex gap-3 align-items-center">

                        {{-- AÑO --}}
                        <select class="form-select w-auto"
                                onchange="location='?anio='+this.value">
                            @for($i = date('Y'); $i >= date('Y')-10; $i--)
                                <option value="{{ $i }}" @selected($anio==$i)>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>

                        {{-- BUSCAR --}}
                        <div class="form-icon">
                            <input type="text"
                                   class="form-control form-control-icon"
                                   placeholder="Buscar ...">
                            <i class="ri-search-2-line text-muted"></i>
                        </div>

                        {{-- IMPORTAR --}}
                        <form class="d-flex align-items-center gap-3 m-0"
                              action="{{ route('retencion_humedad.importar') }}"
                              enctype="multipart/form-data"
                              method="POST">
                            @csrf

                            <input type="file"
                                   name="archivo"
                                   accept=".xlsx,.xls"
                                   class="form-control"
                                   style="max-width: 320px"
                                   required>

                            <button type="submit"
                                    class="btn btn-primary mb-0">
                                <i class="ri-upload-2-line me-1"></i>
                                Importar
                            </button>
                        </form>

                    </div>
                </div>
            </div>

            <div class="card-body">

                {{-- TABLE --}}
                <table id="default_datatable"
                       class="table table-nowrap align-middle">

                    <thead>
                        <tr>
                            <th>Archivo</th>
                            <th>Fecha</th>
                            <th>Analista</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($archivos as $l)
                        <tr>

                            {{-- ARCHIVO --}}
                            <td>
                                <h6 class="mb-0">
                                    <a href="{{ route('retencion_humedad.muestras', $l->id_archivo) }}">
                                        {{ $l->archivo }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    ID {{ $l->id_archivo }}
                                </small>
                            </td>

                            {{-- FECHA --}}
                            <td>{{ $l->fecha }}</td>

                            {{-- ANALISTA --}}
                            <td>{{ $l->analista }}</td>

                            {{-- ACCIONES --}}
                            <td class="text-end">
                                <div class="hstack gap-2 fs-15 justify-content-end">

                                    {{-- VER --}}
                                    <a href="{{ route('retencion_humedad.muestras', $l->id_archivo) }}"
                                       class="btn bg-primary-subtle text-primary btn-sm">
                                        <i class="ri-eye-line"></i>
                                    </a>

                                    {{-- ELIMINAR --}}
                                    <button type="button"
                                            class="btn bg-danger-subtle text-danger btn-sm"
                                            onclick="confirmarEliminarArchivo(
                                                {{ $l->id_archivo }}, '{{ $l->archivo }}'
                                            )">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="4"
                                class="text-center text-muted py-4">
                                No hay archivos registrados para este año.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>

    </div>
</div>

{{-- ================= MODAL CONFIRMACIÓN ================= --}}
<div class="modal fade" id="confirmArchivoModal" tabindex="-1">
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
                    @method('DELETE')
                    <button class="btn btn-danger">
                        Eliminar
                    </button>
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

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script src="{{ asset('js/table/datatable.init.js') }}"></script>
<script src="{{ asset('js/table/buscarEnTabla.js') }}"></script>

<script src="{{ asset('libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script>
let archivoModal;

document.addEventListener('DOMContentLoaded', function () {
    archivoModal = new bootstrap.Modal(
        document.getElementById('confirmArchivoModal')
    );
});

function confirmarEliminarArchivo(id, nombre) {

    document.getElementById('modalTitle').textContent = 'Eliminar archivo';
    document.getElementById('modalTitle').className =
        'modal-title text-danger fw-semibold';

    document.getElementById('modalBody').innerHTML = `
        ¿Está seguro que desea eliminar el archivo
        <strong>${nombre}</strong>?
        <br>
        <small class="text-muted">
            Se eliminarán todas las muestras y resultados asociados.
        </small>
    `;

    document.getElementById('modalForm').action =
        `/ingreso-datos/retencion-humedad/${id}`;

    archivoModal.show();
}
</script>

@endsection
