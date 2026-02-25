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

                                @forelse($comentarios as $comentario)

                                <li class="card border-0 box mb-3">
                                    <span></span>

                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center gap-3">

                                            <img 
                                                src="https://ui-avatars.com/api/?name={{ urlencode($comentario->nombre_usuario) }}&background=198754&color=fff"
                                                class="rounded-pill"
                                                width="40"
                                                height="40"
                                                alt="Avatar">

                                            <div>
                                                <h6 class="mb-1">
                                                    {{ $comentario->nombre_usuario }}
                                                </h6>

                                                <p class="fs-12 text-muted mb-0">
                                                    {{ \Carbon\Carbon::parse($comentario->fecha)->format('d/m/Y') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="text-muted">
                                            {{ \Carbon\Carbon::parse($comentario->fecha)->format('h:i A') }}
                                        </div>
                                    </div>

                                    @if($comentario->aprobado)
                                    <p class="text-success fw-semibold mb-0">
                                        ✔ Aprobado
                                    </p>
                                    @else
                                    <p class="text-muted mb-0">
                                        {{ $comentario->comentario }}
                                    </p>
                                    @endif

                                </li>

                                @empty
                                <li class="text-center text-muted">
                                    No hay comentarios aún.
                                </li>
                                @endforelse

                            </ul>
                        </div>
                    </section>
                </div>


                {{-- ================= ACCIONES ================= --}}
                <div class="mb-5 pt-4">

                    <form method="POST" action="{{ route('controles.textura.comentar', $id ?? request()->route('id')) }}">
                        @csrf

                        <textarea name="comentario"
                                  class="form-control mb-3"
                                  rows="3"
                                  placeholder="Escribir comentario..."></textarea>

                        <div class="d-flex gap-2">

                            {{-- PUBLICAR --}}
                            <button type="submit"
                                    name="accion"
                                    value="comentar"
                                    class="btn btn-primary btn-sm">
                                Publicar
                            </button>

                            {{-- APROBAR --}}
                            <button type="submit"
                                    name="accion"
                                    value="aprobar"
                                    class="btn btn-success btn-sm">
                                Aprobar
                            </button>

                        </div>

                    </form>

                </div>

                <br>


                {{-- ================= REP ================= --}}

                @php
                function convertirARangos($numeros) {
                $numeros = collect($numeros)
                ->map(fn($n) => (int)$n)
                ->unique()
                ->sort()
                ->values();

                if ($numeros->isEmpty()) return '';

                $rangos = [];
                $inicio = $numeros[0];
                $prev = $inicio;

                foreach ($numeros->slice(1) as $n) {
                if ($n == $prev + 1) {
                $prev = $n;
                } else {
                $rangos[] = $inicio == $prev ? $inicio : "$inicio - $prev";
                $inicio = $prev = $n;
                }
                }

                $rangos[] = $inicio == $prev ? $inicio : "$inicio - $prev";

                return implode(' , ', $rangos);
                }
                @endphp

                {{-- ================= REP ================= --}}
                <div class="mb-5 pt-4 border-top">
                    <h6 class="fw-semibold mb-4">Muestras asociadas al blanco</h6>
                    <br>

                    @forelse($porArchivo as $index => $items)

                    @php
                    $nombreArchivo = $items->first()->archivo;

                    $numeros = $items->pluck('idlab')
                    ->map(fn($n) => (int)$n)
                    ->unique()
                    ->sort()
                    ->values();

                    $rangos = [];
                    $inicio = $numeros[0] ?? null;
                    $prev = $inicio;

                    foreach ($numeros->slice(1) as $n) {
                    if ($n == $prev + 1) {
                    $prev = $n;
                    } else {
                    $rangos[] = $inicio == $prev ? $inicio : "$inicio - $prev";
                    $inicio = $prev = $n;
                    }
                    }

                    if ($inicio !== null) {
                    $rangos[] = $inicio == $prev ? $inicio : "$inicio - $prev";
                    }

                    $texto = implode(' , ', $rangos);

                    // 🎨 Colores dinámicos por archivo
                    $colores = ['primary', 'success', 'warning', 'danger', 'info'];
                    $color = $colores[$loop->index % count($colores)];
                    @endphp

                    <div class="mb-3">

                        <div class="px-3 py-2 bg-{{ $color }}-subtle rounded text-{{ $color }} border border-{{ $color }}-subtle">
                            {{ $loop->iteration }}: {{ $texto }}
                        </div>
                    </div>

                    @empty
                    <div class="text-muted">
                        No hay datos.
                    </div>
                    @endforelse
                </div>

                <br>


                {{-- ================= ARCHIVOS ================= --}}
                <div class="pt-4 border-top">
                    <br>

                    <div class="d-flex justify-content-between align-items-center border-bottom border-dashed pb-3 mb-3">

                        <div>
                            <h3 class="mb-1">{{ $archivos->count() }}</h3>
                            <p class="text-muted mb-0">Archivos involucrados</p>
                        </div>

                        <div class="h-48px w-48px d-flex justify-content-center align-items-center rounded bg-info-subtle text-info fs-3">
                            <i class="bi bi-folder2-open"></i>
                        </div>

                    </div>

                    <div class="d-flex flex-column gap-2">
                        @forelse($archivos as $archivo)
                        <div class="d-flex justify-content-between p-2 border rounded">
                            <span class="fw-medium">
                                {{ $archivo->archivo ?? 'Archivo sin nombre' }}
                            </span>

                            <span class="badge bg-light text-dark">
                                {{ $loop->iteration }}
                            </span>
                        </div>
                        @empty
                        <div class="alert alert-light border">
                            No hay archivos de textura asociados a este control.
                        </div>
                        @endforelse
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

const datos = @json($datosGraficos);

const limites = {
    R: {min: -0.3, max: 0.6, inferior: 0, superior: 0.5},
    Temp: {min: 15, max: 30, inferior: 18, superior: 26},
    Tiempo: {min: 0, max: 120, inferior: 30, superior: 90}
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
            toolbar: {show: false},
            zoom: {enabled: false}
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
            axisBorder: {show: true},
            axisTicks: {show: false}
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