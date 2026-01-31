@extends('partials.layouts.master')

@section('title', 'Bitácora del Sistema')

@section('css')
<link rel="stylesheet"
      href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css"/>
<link rel="stylesheet"
      href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css"/>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">

        <br>

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
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header border-0">
                <h5 class="modal-title">Detalle de Bitácora</h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Usuario</strong>
                        <div id="bd-usuario" class="text-muted"></div>
                    </div>

                    <div class="col-md-4">
                        <strong>Tabla</strong>
                        <div id="bd-tabla" class="text-muted"></div>
                    </div>

                    <div class="col-md-4">
                        <strong>Acción</strong>
                        <div id="bd-accion" class="text-muted"></div>
                    </div>
                </div>

                <hr>

                <h6 class="fw-semibold">Antes</h6>
                <pre class="bg-light p-3 rounded small"
                     id="bd-antes">—</pre>

                <h6 class="fw-semibold mt-4">Después</h6>
                <pre class="bg-light p-3 rounded small"
                     id="bd-despues">—</pre>

            </div>

            <div class="modal-footer border-0">
                <button class="btn btn-light"
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


