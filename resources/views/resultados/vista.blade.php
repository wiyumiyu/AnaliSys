@extends('partials.layouts.master')

@section('title', 'Detalle Resultado')

@section('content')

{{-- HEADER --}}
<div class="row">
    <div class="col-lg-12">
        <br>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-semibold">
                    Detalle Resultado
                </h5>

                <a href="{{ route('resultados.index') }}" class="btn btn-primary">
                    ← Regresar
                </a>

            </div>
        </div>

    </div>
</div>

<div class="row mb-4">

    <div class="col-lg-12">

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="row">

                    {{-- ================= IZQUIERDA ================= --}}
                    <div class="col-xl-6">

                        {{-- ================= HISTORIAL ================= --}}
                        <div class="mb-5">

                            <h6 class="fw-semibold mb-4">Historial</h6>
                            <br>

                            <section data-simplebar class="px-4 mx-n4" style="max-height:260px;">

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


                        {{-- ACCIONES --}}
                        <div>

                            <h6 class="fw-semibold mb-3">Acciones</h6>

                            <form method="POST" action="{{ route('resultados.comentar', request()->route('id')) }}">
                                @csrf

                                <textarea name="comentario"
                                          class="form-control mb-3"
                                          rows="3"
                                          placeholder="Escribir comentario..."></textarea>

                                <div class="d-flex gap-2">

                                    <button type="submit"
                                            name="accion"
                                            value="comentar"
                                            class="btn btn-primary btn-sm">
                                        Publicar
                                    </button>

                                    <button type="submit"
                                            name="accion"
                                            value="aprobar"
                                            class="btn btn-success btn-sm">
                                        Aprobar
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>


                    {{-- ================= DERECHA ================= --}}
                    {{-- ================= DERECHA ================= --}}
                    <div class="col-xl-6">

                        {{-- ================= ARCHIVOS ================= --}}
                        <div class="pt-2">

                            <div class="d-flex justify-content-between align-items-center border-bottom border-dashed pb-3 mb-3">

                                <div>
                                    <h3 class="mb-1">{{ $archivos->count() }}</h3>
                                    <p class="text-muted mb-0">Archivos involucrados</p>
                                </div>

                                <div class="h-48px w-48px d-flex justify-content-center align-items-center rounded bg-info-subtle text-info fs-3">
                                    <i class="bi bi-folder2-open"></i>
                                </div>

                            </div>

                            {{-- LISTADO --}}
                            <div class="d-flex flex-column gap-2">

                                @forelse($archivos as $archivo)

                                <div class="d-flex justify-content-between p-2 border rounded">

                                    <div>
                                        <span class="fw-medium">
                                            {{ $archivo->archivo ?? 'Archivo sin nombre' }}
                                        </span>

                                        <div class="text-muted small">
                                            {{ str_replace('_',' ', $archivo->tipo) }}
                                        </div>
                                    </div>

                                </div>

                                @empty

                                <div class="alert alert-light border">
                                    No hay archivos asociados a este resultado.
                                </div>

                                @endforelse

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>

</div>

<div class="row">
    <div class="col-lg-12">
        @foreach($cards as $card)

        @php
        $rows = $card->rows;
        $first = $card->first;
        $count = $card->count;
        @endphp

        <div class="card shadow-sm rounded-3 mb-4">

            <div class="card-body p-0">

                <div class="table-responsive rounded-3 overflow-hidden">

                    <table class="table table-bordered align-middle text-center mb-0">

                        <thead>
                            <tr class="border-bottom fw-bold">
                                <td rowspan="6" style="background:#F97316; width:300px;"></td>
                                <th rowspan="3">IDLAB</th>

                                <th colspan="4">Textura</th>
                                <th colspan="3">Propiedades Físicas</th>
                                <th colspan="2">Hidráulicas</th>
                                <th colspan="3">Ret H</th>
                                <th colspan="2">Estabilidad de Agregados</th>
                                <th colspan="1">Estructura</th>
                            </tr>

                            <tr class="small text-muted">
                                <th>Arena</th>
                                <th>Limo</th>
                                <th>Arcilla</th>
                                <th>Clase Textural</th>

                                <th>DA</th>
                                <th>DP</th>
                                <th>Por</th>
                                <th>HG</th>
                                <th>CH</th>
                                <th>Hg 33</th>
                                <th>Hg 1500</th>
                                <th>Agua Disp</th>
                                <th>DMP</th>
                                <th>EAA</th>
                                <th>COEL</th>
                            </tr>

                            <tr class="fw-light text-muted small opacity-75">
                                <th>%</th>
                                <th>%</th>
                                <th>%</th>
                                <th>-</th>

                                <th>g/cm³</th>
                                <th>g/cm³</th>
                                <th>%</th>

                                <th>%</th>
                                <th>cm/s</th>

                                <th>%</th>
                                <th>%</th>
                                <th>%</th>

                                <th>mm</th>
                                <th>%</th>

                                <th>-</th>
                            </tr>

                        </thead>

<tbody>

{{-- ================= FILA 1 ================= --}}
<tr>
    @php
    $statsT = $estadisticasTextura[$first->idlab] ?? null;
    $statsDA = $estadisticasDA[$first->idlab] ?? null;
    $statsDP = $estadisticasDP[$first->idlab] ?? null;
    $statsPor = $estadisticasPor[$first->idlab] ?? null;
    $statsHG = $estadisticasHG[$first->idlab] ?? null;
    $statsCH = $estadisticasCH[$first->idlab] ?? null;
    $statsRH = $estadisticasRH[$first->idlab] ?? null;
    $statsEA = $estadisticasEA[$first->idlab] ?? null;
    $statsCOEL = $estadisticasCOEL[$first->idlab] ?? null;

    $tieneStats =
    ($statsDA && count($statsDA['vals']) > 1) ||
    ($statsDP && count($statsDP['vals']) > 1) ||
    ($statsPor && count($statsPor['vals']) > 1) ||
    ($statsHG && count($statsHG['vals']) > 1) ||
    ($statsCH && count($statsCH['vals']) > 1) ||
    ($statsRH && (
        count($statsRH['Hg_33']['vals']) > 1 ||
        count($statsRH['Hg_1500']['vals']) > 1 ||
        count($statsRH['agua_disponible']['vals']) > 1
    )) ||
    ($statsEA && (
        count($statsEA['dmp']['vals']) > 1 ||
        count($statsEA['eaa']['vals']) > 1
    )) ||
    ($statsCOEL && count($statsCOEL['cole']['vals']) > 1);

    $totalFilas = $count + ($tieneStats ? 3 : 0);
    @endphp

    <td rowspan="{{ $totalFilas }}" class="text-start align-top p-4 border-end">

        <div class="fw-bold text-primary mb-3 fs-5">
            SOL. {{ $first->solicitud }}
        </div>

        <div class="small text-muted">Cultivo</div>
        <div class="fw-semibold text-primary">{{ $first->cultivo ?? '-' }}</div>

        <div class="small text-muted mt-2">Cliente</div>
        <div class="fw-semibold">{{ $first->cliente ?? '-' }}</div>

        <div class="small text-muted mt-2">Cantón</div>
        <div class="fw-semibold">{{ $first->canton ?? '-' }}</div>

        <div class="small text-muted mt-2">Fecha</div>
        <div class="fw-semibold">
            {{ \Carbon\Carbon::parse($first->fecha)->format('d/m/Y') }}
        </div>
    </td>

    <td>
        <span class="badge bg-primary-subtle text-primary">
            {{ $first->idlab }}
        </span>
        <div class="small text-muted">Rep {{ $first->rep }}</div>
    </td>

    @php
    $t = $texturas[$first->idlab][$first->rep] ?? null;
    $d = $densidades[$first->idlab][$first->rep] ?? null;
    $dp = $densidadesParticulas[$first->idlab][$first->rep] ?? null;
    $p = $porosidades[$first->idlab][$first->rep] ?? null;
    $hg = $humedades[$first->idlab][$first->rep] ?? null;
    $ch = $conductividades[$first->idlab][$first->rep] ?? null;
    $rh = $retenciones[$first->idlab][$first->rep] ?? null;
    $ea = $estabilidad[$first->idlab][$first->rep] ?? null;
    $coel = $coefExt[$first->idlab][$first->rep] ?? null;
    @endphp

    {{-- TEXTURA --}}
    <td>{{ $t ? number_format($t['arena'],1) : '-' }}</td>
    <td>{{ $t ? number_format($t['limo'],1) : '-' }}</td>
    <td>{{ $t ? number_format($t['arcilla'],1) : '-' }}</td>
    <td>{{ $t['clase'] ?? '-' }}</td>

    {{-- FISICAS --}}
    <td>{{ $d !== null ? number_format($d,3) : '-' }}</td>
    <td>{{ $dp !== null ? number_format($dp,3) : '-' }}</td>
    <td>{{ $p !== null ? number_format($p,2) : '-' }}</td>

    {{-- HIDRAULICAS --}}
    <td>{{ $hg !== null ? number_format($hg,2) : '-' }}</td>
    <td>{{ $ch !== null ? number_format($ch,6) : '-' }}</td>

    {{-- RETENCION --}}
    <td>{{ $rh && $rh['Hg_33'] !== null ? number_format($rh['Hg_33'],4) : '-' }}</td>
    <td>{{ $rh && $rh['Hg_1500'] !== null ? number_format($rh['Hg_1500'],4) : '-' }}</td>
    <td>{{ $rh && $rh['agua_disponible'] !== null ? number_format($rh['agua_disponible'],4) : '-' }}</td>

    {{-- ESTABILIDAD --}}
    <td>{{ $ea ? number_format($ea['dmp'],4) : '-' }}</td>
    <td>{{ $ea ? number_format($ea['eaa'],2) : '-' }}</td>

    {{-- COEL --}}
    <td>{{ $coel ? number_format($coel['cole'],4) : '-' }}</td>

</tr>

{{-- ================= RESTO ================= --}}
@foreach($rows->skip(1) as $row)
<tr>

    <td>
        <span class="badge bg-primary-subtle text-primary">
            {{ $row->idlab }}
        </span>
        <div class="small text-muted">Rep {{ $row->rep }}</div>
    </td>

    @php
    $t = $texturas[$row->idlab][$row->rep] ?? null;
    $d = $densidades[$row->idlab][$row->rep] ?? null;
    $dp = $densidadesParticulas[$row->idlab][$row->rep] ?? null;
    $p = $porosidades[$row->idlab][$row->rep] ?? null;
    $hg = $humedades[$row->idlab][$row->rep] ?? null;
    $ch = $conductividades[$row->idlab][$row->rep] ?? null;
    $rh = $retenciones[$row->idlab][$row->rep] ?? null;
    $ea = $estabilidad[$row->idlab][$row->rep] ?? null;
    $coel = $coefExt[$row->idlab][$row->rep] ?? null;
    @endphp

    <td>{{ $t ? number_format($t['arena'],1) : '-' }}</td>
    <td>{{ $t ? number_format($t['limo'],1) : '-' }}</td>
    <td>{{ $t ? number_format($t['arcilla'],1) : '-' }}</td>
    <td>{{ $t['clase'] ?? '-' }}</td>

    <td>{{ $d !== null ? number_format($d,3) : '-' }}</td>
    <td>{{ $dp !== null ? number_format($dp,3) : '-' }}</td>
    <td>{{ $p !== null ? number_format($p,2) : '-' }}</td>

    <td>{{ $hg !== null ? number_format($hg,2) : '-' }}</td>
    <td>{{ $ch !== null ? number_format($ch,6) : '-' }}</td>

    <td>{{ $rh && $rh['Hg_33'] !== null ? number_format($rh['Hg_33'],4) : '-' }}</td>
    <td>{{ $rh && $rh['Hg_1500'] !== null ? number_format($rh['Hg_1500'],4) : '-' }}</td>
    <td>{{ $rh && $rh['agua_disponible'] !== null ? number_format($rh['agua_disponible'],4) : '-' }}</td>

    <td>{{ $ea ? number_format($ea['dmp'],4) : '-' }}</td>
    <td>{{ $ea ? number_format($ea['eaa'],2) : '-' }}</td>
    <td>{{ $coel ? number_format($coel['cole'],4) : '-' }}</td>

</tr>
@endforeach

{{-- ================= STATS ================= --}}
@if($tieneStats)

<tr class="table-active">
    <td class="fw-semibold">PROMEDIO</td>

    <td>{{ $statsT['arena']['prom'] ?? '-' }}</td>
    <td>{{ $statsT['limo']['prom'] ?? '-' }}</td>
    <td>{{ $statsT['arcilla']['prom'] ?? '-' }}</td>
    <td>-</td>

    <td>{{ $statsDA['prom'] ?? '-' }}</td>
    <td>{{ $statsDP['prom'] ?? '-' }}</td>
    <td>{{ $statsPor['prom'] ?? '-' }}</td>
    <td>{{ $statsHG['prom'] ?? '-' }}</td>
    <td>{{ $statsCH['prom'] ?? '-' }}</td>

    <td>{{ $statsRH['Hg_33']['prom'] ?? '-' }}</td>
    <td>{{ $statsRH['Hg_1500']['prom'] ?? '-' }}</td>
    <td>{{ $statsRH['agua_disponible']['prom'] ?? '-' }}</td>

    <td>{{ $statsEA['dmp']['prom'] ?? '-' }}</td>
    <td>{{ $statsEA['eaa']['prom'] ?? '-' }}</td>
    <td>{{ $statsCOEL['cole']['prom'] ?? '-' }}</td>
</tr>

<tr class="table-active">
    <td class="fw-semibold">Desv.Est.</td>

    <td>{{ $statsT['arena']['desv'] ?? '-' }}</td>
    <td>{{ $statsT['limo']['desv'] ?? '-' }}</td>
    <td>{{ $statsT['arcilla']['desv'] ?? '-' }}</td>
    <td>-</td>

    <td>{{ $statsDA['desv'] ?? '-' }}</td>
    <td>{{ $statsDP['desv'] ?? '-' }}</td>
    <td>{{ $statsPor['desv'] ?? '-' }}</td>
    <td>{{ $statsHG['desv'] ?? '-' }}</td>
    <td>{{ $statsCH['desv'] ?? '-' }}</td>

    <td>{{ $statsRH['Hg_33']['desv'] ?? '-' }}</td>
    <td>{{ $statsRH['Hg_1500']['desv'] ?? '-' }}</td>
    <td>{{ $statsRH['agua_disponible']['desv'] ?? '-' }}</td>

    <td>{{ $statsEA['dmp']['desv'] ?? '-' }}</td>
    <td>{{ $statsEA['eaa']['desv'] ?? '-' }}</td>
    <td>{{ $statsCOEL['cole']['desv'] ?? '-' }}</td>
</tr>

<tr class="table-active">
    <td class="fw-semibold">CV</td>

    <td>{{ $statsT['arena']['cv'] ?? '-' }}</td>
    <td>{{ $statsT['limo']['cv'] ?? '-' }}</td>
    <td>{{ $statsT['arcilla']['cv'] ?? '-' }}</td>
    <td>-</td>

    <td>{{ $statsDA['cv'] ?? '-' }}</td>
    <td>{{ $statsDP['cv'] ?? '-' }}</td>
    <td>{{ $statsPor['cv'] ?? '-' }}</td>
    <td>{{ $statsHG['cv'] ?? '-' }}</td>
    <td>{{ $statsCH['cv'] ?? '-' }}</td>

    <td>{{ $statsRH['Hg_33']['cv'] ?? '-' }}</td>
    <td>{{ $statsRH['Hg_1500']['cv'] ?? '-' }}</td>
    <td>{{ $statsRH['agua_disponible']['cv'] ?? '-' }}</td>

    <td>{{ $statsEA['dmp']['cv'] ?? '-' }}</td>
    <td>{{ $statsEA['eaa']['cv'] ?? '-' }}</td>
    <td>{{ $statsCOEL['cole']['cv'] ?? '-' }}</td>
</tr>

@endif

</tbody>
                    </table>

                </div>
            </div>
        </div>

        @endforeach

    </div>
</div>

</div><!--End container-fluid-->
</main><!--End app-wrapper-->
@endsection


@section('js')
<script src="{{ asset('libs/prismjs/prism.js') }}"></script>
<script  src="{{ asset('libs/sortablejs/Sortable.min.js') }}"></script>

<!--App js-->
<script type="module" src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
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