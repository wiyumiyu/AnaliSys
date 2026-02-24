@extends('partials.layouts.master')

@section('title', 'Textura - Archivos')

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
                <div class="d-flex gap-4 justify-content-between align-items-center">

                    {{-- TÍTULO --}}
                    <h5 class="mb-0 fw-semibold">
                        Controles de Textura
                    </h5>

                    {{-- ACCIONES --}}
                    {{-- ACCIONES --}}
                    <div class="d-flex align-items-center gap-3 flex-nowrap">

                        {{-- AÑO --}}
                        <select class="form-select w-auto"
                                onchange="location = '?periodo=' + this.value">
                            @for($i = date('Y'); $i >= date('Y')-10; $i--)
                            <option value="{{ $i }}" @selected($periodo==$i)>
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

                        {{-- AGREGAR --}}

                        <button class="btn btn-primary mb-0"
                                data-bs-toggle="modal"
                                data-bs-target="#modalNuevoControl">
                            <i class="ri-add-large-line fs-5 me-1"></i>
                            Nuevo
                        </button>
                    </div>
                </div>
            </div>


            <div class="card-body">

                {{-- TABLE --}}
                <table id="default_datatable"
                       class="table table-nowrap align-middle">

                    <thead>
                        <tr>
                            <th>Consecutivo</th>
                            <th>Fecha</th>
                            <th>Analista</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($consecutivosControles as $l)
                        <tr>

                            {{-- ARCHIVO --}}
                            <td>
                                <h6 class="mb-0">
                                    <a href=""
                                       class="text-reset fw-semibold text-decoration-none fs-6">
                                        {{ $l->consecutivo }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    ID {{ $l->id}}
                                </small>
                            </td>

                            {{-- FECHA --}}
                            <td>{{ $l->fecha }}</td>

                            {{-- ANALISTA --}}
                            <td>{{ $l->responsable }}</td>

                            {{-- ACCIONES --}}
                            <td class="text-end">
                                <div class="hstack gap-2 fs-15 justify-content-end">

                                    <a href="{{ route('controles.textura.graficos', $l->id) }}"
                                       class="btn bg-primary-subtle text-primary btn-sm">
                                        <i class="ri-eye-line"></i>
                                    </a>

                                    <button type="button" class="btn bg-danger-subtle text-danger btn-sm"
                                            onclick="confirmarEliminacion({{ $l->id }}, {{ $l->consecutivo }})">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>

                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>


                </table>

            </div>
        </div>

    </div>
</div>

{{-- =========================================================
   MODAL NUEVO CONTROL
   ========================================================= --}}
<div class="modal fade"
     id="modalNuevoControl"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-semibold">
                    Nuevo control de textura
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <form method="POST"
                  action="{{ route('controlTextura.store') }}">
                @csrf

                <div class="modal-body">

                    <div class="d-flex flex-wrap gap-4 justify-content-center">

                        {{-- AÑO --}}
                        <div>
                            <label class="form-label fw-semibold">Año</label>
                            <select name="anio" class="form-select">
                                @for($i = date('Y'); $i >= date('Y')-10; $i--)
                                <option value="{{ $i }}" @selected($periodo==$i)>
                                    {{ $i }}
                                </option>
                                @endfor
                            </select>
                        </div>

                        {{-- CONSECUTIVO --}}
                        <div>
                            <label class="form-label fw-semibold">Consecutivo</label>
                            <input type="number"
                                   name="consecutivo"
                                   class="form-control"
                                   value="{{ old('consecutivo', $siguienteConsecutivo->siguiente_consecutivo ?? 1) }}"
                                   min="1"
                                   required>
                        </div>

                        {{-- ARCHIVOS --}}
                        {{-- ARCHIVOS --}}
                        <div>
                            <label class="form-label fw-semibold">Archivos</label>

                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        data-bs-auto-close="outside">
                                    Seleccionar archivos
                                </button>

                                <div class="dropdown-menu p-3"
                                     style="min-width: 260px; max-height: 250px; overflow-y: auto;">

                                    @php
                                    $archivosOld = old('archivos', []);
                                    @endphp
                                    @forelse($archivosDisponibles as $archivo)

                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="archivos[]"
                                               value="{{ $archivo->id }}"
                                               id="archivo{{ $archivo->id }}"
                                               @checked(in_array($archivo->id, $archivosOld))>
                                        <label class="form-check-label"
                                               for="archivo{{ $archivo->id }}">
                                            {{ $archivo->archivo }}
                                        </label>
                                    </div>
                                    @empty
                                    <span class="text-muted small">
                                        No hay archivos disponibles
                                    </span>
                                    @endforelse

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer border-0 justify-content-center">
                    <button type="submit"
                            class="btn btn-primary">
                        Guardar
                    </button>

                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- =========================================================
   MODAL ELIMINAR CONTROL
   ========================================================= --}}


<div class="modal fade"
     id="modalEliminarControl"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-semibold text-danger">
                    Confirmar eliminación
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">

                <p class="mb-3">
                    ¿Está seguro que desea eliminar el control
                    <strong id="controlNumero"></strong>?
                </p>

                <form id="formEliminar"
                      method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="d-flex justify-content-center gap-3">

                        <button type="submit"
                                class="btn btn-danger">
                            Sí, eliminar
                        </button>

                        <button type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">
                            Cancelar
                        </button>

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

<!-- Buscar en tabla -->
<script src="{{ asset('js/table/buscarEnTabla.js') }}"></script>

<script src="{{ asset('libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script>
                                                function confirmarEliminacion(id, consecutivo) {

                                                document.getElementById('controlNumero').textContent = consecutivo;
                                                const form = document.getElementById('formEliminar');
                                                form.action = `/control-textura/${id}`;
                                                const modal = new bootstrap.Modal(
                                                        document.getElementById('modalEliminarControl')
                                                        );
                                                modal.show();
                                                }
</script>
<script>
 document.querySelector('#modalNuevoControl form')
            .addEventListener('submit', function(e) {

            const seleccionados = document.querySelectorAll(
                    '#modalNuevoControl input[name="archivos[]"]:checked'
                    );
            if (seleccionados.length === 0) {
            e.preventDefault();
            alert('Debe seleccionar al menos un archivo.');
            const dropdownBtn = document.querySelector(
                    '#modalNuevoControl [data-bs-toggle="dropdown"]'
                    );
            dropdownBtn.click();
            }

            });


</script>
@endsection