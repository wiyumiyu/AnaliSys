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

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0 fw-semibold">
                        Reportes de Clientes
                    </h5>

                    <div class="d-flex align-items-center gap-3">

                        {{-- FILTRO IMPRESA --}}
                        <select class="form-select w-auto"
                                onchange="location = '?impresa=' + this.value + '&periodo={{ $periodo }}'">
                            <option value="0" @selected($impresa == 0)>No Impresas</option>
                            <option value="1" @selected($impresa == 1)>Impresas</option>
                        </select>

                        {{-- FILTRO AÑO --}}
                        <select class="form-select w-auto"
                                onchange="location = '?periodo=' + this.value + '&impresa={{ $impresa }}'">
                            @for($i = date('Y'); $i >= date('Y')-10; $i--)
                                <option value="{{ $i }}" @selected($periodo==$i)>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>

                    </div>
                </div>
            </div>

            <div class="card-body">

                <table id="default_datatable"
                       class="table table-nowrap align-middle">

                    <thead>
                        <tr>
                            <th>Solicitud</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($solicitudes as $l)
                        <tr>

                            <td>
                                <a href="{{ route('reportes_clientes.show', $l->id_solicitud) }}"
                                   class="fw-semibold text-primary text-decoration-none">
                                    {{ $l->numero }}
                                </a>
                            </td>

                            <td>{{ $l->nombre }}</td>

                            <td>{{ \Carbon\Carbon::parse($l->fecha)->format('d/m/Y') }}</td>

                            <td>
                                @if($l->estado == 1)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-secondary">Inactiva</span>
                                @endif
                            </td>

                        </tr>
                        @endforeach
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