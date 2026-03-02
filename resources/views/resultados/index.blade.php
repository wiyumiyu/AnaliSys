@extends('partials.layouts.master')

@section('title', 'Resultados')

@section('css')
<link rel="stylesheet"
      href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css"/>
<link rel="stylesheet"
      href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css"/>
<link rel="stylesheet"
      href="{{ asset('libs/prismjs/themes/prism-coy.min.css') }}">

<style>
    /* Scroll interno del modal */
    #modalNuevoResultado .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }

    /* Badge interno del tipo */
    .tipo-badge {
        font-size: 0.70rem;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 6px;
        white-space: nowrap;
    }

    /* Modo claro */
    [data-bs-theme="light"] .tipo-badge {
        background-color: #ffffff;
    }

    /* Modo oscuro */
    [data-bs-theme="dark"] .tipo-badge {
        background-color: #1e1e1e;
    }
</style>
@endsection
@section('content')

<div class="row">
    <div class="col-lg-12">
        <br>

        <div class="card">

            {{-- HEADER --}}
            <div class="card-header">
                <div class="d-flex gap-4 justify-content-between align-items-center">

                    <h5 class="mb-0 fw-semibold">
                        Resultados
                    </h5>

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

                        {{-- NUEVO --}}
                        <button class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalNuevoResultado">
                            <i class="ri-add-large-line fs-5 me-1"></i>
                            Nuevo
                        </button>
                    </div>
                </div>
            </div>

            {{-- BODY --}}
            <div class="card-body">

                <table id="default_datatable"
                       class="table table-nowrap align-middle">

                    <thead>
                        <tr>
                            <th>Consecutivo</th>
                            <th>Fecha</th>
                            <th>Archivos</th>
                            <th>Analista</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resultados as $r)
                        <tr>

                            <td>
                                <a href="{{ route('resultados.show', $r->id) }}"
                                   class="fw-semibold text-primary text-decoration-none">
                                    {{ $r->consecutivo }}
                                </a>
                            </td>

                            <td>{{ $r->fecha }}</td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @foreach($r->archivos as $archivo)
                                    <div class="badge bg-light text-secondary d-flex justify-content-between">
                                        {{ $archivo->archivo }}
                                        <span class="text-muted small">
                                            {{ str_replace('_', ' ', $archivo->tipo) }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>{{ $r->analista }}</td>

                            <td class="text-end">
                                <div class="hstack gap-2 fs-15 justify-content-end">

                                    {{-- Eliminar --}}
                                    <button type="button"
                                            class="btn bg-danger-subtle text-danger btn-sm"
                                            onclick="confirmarEliminacion({{ $r->id }}, '{{ $r->consecutivo }}')">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                No hay resultados registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</div>
<!-- start:: Scrollable Modal -->
<div class="modal fade"
     id="modalNuevoResultado"
     tabindex="-1"
     aria-labelledby="modalNuevoResultadoLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <form method="POST" action="{{ route('resultados.store') }}">
                @csrf

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold"
                        id="modalNuevoResultadoLabel">
                        <i class="ri-file-list-3-line text-primary me-2"></i>
                        Nuevo Resultado
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <!-- BODY (SCROLL INTERNO AQUÍ) -->
                <div class="modal-body">

                    <!-- AÑO + CONSECUTIVO -->
                    <div class="d-flex flex-wrap gap-3">

                        <div>
                            <label class="form-label fw-semibold mb-1">
                                Año
                            </label>
                            <select name="anio"
                                    class="form-select"
                                    style="width:150px;">
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
                                   value="{{ old('consecutivo', $siguienteConsecutivo ?? 1) }}"
                                   min="1"
                                   readonly>
                        </div>

                    </div>

                    <br>

                    <!-- LISTA DUAL -->
                    <div class="row g-4">

                        <!-- DISPONIBLES -->
                        <div class="col-lg-6">
                            <label class="fw-semibold mb-3 d-block">
                                <i class="ri-folder-open-line text-primary me-1"></i>
                                Archivos disponibles
                            </label>
                            <div class="border rounded-3 p-3 h-100">
                                <div class="dual-box d-flex flex-column gap-2"
                                     id="listaDisponibles">

                                    @foreach($archivosDisponibles as $archivo)

                                    @php
                                    $colorMap = [
                                    'TEXTURA' => 0,
                                    'GRANULOMETRIA' => 1,
                                    'DENSIDAD_APARENTE' => 2,
                                    'DENSIDAD_PARTICULAS' => 3,
                                    'HUMEDAD_GRAVIMETRICA' => 4,
                                    'CONDUCTIVIDAD_HIDRAULICA' => 5,
                                    'RETENCION_HUMEDAD' => 6,
                                    'ESTABILIDAD_AGREGADOS' => 7,
                                    'COEFICIENTE_EXTENSIBILIDAD' => 8,
                                    ];

                                    $index = $colorMap[$archivo->tipo] ?? 0;
                                    $hue = fmod(($index * 137.508), 360);

                                    $textColor = "hsl($hue, 85%, 65%)";
                                    $bgColor   = "hsla($hue, 90%, 50%, 0.12)";
                                    $borderColor = "hsl($hue, 85%, 50%)";
                                    @endphp

                                    <span class="badge dual-item w-100 d-flex justify-content-between align-items-center"
                                          data-id="{{ $archivo->id }}"
                                          style="background-color: {{ $bgColor }};
                                          color: {{ $textColor }};
                                          border: 1px solid {{ $borderColor }};
                                          cursor:pointer;">

                                        <span>{{ $archivo->archivo }}</span>

                                        <span class="tipo-badge"
                                              style="color: {{ $textColor }};">
                                            {{ str_replace('_', ' ', $archivo->tipo) }}
                                        </span>

                                        <input type="checkbox"
                                               name="archivos[]"
                                               value="{{ $archivo->tipo }}|{{ $archivo->id }}"
                                               class="d-none">

                                    </span>

                                    @endforeach

                                </div>
                            </div>
                        </div>

                        <!-- SELECCIONADOS -->
                        <div class="col-lg-6">
                            <label class="fw-semibold mb-3 d-block">
                                <i class="ri-check-line text-success me-1"></i>
                                Archivos seleccionados
                            </label>

                            <div class="border rounded-3 p-3 h-100">
                                <div class="dual-box d-flex flex-column gap-2"
                                     id="listaSeleccionados">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
<br>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit"
                            class="btn btn-primary">
                        Guardar
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
<!-- end:: Scrollable Modal -->
{{-- =========================================================
   MODAL ELIMINAR RESULTADO
   ========================================================= --}}
<div class="modal fade"
     id="modalEliminarResultado"
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
                    ¿Está seguro que desea eliminar el resultado
                    <strong id="resultadoNumero"></strong>?
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
<script src="{{ asset('libs/prismjs/prism.js') }}"></script>
<script  src="{{ asset('libs/sortablejs/Sortable.min.js') }}"></script>

<!--App js-->
<script type="module" src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
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

                                                document.getElementById('resultadoNumero').textContent = consecutivo;
                                                const form = document.getElementById('formEliminar');
                                                form.action = `/resultados/${id}`;
                                                const modal = new bootstrap.Modal(
                                                        document.getElementById('modalEliminarResultado')
                                                        );
                                                modal.show();
                                                }


                                                document.addEventListener("click", function(e) {

                                                const item = e.target.closest(".dual-item");
                                                if (!item) return;
                                                const disponible = document.getElementById("listaDisponibles");
                                                const seleccionado = document.getElementById("listaSeleccionados");
                                                const checkbox = item.querySelector('input[type="checkbox"]');
                                                if (item.parentElement.id === "listaDisponibles") {
                                                seleccionado.appendChild(item);
                                                if (checkbox) checkbox.checked = true;
                                                } else {
                                                disponible.appendChild(item);
                                                if (checkbox) checkbox.checked = false;
                                                }

                                                });

</script>

@endsection