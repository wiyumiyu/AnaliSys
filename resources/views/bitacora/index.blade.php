@extends('partials.layouts.master')

@section('title', 'Bitácora del Sistema')

@section('css')
<link rel="stylesheet"
      href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css"/>
<link rel="stylesheet"
      href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css"/>
@endsection

@section('content')
<br> 
<div id="layout-wrapper">
    <div class="row">
        <div class="col-12 mb-1">
            <div class="accordion accordion-icon accordion-primary accordion-border-box"
                 id="demo_accordion_main_03">

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#demo_accordion_item_31"
                                aria-expanded="true"
                                aria-controls="demo_accordion_item_31">
                            <i class="bi bi-funnel-fill me-2"></i> Filtrar
                        </button>
                    </h2>

                    <div id="demo_accordion_item_31"
                         class="accordion-collapse collapse show"
                         data-bs-parent="#demo_accordion_main_03">

                        <div class="accordion-body py-5">
                            <div class="row g-4">

                                <!-- Usuario -->
                                <div class="col-md-4 col-xl">
                                    <input type="text"
                                           class="form-control form-control-icon"
                                           id="filter-usuario"
                                           placeholder="Usuario">
                                </div>

                                <!-- Tabla -->
                                <div class="col-md-4 col-xl">
                                    <input type="text"
                                           class="form-control form-control-icon"
                                           id="filter-tabla"
                                           placeholder="Tabla">
                                </div>

                                <!-- Acción -->
                                <div class="col-md-4 col-xl">
                                    <select id="filter-accion"
                                            class="form-select">
                                        <option value="">Acción</option>
                                        <option value="CREATE">CREATE</option>
                                        <option value="UPDATE">UPDATE</option>
                                        <option value="DELETE">DELETE</option>
                                    </select>
                                </div>

                                <!-- IP -->
                                <div class="col-md-4 col-xl">
                                    <input type="text"
                                           class="form-control form-control-icon"
                                           id="filter-ip"
                                           placeholder="IP">
                                </div>

                                <!-- Fecha -->
                                <div class="col-md-4 col-xl">
                                    <input type="date"
                                           class="form-control"
                                           id="filter-fecha">
                                </div>

                                <div class="col-xl d-flex justify-content-end align-items-center gap-2">

                                    <button class="btn btn-light-danger"
                                            type="button"
                                            id="btn-limpiar">
                                        Quitar filtros
                                    </button>

                                </div>


                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">Registros de Bitácora</h5>
            </div>

            <div class="card-body">

                <table id="default_datatable" class="table table-nowrap align-middle">

                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Tabla</th>
                            <th>Acción</th>
                            <th>IP</th>
                            <th class="text-end">Detalle</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($bitacoras as $b)
                    <tr>

                        <td>{{ $b->fecha }}</td>

                        <td>
                            <strong>{{ $b->usuario }}</strong>
                        </td>

                        <td>
                            <span class="badge bg-secondary-subtle text-secondary">
                                {{ $b->tabla }}
                            </span>
                        </td>

                        <td>
                            @if($b->accion === 'CREATE')
                                <span class="badge bg-success-subtle text-success">CREATE</span>
                            @elseif($b->accion === 'UPDATE')
                                <span class="badge bg-warning-subtle text-warning">UPDATE</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">DELETE</span>
                            @endif
                        </td>

                        <td>{{ $b->ip }}</td>

                        <td class="text-end">
                            @if($b->tiene_antes || $b->tiene_despues)
                                <button
                                    class="btn bg-primary-subtle text-primary btn-sm"
                                    title="Ver detalle"
                                    onclick="verDetalle({{ $b->id }})">
                                    <i class="ri-eye-line"></i>
                                </button>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No hay registros en la bitácora.
                        </td>
                    </tr>
                    @endforelse
                    </tbody>


                </table>

            </div>
        </div>

    </div>
</div>

{{-- ================= MODAL DETALLE BITÁCORA ================= --}}
<div class="modal fade"
     id="detalleBitacoraModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="modalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"
         role="document">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">
                    <i class="ri-eye-line text-primary me-2"></i>
                    Detalle de Bitácora
                </h5>

                <button type="button"
                        class="btn-close icon-btn-sm"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    <i class="ri-close-large-line fw-semibold"></i>
                </button>
            </div>

            {{-- BODY --}}
            <div class="modal-body">

                {{-- INFO PRINCIPAL --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="fw-semibold mb-1">
                            <i class="ri-user-3-line me-1 text-primary"></i>
                            Usuario
                        </div>
                        <div id="bd-usuario" class="text-muted"></div>
                    </div>

                    <div class="col-md-4">
                        <div class="fw-semibold mb-1">
                            <i class="ri-database-2-line me-1 text-secondary"></i>
                            Tabla
                        </div>
                        <div id="bd-tabla" class="text-muted"></div>
                    </div>

                    <div class="col-md-4">
                        <div class="fw-semibold mb-1">
                            <i class="ri-flashlight-line me-1 text-warning"></i>
                            Acción
                        </div>
                        <div id="bd-accion" class="text-muted"></div>
                    </div>
                </div>

                <hr class="my-3">

                {{-- ANTES --}}
                <h6 class="fw-semibold mb-2">
                    <i class="ri-arrow-left-right-line text-danger me-1"></i>
                    Estado anterior
                </h6>
                <pre class="bg-light p-3 rounded small mb-4"
                     id="bd-antes">—</pre>

                {{-- DESPUÉS --}}
                <h6 class="fw-semibold mb-2">
                    <i class="ri-arrow-right-line text-success me-1"></i>
                    Estado posterior
                </h6>
                <pre class="bg-light p-3 rounded small"
                     id="bd-despues">—</pre>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Cerrar
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

<script>
function verDetalle(id) {

    fetch(`/bitacora/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar detalle');
            }
            return response.json();
        })
        .then(b => {

            document.getElementById('bd-usuario').textContent =
                b.usuario ?? '—';

            document.getElementById('bd-tabla').textContent =
                b.tabla ?? '—';

            document.getElementById('bd-accion').textContent =
                b.accion ?? '—';

            document.getElementById('bd-antes').textContent =
                b.datos_antes
                    ? JSON.stringify(JSON.parse(b.datos_antes), null, 2)
                    : 'Sin datos';

            document.getElementById('bd-despues').textContent =
                b.datos_despues
                    ? JSON.stringify(JSON.parse(b.datos_despues), null, 2)
                    : 'Sin datos';

            const modal = new bootstrap.Modal(
                document.getElementById('detalleBitacoraModal')
            );
            modal.show();
        })
        .catch(error => {
            console.error(error);
            alert('No se pudo cargar el detalle de la bitácora');
        });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const table = $('#default_datatable').DataTable({
        order: [[0, 'desc']] // 👈 Fecha DESC (más reciente primero)
    });


    function aplicarFiltros() {
        table.column(1).search(
            document.getElementById('filter-usuario').value
        );

        table.column(2).search(
            document.getElementById('filter-tabla').value
        );

        table.column(3).search(
            document.getElementById('filter-accion').value
        );

        table.column(4).search(
            document.getElementById('filter-ip').value
        );

        table.column(0).search(
            document.getElementById('filter-fecha').value
        );

        table.draw();
    }

    // Inputs texto → mientras escribe
    document.querySelectorAll(
        '#filter-usuario, #filter-tabla, #filter-ip'
    ).forEach(input => {
        input.addEventListener('input', aplicarFiltros);
    });

    // Fecha
    document.getElementById('filter-fecha')
        .addEventListener('change', aplicarFiltros);

    // Select acción
    document.getElementById('filter-accion')
        .addEventListener('change', aplicarFiltros);

    // Botón limpiar (se mantiene)
    document.getElementById('btn-limpiar')
        .addEventListener('click', function () {

            document.querySelectorAll(
                '#filter-usuario, #filter-tabla, #filter-accion, #filter-ip, #filter-fecha'
            ).forEach(el => el.value = '');

            table.columns().search('');
            table.draw();
        });

});
</script>





