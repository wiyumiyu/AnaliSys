


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

        {{-- ================= ENCABEZADO ================= --}}
<div class="d-flex justify-content-between align-items-center mb-3">

    <h4 class="fw-bold mb-0">
        Solicitud 99999
    </h4>

    <div class="d-flex gap-2">

        {{-- Botón Exportar --}}
        <div class="btn-group">
            <button type="button"
                    class="btn btn-success dropdown-toggle"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                Exportar
            </button>

            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="#">
                        Exportar PDF
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">
                        Exportar Excel
                    </a>
                </li>
            </ul>
        </div>

        {{-- Botón volver --}}
                <a href="{{ url()->previous() }}" class="btn btn-primary">
                ← Volver
            </a>
    </div>

</div>

        {{-- ================= DATOS SOLICITUD (COMPACTO ARRIBA) ================= --}}
        <div class="card mb-3">

            <div class="card-body py-3 small">

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-3">

                    <div>
                        <span class="text-muted">Cliente</span><br>
                        <span class="fw-semibold">
                            Inversiones Agropecuarias Los Guanacastes S.A.
                        </span>
                    </div>

                    <div>
                        <span class="text-muted">Subcliente</span><br>
                        <span class="fw-semibold">Finca La Virgen</span>
                    </div>

                    <div>
                        <span class="text-muted">Responsable</span><br>
                        <span class="fw-semibold">Katherine Arias</span>
                    </div>

                    <div>
                        <span class="text-muted">Fecha recepción</span><br>
                        <span class="fw-semibold">20/02/2026</span>
                    </div>

                    <div>
                        <span class="text-muted">N° muestras</span><br>
                        <span class="fw-semibold">2</span>
                    </div>

                    <div>
                        <span class="text-muted">Correos</span><br>
                        correo1@cliente.com
                    </div>

                    <div>
                        <span class="text-muted">Teléfonos</span><br>
                        2297-2242
                    </div>

                    <div>
                        <span class="text-muted">Ubicación</span><br>
                        Guanacaste, Bagaces
                    </div>

                    <div>
                        <span class="text-muted">Cultivo</span><br>
                        Caña de azúcar
                    </div>

                    <div>
                        <span class="text-muted">Análisis</span><br>
                        TEXT, DA, HG
                    </div>

                </div>

            </div>
        </div>

        {{-- ================= TABLA ANALISIS (100% ANCHO) ================= --}}
        <div class="card">

            <div class="card-header bg-light">
                <h6 class="mb-0 fw-semibold">
                    Análisis Físico de Suelo
                </h6>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-0">

                        <thead class="table-light text-center small">
                            <tr>
                                <th style="width: 28%;">Etiqueta</th>
                                <th style="width: 12%;">ID Lab</th>

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

                        <tbody class="text-center small">

                            <tr>
                                <td class="text-start">
                                    Muestra de suelo sector norte finca experimental 1
                                </td>
                                <td>AO-26-00074</td>

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
                                <td class="text-start">
                                    Muestra profunda parcela oeste lote 4
                                </td>
                                <td>AO-26-00075</td>

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