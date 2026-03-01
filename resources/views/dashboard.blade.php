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

<!-- ================= REPORTES POR ANÁLISIS ================= -->
<div class="row mb-4">
    <div class="col-12 col-xl-12">

        <div class="card card-h-100 shadow-sm">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">
                    Reportes generados por Análisis
                </h6>
            </div>

            <div class="card-body">
                <div id="analisisChart" style="min-height: 350px;"></div>
            </div>

        </div>

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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


<script>
document.addEventListener("DOMContentLoaded", function () {

    var options = {
        series: [{
            name: 'Reportes',
            data: @json(array_values($analisisData))
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: true
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 6,
                columnWidth: '45%',
                distributed: true,
                dataLabels: {
                    position: 'top'
                }
            }
        },
        colors: [
            '#3b82f6','#10b981','#0dcaf0','#ffc107',
            '#dc3545','#6f42c1','#6c757d','#0d6efd'
        ],
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val;
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                fontWeight: 600,
                colors: ["#304758"]
            }
        },
        xaxis: {
            categories: @json(array_keys($analisisData)),
            position: 'bottom',
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Cantidad de Reportes'
            },
            min: 0,
            forceNiceScale: true
        },
        grid: {
            borderColor: '#e0e0e0'
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " reportes";
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#analisisChart"), options);
    chart.render();

});
</script>

@endsection