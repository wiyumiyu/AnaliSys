@extends('partials.layouts.master')

@section('title', 'Textura - Archivos')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <br>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    Controles de textura
                </h5>

                <a href="/controles/textura"
                   class="btn btn-primary">
                    ← Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">

    {{-- ================= COLUMNA IZQUIERDA (SOLO GRÁFICOS) ================= --}}
    <div class="col-xl-9">

        <div class="row">

            @foreach(['R1','R2','R3','R4','Temp1','Temp2','Temp3','Temp4','Tiempo1','Tiempo2','Tiempo3','Tiempo4'] as $grafico)
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-header text-center fw-semibold">
                        {{ $grafico }}
                    </div>
                    <div class="card-body">
                        <div id="{{ $grafico }}" style="height:200px;"></div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>

    </div>
    {{-- ================= COLUMNA DERECHA ================= --}}
    <div class="col-xl-3 ms-auto">

        <div class="card">
            <div class="card-body">

                {{-- ================= HISTORIAL ================= --}}
                <div class="mb-5">

                    <h6 class="fw-semibold mb-4">Historial</h6>
                    <br>
                    <section data-simplebar class="px-4 mx-n4" style="max-height: 260px;">
                        <div class="timeline2">
                            <ul>

                                <li class="card border-0 box mb-3">
                                    <span></span>

                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center gap-3">

                                            <img 
                                                src="https://ui-avatars.com/api/?name=Sistema&background=198754&color=fff"
                                                class="rounded-pill"
                                                width="40"
                                                height="40"
                                                alt="Avatar">

                                            <div>
                                                <h6 class="mb-1">Sistema UCR</h6>
                                                <p class="fs-12 text-muted mb-0">22/02/2026</p>
                                            </div>
                                        </div>

                                        <div class="text-muted">10:15 AM</div>
                                    </div>

                                    <p class="text-muted mb-0">
                                        Control creado correctamente.
                                    </p>
                                </li>

                                <br>

                                <li class="card border-0 box mb-3">
                                    <span></span>

                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center gap-3">

                                            <img 
                                                src="https://ui-avatars.com/api/?name=Sistema&background=198754&color=fff"
                                                class="rounded-pill"
                                                width="40"
                                                height="40"
                                                alt="Avatar">

                                            <div>
                                                <h6 class="mb-1">Sistema UCR</h6>
                                                <p class="fs-12 text-muted mb-0">22/02/2026</p>
                                            </div>
                                        </div>

                                        <div class="text-muted">10:15 AM</div>
                                    </div>

                                    <p class="text-muted mb-0">
                                        Control creado correctamente.
                                    </p>
                                </li>

                            </ul>
                        </div>
                    </section>
                </div>


                {{-- ================= ACCIONES ================= --}}
                <div class="mb-5 pt-4">
                    <textarea class="form-control mb-3"
                              rows="3"
                              placeholder="Escribir comentario..."></textarea>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-sm">
                            Publicar
                        </button>

                        <button class="btn btn-success btn-sm">
                            Aprobar
                        </button>
                    </div>
                </div>

                <br>


                {{-- ================= REP ================= --}}
                <div class="mb-5 pt-4 border-top">
                    <h6 class="fw-semibold mb-4">Repeticiones del blanco</h6>
                    <br>

                    <div class="d-flex flex-wrap gap-3">

                        <div class="px-3 py-2 bg-primary-subtle rounded text-primary border border-primary-subtle">
                            1: 1 - 5 , 10 - 12
                        </div>

                        <div class="px-3 py-2 bg-success-subtle rounded text-success border border-success-subtle">
                            2: 1 - 5 , 10 - 12
                        </div>

                        <div class="px-3 py-2 bg-warning-subtle rounded text-warning border border-warning-subtle">
                            3: 1 - 5 , 10 - 12
                        </div>

                    </div>
                </div>

                <br>


                {{-- ================= ARCHIVOS ================= --}}
                <div class="pt-4 border-top">
                    <br>
                    <div class="d-flex justify-content-between align-items-center border-bottom border-dashed pb-3 mb-3">

                        <div>
                            <h3 class="mb-1">3</h3>
                            <p class="text-muted mb-0">Archivos involucrados</p>
                        </div>

                        <div class="h-48px w-48px d-flex justify-content-center align-items-center rounded bg-info-subtle text-info fs-3">
                            <i class="bi bi-folder2-open"></i>
                        </div>

                    </div>

                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between p-2 border rounded">
                            <span class="fw-medium">Archivo 2024-01</span>
                            <span class="badge bg-light text-dark">1</span>
                        </div>

                        <div class="d-flex justify-content-between p-2 border rounded">
                            <span class="fw-medium">Archivo 2024-02</span>
                            <span class="badge bg-light text-dark">2</span>
                        </div>

                        <div class="d-flex justify-content-between p-2 border rounded">
                            <span class="fw-medium">Archivo 2024-03</span>
                            <span class="badge bg-light text-dark">3</span>
                        </div>
                    </div>

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
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
<script src="{{ asset('libs/apexcharts/apexcharts.min.js') }}"></script>
<script>

const datos = {
    R1: [0.35, 0.42, 0.42, 0.42, 0.42, 0.42, 0.42],
    R2: [0.38, 0.41],
    R3: [0.33, 0.39],
    R4: [0.36, 0.40],

    Temp1: [21, 22],
    Temp2: [22, 23],
    Temp3: [20, 21],
    Temp4: [21, 22],

    Tiempo1: [45, 50],
    Tiempo2: [48, 52],
    Tiempo3: [40, 95], // este se pone rojo
    Tiempo4: [47, 53],
};

const limites = {
    R: { min: -0.3, max: 0.6, inferior: 0, superior: 0.5 },
    Temp: { min: 15, max: 30, inferior: 18, superior: 26 },
    Tiempo: { min: 0, max: 120, inferior: 30, superior: 90 }
};

function crearGrafico(id, valores, config) {

    const chart = new ApexCharts(document.querySelector("#" + id), {

        series: [{
            name: "Valor",
            data: valores
        }],

        chart: {
            type: 'scatter',
            height: 220,
            toolbar: { show: false },
            zoom: { enabled: false }
        },

        markers: {
            size: 5,
            colors: valores.map(v =>
                (v > config.superior || v < config.inferior)
                    ? '#e74c3c'
                    : '#27ae60'
            ),
            strokeWidth: 0
        },

        xaxis: {
            categories: valores.map((_, i) => i + 1),
            axisBorder: { show: true },
            axisTicks: { show: false }
        },

        yaxis: {
            min: config.min,
            max: config.max,
            tickAmount: 4
        },

        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 3,
            padding: {
                left: 30  
            }
        },

        annotations: {
            yaxis: [
                {
                    y: config.superior,
                    borderColor: '#f39c12'
                },
                {
                    y: config.inferior,
                    borderColor: '#f39c12'
                }
            ]
        },

        tooltip: {
            enabled: true
        }

    });

    chart.render();
}

Object.keys(datos).forEach(key => {

    if (key.startsWith('R')) {
        crearGrafico(key, datos[key], limites.R);
    }

    if (key.startsWith('Temp')) {
        crearGrafico(key, datos[key], limites.Temp);
    }

    if (key.startsWith('Tiempo')) {
        crearGrafico(key, datos[key], limites.Tiempo);
    }

});

</script>

@endsection