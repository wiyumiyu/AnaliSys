@extends('partials.layouts.master')

@section('title', 'Detalle Resultado')

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

            {{-- HEADER FABKIN --}}
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0 fw-semibold">
                        Detalle Resultado {{ $resultado->consecutivo ?? '' }}
                    </h5>

                    <a href="{{ route('resultados.index') }}"
                       class="btn btn-light btn-sm">
                        ← Volver
                    </a>

                </div>
            </div>

            {{-- BODY --}}
            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-sm align-middle mb-0 text-center">

                        {{-- HEADER NEGRO TIPO LAB --}}
                        <thead>

                            {{-- FILA DE AGRUPACIÓN --}}
                            <tr >

                                <th rowspan="2" style="min-width:220px">INFORMACIÓN</th>
                                <th rowspan="2">IDLAB</th>

                                {{-- AGRUPACIÓN DE UNIDADES --}}
                                <th colspan="3">% FÍSICO</th>
                                <th colspan="2">% HUMEDAD</th>
                                <th colspan="2">% ESTRUCTURA</th>
                                <th colspan="2">ÍNDICES</th>

                            </tr>

                            {{-- FILA DE NOMBRES --}}
                            <tr ">

                                <th>TEXT</th>
                                <th>DA</th>
                                <th>DP</th>

                                <th>HG</th>
                                <th>Ret.H</th>

                                <th>C_RetH</th>
                                <th>Frac.A</th>

                                <th>Est.Agr</th>
                                <th>COEL</th>
                                <th>Con.Por</th>

                            </tr>

                        </thead>

                        <tbody>

                            {{-- ===== BLOQUE IDLAB ===== --}}
                            <tr>

                                {{-- COLUMNA IZQUIERDA FIJA --}}
                                {{-- COLUMNA IZQUIERDA FIJA --}}
                                <td rowspan="6"
                                    class="text-start align-top info-cell">

                                    {{-- SOL --}}
                                    <div class="fw-semibold text-success">
                                        SOL.{{ $resultado->consecutivo ?? '97987' }}
                                    </div>

                                    {{-- CLIENTE --}}
                                    <div class="fw-semibold mt-1">
                                        {{ $resultado->cliente ?? 'FLORES Y VERDES DEL IRAZU' }}
                                    </div>

                                    {{-- CANTÓN --}}
                                    <div class="text-muted small">
                                        {{ $resultado->canton ?? 'CARTAGO' }}
                                    </div>

                                    {{-- FECHA --}}
                                    <div class="small mt-1">
                                        <i class="ri-calendar-line text-secondary me-1"></i>
                                        {{ \Carbon\Carbon::parse($resultado->fecha ?? '2026-01-06')->format('d/m/Y') }}
                                    </div>

                                </td>


<td class="text-start align-top">

    {{-- IDLAB --}}
    <div class="fs-5 fw-bold">
        13
    </div>

    {{-- ETIQUETA (real de la imagen) --}}
    <div class="small text-muted">
        F-COOPEAGRI - PZ(1)
    </div>

    {{-- CULTIVO (real de la imagen) --}}
    <div class="small fw-semibold text-uppercase">
        CAFE
    </div>

</td>
                                <td>Fr.Ar</td>
                                <td>1.25</td>
                                <td>2.60</td>
                                <td>18%</td>
                                <td>30%</td>
                                <td>A</td>
                                <td>5%</td>
                                <td>80%</td>
                                <td>0.12</td>
                                <td>0.85</td>
                            </tr>

                            <tr>
<td class="text-start align-top">

    {{-- IDLAB --}}
    <div class="fs-5 fw-bold">
        13
    </div>

    {{-- ETIQUETA (real de la imagen) --}}
    <div class="small text-muted">
        F-COOPEAGRI - PZ(1)
    </div>

    {{-- CULTIVO (real de la imagen) --}}
    <div class="small fw-semibold text-uppercase">
        CAFE
    </div>

</td>
                                <td>Fr.Ar</td>
                                <td>1.35</td>
                                <td>2.58</td>
                                <td>15%</td>
                                <td>25%</td>
                                <td>B</td>
                                <td>8%</td>
                                <td>75%</td>
                                <td>0.10</td>
                                <td>0.78</td>
                            </tr>

                            <tr>
<td class="text-start align-top">

    {{-- IDLAB --}}
    <div class="fs-5 fw-bold">
        13
    </div>

    {{-- ETIQUETA (real de la imagen) --}}
    <div class="small text-muted">
        F-COOPEAGRI - PZ(1)
    </div>

    {{-- CULTIVO (real de la imagen) --}}
    <div class="small fw-semibold text-uppercase">
        CAFE
    </div>

</td>
                                <td>Fr.Ar</td>
                                <td>1.30</td>
                                <td>2.59</td>
                                <td>17%</td>
                                <td>28%</td>
                                <td>A</td>
                                <td>6%</td>
                                <td>78%</td>
                                <td>0.11</td>
                                <td>0.82</td>
                            </tr>

                            {{-- PROMEDIO --}}
                            <tr class="fila-promedio">
                                <td>PROMEDIO</td>
                                <td>Fr.Ar</td>
                                <td>1.30</td>
                                <td>2.59</td>
                                <td>16.6%</td>
                                <td>27.6%</td>
                                <td>A</td>
                                <td>6.3%</td>
                                <td>77.6%</td>
                                <td>0.11</td>
                                <td>0.81</td>
                            </tr>

                            {{-- DESV EST --}}
                            <tr class="fila-metrica">
                                <td>Desv.Est.</td>
                                <td>-</td>
                                <td>0.05</td>
                                <td>0.01</td>
                                <td>1.5%</td>
                                <td>2%</td>
                                <td>-</td>
                                <td>1%</td>
                                <td>2%</td>
                                <td>0.01</td>
                                <td>0.03</td>
                            </tr>

                            {{-- CV --}}
                            <tr class="fila-metrica">
                                <td>CV</td>
                                <td>-</td>
                                <td>3.8%</td>
                                <td>0.3%</td>
                                <td>9%</td>
                                <td>7%</td>
                                <td>-</td>
                                <td>15%</td>
                                <td>2%</td>
                                <td>9%</td>
                                <td>3%</td>
                            </tr>

                        </tbody>

                    </table>

                </div>

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