@extends('partials.layouts.master')

@section('title', 'Dashboard')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <br>

        <!-- ================= MARCO PRINCIPAL ================= -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-semibold">
                            Dashboard {{ $anio }}
                        </h5>
                        <small class="text-muted">
                            Bienvenido {{ session('nombre') }} {{ session('apellido1') }}
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <!-- ================= KPI PRINCIPALES ================= -->
                <div class="row g-3 mb-4">

                    <div class="col-xl-3 col-md-6">
                        <div class="card border-start border-primary border-4 shadow-sm h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">Reportes generados</p>
                                    <h3 class="fw-bold text-primary mb-0">{{ $reportesAnio }}</h3>
                                </div>
                                <div class="fs-1 text-primary opacity-50">
                                    <i class="ri-file-chart-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="card border-start border-danger border-4 shadow-sm h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">Solicitudes pendientes</p>
                                    <h3 class="fw-bold text-danger mb-0">{{ $pendientes }}</h3>
                                </div>
                                <div class="fs-1 text-danger opacity-50">
                                    <i class="ri-time-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="card border-start border-success border-4 shadow-sm h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">Controles generados</p>
                                    <h3 class="fw-bold text-success mb-0">{{ $controlesAnio }}</h3>
                                </div>
                                <div class="fs-1 text-success opacity-50">
                                    <i class="ri-shield-check-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="card border-start border-dark border-4 shadow-sm h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">Resultados generados</p>
                                    <h3 class="fw-bold mb-0">{{ $resultadosAnio }}</h3>
                                </div>
                                <div class="fs-1 text-dark opacity-50">
                                    <i class="ri-bar-chart-box-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <hr class="my-4">

                <!-- ================= ARCHIVOS POR MÓDULO ================= -->
                <h6 class="fw-semibold mb-3">Archivos generados por módulo</h6>

                <div class="row g-4 text-center mb-4">

                    <div class="col-xl-2 col-md-4 col-6">
                        <h4 class="fw-bold text-primary">{{ $textura }}</h4>
                        <small class="text-muted">Textura</small>
                    </div>

                    <div class="col-xl-2 col-md-4 col-6">
                        <h4 class="fw-bold text-success">{{ $densidadAparente }}</h4>
                        <small class="text-muted">Densidad Aparente</small>
                    </div>

                    <div class="col-xl-2 col-md-4 col-6">
                        <h4 class="fw-bold text-info">{{ $densidadParticulas }}</h4>
                        <small class="text-muted">Densidad Partículas</small>
                    </div>

                    <div class="col-xl-2 col-md-4 col-6">
                        <h4 class="fw-bold text-warning">{{ $humedad }}</h4>
                        <small class="text-muted">Humedad Gravimétrica</small>
                    </div>

                    <div class="col-xl-2 col-md-4 col-6">
                        <h4 class="fw-bold text-danger">{{ $retencion }}</h4>
                        <small class="text-muted">Retención</small>
                    </div>

                    <div class="col-xl-2 col-md-4 col-6">
                        <h4 class="fw-bold text-dark">{{ $granulometria }}</h4>
                        <small class="text-muted">Granulometría</small>
                    </div>

                    <div class="col-xl-2 col-md-4 col-6">
                        <h4 class="fw-bold text-secondary">{{ $estabilidad }}</h4>
                        <small class="text-muted">Estabilidad</small>
                    </div>

                    <div class="col-xl-2 col-md-4 col-6">
                        <h4 class="fw-bold text-primary">{{ $coel }}</h4>
                        <small class="text-muted">COEL</small>
                    </div>

                </div>

                <hr class="my-4">

                <!-- ================= EFICIENCIA ================= -->
                <h6 class="fw-semibold mb-3">
                    Tiempo entre solicitud y generación de reporte
                </h6>

                <div class="row text-center g-4">

                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block">Promedio</small>
                            <h3 class="fw-bold text-primary">{{ $promedio }} días</h3>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block">Más rápido</small>
                            <h3 class="fw-bold text-success">{{ $masRapido }} días</h3>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block">Más lento</small>
                            <h3 class="fw-bold text-danger">{{ $masLento }} días</h3>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!-- ================= FIN MARCO ================= -->

    </div>
</div>

</div><!--End container-fluid-->
</main><!--End app-wrapper-->
@endsection


@section('js')
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
@endsection