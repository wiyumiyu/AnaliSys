


@extends('partials.layouts.master')

@section('title', 'Vista solicitud')

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
                        Reporte de ensayo
                    </h5>
                </div>
            </div>

            <div class="card-body">

                <div class="container-fluid mb-4">

                    <div class="row align-items-center mb-3">

                        <div class="col-md-3">
                            <!--<img src="{{ asset('images/ucr_logo.png') }}" style="height:80px">-->
                        </div>

                        <div class="col-md-6 text-center">

                            <h3 class="fw-bold mt-2">
                                REPORTE DE ENSAYO
                            </h3>

                        </div>

                        <div class="col-md-3 text-end">
                            <!--<img src="{{ asset('images/cia_logo.png') }}" style="height:80px">-->
                        </div>

                    </div>

                    <table class="table table-bordered table-sm">

                        <tr>
                            <td width="200"><strong>N° DE REPORTE</strong></td>
                            <td><strong>{{ $encabezado->numero }}</strong></td>
                        </tr>

                        <tr>
                            <td>USUARIO</td>
                            <td>{{ $encabezado->cliente }}</td>
                        </tr>

                        <tr>
                            <td>CORREO</td>
                            <td>{{ $encabezado->correo }}</td>
                        </tr>

                    </table>

                    <table class="table table-bordered table-sm">

                        <tr>
                            <td>UBICACIÓN</td>
                            <td>{{ $encabezado->provincia }}, {{ $encabezado->canton }}</td>

                            <td>TELÉFONO</td>
                            <td>{{ $encabezado->telefono }}</td>
                        </tr>

                        <tr>
                            <td><strong>CULTIVO</strong></td>
                            <td>{{ $encabezado->cultivo }}</td>

                            <td><strong>FECHA DE RECEPCIÓN</strong></td>
                            <td>{{ \Carbon\Carbon::parse($encabezado->fecha)->format('d/m/Y') }}</td>
                        </tr>

                        <tr>
                            <td><strong>ANÁLISIS</strong></td>
                            <td>ANÁLISIS FÍSICO DE SUELOS</td>

                            <td><strong>EMISIÓN DE REPORTE</strong></td>
                            <td>{{ now()->format('d/m/Y') }}</td>
                        </tr>

                        <tr>
                            <td><strong>N° DE MUESTRAS</strong></td>
                            <td>{{ $encabezado->numero_muestras }}</td>

                            <td>RESPONSABLE</td>
                            <td>{{ $encabezado->responsable }}</td>
                        </tr>

                    </table>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle mb-0 text-center">

                            <thead class="small">

                                <tr>
                                    <th colspan="14">ANÁLISIS FÍSICO DE SUELOS</th>
                                </tr>

                                <tr>
                                    <th rowspan="2" style="width:23%">ID USUARIO</th>
                                    <th rowspan="2" style="width:7%">IDLAB</th>

                                    <th colspan="4">TEXTURA</th>

                                    <th rowspan="2">DA</th>
                                    <th rowspan="2">DP</th>
                                    <th rowspan="2">HG</th>
                                    <th rowspan="2">Ret.H</th>
                                    <th rowspan="2">C_RetH</th>
                                    <th rowspan="2">Frac.A</th>
                                    <th rowspan="2">Est.Agr</th>
                                    <th rowspan="2">COEL</th>
                                    <th rowspan="2">Con.Por</th>
                                </tr>

                                <tr>
                                    <th>Arena</th>
                                    <th>Limo</th>
                                    <th>Arcilla</th>
                                    <th>Clase textural</th>
                                </tr>

                                <tr style="font-size:11px">
                                    <th></th>
                                    <th></th>

                                    <th colspan="3">%</th>

                                    <th></th>
                                    <th>%</th>
                                    <th>%</th>
                                    <th>%</th>
                                    <th>%</th>
                                    <th>%</th>
                                    <th>%</th>
                                    <th>%</th>
                                    <th>%</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach($datos as $row)

                                <tr>

                                    <td class="text-start align-top bg-light">
                                        {{ $row->etiqueta }}
                                    </td>

                                    <td>{{ $row->idlab }}</td>

                                    @php
                                    $t = $texturas[$row->idlab] ?? null;
                                    @endphp

                                    <td>{{ $t ? number_format($t['arena'],1) : '-' }}</td>
                                    <td>{{ $t ? number_format($t['limo'],1) : '-' }}</td>
                                    <td>{{ $t ? number_format($t['arcilla'],1) : '-' }}</td>

                                    @php
                                    $d = $densidades[$row->idlab] ?? null;
                                    @endphp

                                    <td>{{ $d ? number_format($d, 3) : '-' }}</td>
                                    
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>

                                </tr>

                                @endforeach

                            </tbody>

                        </table>
                    </div>

                </div>

            </div> {{-- card-body --}}
        </div> {{-- card --}}
    </div>
</div>

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