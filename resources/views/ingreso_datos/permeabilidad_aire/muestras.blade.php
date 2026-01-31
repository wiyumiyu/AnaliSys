@extends('partials.layouts.master')

@section('title', 'Muestras del lote')

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
                        Lote {{ $lote }} – Muestras
                    </h5>

                    {{-- ACCIONES --}}
                    <div class="d-flex gap-3 align-items-center">

                        {{-- BUSCAR --}}
                        <div class="form-icon">
                            <input type="text"
                                   class="form-control form-control-icon"
                                   placeholder="Buscar ...">
                            <i class="ri-search-2-line text-muted"></i>
                        </div>

                        {{-- VOLVER --}}
                        <a href="{{ route('pa.index') }}"
                           class="btn btn-primary">
                            ← Volver
                        </a>

                    </div>
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
                            <th>Material</th>
                            <th>Método</th>
                            <th>Tipo muestra</th>
                            <th>Longitud</th>
                            <th>Diámetro</th>
                            <th>Área</th>
                            <th>Volumen</th>
                            <th>Temp aire</th>
                            <th>Prom</th>
                            <th>Desv</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($muestras as $m)
                        <tr>

                            {{-- ID LAB --}}
                            <td>
                                <h6 class="mb-0">
                                    <a href="{{ route('pa.muestra.edit', $m->id) }}">
                                        {{ $m->idlab }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    Rep {{ $m->rep }}
                                </small>
                            </td>

                            <td>{{ $m->rep }}</td>
                            <td>{{ $m->material }}</td>
                            <td>{{ $m->metodo }}</td>
                            <td>{{ $m->tipomuestra }}</td>
                            <td>{{ $m->longitud }}</td>
                            <td>{{ $m->diametrointerno }}</td>
                            <td>{{ $m->areatransversal }}</td>
                            <td>{{ $m->volumen }}</td>
                            <td>{{ $m->temperaturaaire }}</td>
                            <td>{{ $m->promedio }}</td>
                            <td>{{ $m->desvEst }}</td>

                            {{-- ACCIONES --}}
                            <td class="text-end">
                                <div class="hstack gap-2 fs-15 justify-content-end">

                                    {{-- EDITAR --}}
                                    <a href="{{ route('pa.muestra.edit', $m->id) }}"
                                       class="btn bg-primary-subtle text-primary btn-sm">
                                        <i class="ri-edit-line"></i>
                                    </a>

                                    {{-- ANULAR --}}
                                    <button class="btn bg-warning-subtle text-warning btn-sm">
                                        <i class="ri-close-circle-line"></i>
                                    </button>

                                    {{-- ELIMINAR --}}
                                    <button class="btn bg-danger-subtle text-danger btn-sm">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>

                                    {{-- TIMELINE --}}
                                    <button class="btn bg-info-subtle text-info btn-sm">
                                        <i class="ri-time-line"></i>
                                    </button>

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="13"
                                class="text-center text-muted py-4">
                                No hay muestras registradas para este lote.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

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

@endsection
