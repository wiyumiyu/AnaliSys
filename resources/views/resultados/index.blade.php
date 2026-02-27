@extends('partials.layouts.master')

@section('title', 'Resultados')

@section('css')
<link rel="stylesheet"
      href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css"/>
<link rel="stylesheet"
      href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css"/>

<link rel="stylesheet"  href="{{ asset('libs/prismjs/themes/prism-coy.min.css') }}">

<style>
    /* =====================================================
       LEYENDA LIMPIA (SIN FONDO)
    ===================================================== */
    /* Etiqueta gris del tipo de análisis */
.file-tag {
    background: var(--bs-tertiary-bg);
    font-size: 0.75rem;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 500;
    color: inherit;
}
.dual-item .file-tag {
    color: inherit;
}

    /* =====================================================
       COLORES DE CADA TIPO (PUNTO + ITEMS)
    ===================================================== */

    /* TEXTURA */
    .textura {
        background: #F4E8E1;
        color: #8B5E3C;
        border: 1px solid #E5CFC2;
    }
    .legend-item.textura .legend-dot {
        background: #8B5E3C;
    }

    /* DENSIDAD APARENTE */
    .dens-ap {
        background: #E4ECFA;
        color: #1E3A8A;
        border: 1px solid #C7D6F5;
    }
    .legend-item.dens-ap .legend-dot {
        background: #1E3A8A;
    }

    /* DENSIDAD PARTICULAS */
    .dens-par {
        background: #E6F0FF;
        color: #3B82F6;
        border: 1px solid #C9DEFF;
    }
    .legend-item.dens-par .legend-dot {
        background: #3B82F6;
    }

    /* HUMEDAD */
    .hum-grav {
        background: #E6F7EC;
        color: #16A34A;
        border: 1px solid #C7EBD6;
    }
    .legend-item.hum-grav .legend-dot {
        background: #16A34A;
    }

    /* CONDUCTIVIDAD */
    .cond-hid {
        background: #E0F7FA;
        color: #0891B2;
        border: 1px solid #B9EBF1;
    }
    .legend-item.cond-hid .legend-dot {
        background: #0891B2;
    }

    /* RETENCION */
    .ret-hum {
        background: #EDF7E3;
        color: #65A30D;
        border: 1px solid #D4EAB9;
    }
    .legend-item.ret-hum .legend-dot {
        background: #65A30D;
    }

    /* CURVATURA */
    .curv-ret {
        background: #F1ECFF;
        color: #7C3AED;
        border: 1px solid #D9CCFF;
    }
    .legend-item.curv-ret .legend-dot {
        background: #7C3AED;
    }

    /* GRANULOMETRIA */
    .granul {
        background: #FFF1E8;
        color: #EA580C;
        border: 1px solid #FFD7C2;
    }
    .legend-item.granul .legend-dot {
        background: #EA580C;
    }

    /* ESTABILIDAD */
    .estab {
        background: #FFECEC;
        color: #DC2626;
        border: 1px solid #FFC9C9;
    }
    .legend-item.estab .legend-dot {
        background: #DC2626;
    }

    /* COEF EXTENSIBILIDAD */
    .coef-ext {
        background: #FFEAF4;
        color: #DB2777;
        border: 1px solid #FFC9E2;
    }
    .legend-item.coef-ext .legend-dot {
        background: #DB2777;
    }

    /* PERMEABILIDAD */
    .perm-air {
        background: #F3F4F6;
        color: #6B7280;
        border: 1px solid #E5E7EB;
    }
    .legend-item.perm-air .legend-dot {
        background: #6B7280;
    }


    /* =====================================================
       LISTAS DUALES (ARCHIVOS)
    ===================================================== */
.dual-box {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: 8px;
    padding: 15px;
    min-height: 220px;
}

    .dual-item {
        padding: 10px 14px;
        border-radius: 8px;
        margin-bottom: 8px;
        font-size: 0.85rem;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.18s ease;

        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .dual-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .dual-item:hover {
    background: var(--bs-tertiary-bg);
}
    .modal-header {
        border-bottom: none !important;
    }

    .modal-footer {
        border-top: none !important;
    }
    
    /* ===============================
   DARK MODE – mejorar contraste
================================ */

[data-bs-theme="dark"] .textura {
    background: rgba(139, 94, 60, 0.25);
}

[data-bs-theme="dark"] .hum-grav {
    background: rgba(22, 163, 74, 0.25);
}

[data-bs-theme="dark"] .granul {
    background: rgba(234, 88, 12, 0.25);
}

[data-bs-theme="dark"] .dual-item {
    border: 1px solid rgba(255,255,255,0.08);
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
                            <th>Analista</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($resultados as $r)
                        <tr>

                            <td>
                                <h6 class="mb-0">
                                    {{ $r->consecutivo }}
                                </h6>
                                <small class="text-muted">
                                    ID {{ $r->id }}
                                </small>
                            </td>

                            <td>{{ $r->fecha }}</td>

                            <td>{{ $r->total_archivos }}</td>

                            <td class="text-end">
                                <div class="hstack gap-2 fs-15 justify-content-end">

                                    {{-- Ver --}}
                                    <a href="{{ route('resultados.show', $r->id) }}"
                                       class="btn bg-primary-subtle text-primary btn-sm">
                                        <i class="ri-eye-line"></i>
                                    </a>

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

<div class="modal fade"
     id="modalNuevoResultado"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">
                    <i class="ri-file-list-3-line text-primary me-2"></i>
                    Nuevo Resultado
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('resultados.store') }}">
                @csrf

                <div class="modal-body">

                    {{-- AÑO + CONSECUTIVO --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Año</label>
                            <input type="number" name="anio" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Consecutivo</label>
                            <input type="number" name="consecutivo" class="form-control" required>
                        </div>
                    </div>




                    {{-- LISTA DUAL --}}
                    <div class="row g-4">

                        {{-- DISPONIBLES --}}
                        <div class="col-md-6">
                            <label class="fw-semibold mb-2">
                                <i class="ri-folder-open-line text-primary me-1"></i>
                                Archivos disponibles
                            </label>

                            <div class="dual-box" id="listaDisponibles">
                                {{-- Ejemplo --}}
                                <div class="dual-item textura">
                                    <span>textura_2026.xlsx</span>
                                    <span class="file-tag">Textura</span>
                                </div>
                                <div class="dual-item hum-grav">
                                    <span>humedad_sem1.xlsx</span>
                                    <span class="file-tag">Humedad Gravimétrica</span>
                                </div>

                                <div class="dual-item granul">
                                    <span>granulometria_A.xlsx</span>
                                    <span class="file-tag">Granulometría</span>
                                </div>
                            </div>
                        </div>

                        {{-- SELECCIONADOS --}}
                        <div class="col-md-6">
                            <label class="fw-semibold mb-2">
                                <i class="ri-check-line text-success me-1"></i>
                                Archivos seleccionados
                            </label>

                            <div class="dual-box" id="listaSeleccionados">
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
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

@endsection

@section('js')
<script src="{{ asset('libs/prismjs/prism.js') }}"></script>
<script  src="{{ asset('libs/sortablejs/Sortable.min.js') }}"></script>

<!--App js-->
<script type="module" src="{{ asset('js/app.js') }}"></script>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

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
                                                if (e.target.classList.contains("dual-item")) {
                                                const item = e.target;
                                                const disponible = document.getElementById("listaDisponibles");
                                                const seleccionado = document.getElementById("listaSeleccionados");
                                                if (item.parentElement.id === "listaDisponibles") {
                                                seleccionado.appendChild(item);
                                                } else {
                                                disponible.appendChild(item);
                                                }
                                                }
                                                });
</script>

@endsection