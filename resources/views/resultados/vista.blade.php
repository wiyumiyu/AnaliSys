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


        {{-- TARJETA 1 --}}
        <div class="card shadow-sm rounded-3 mb-4">

            <div class="card-body p-0">

                <div class="table-responsive rounded-3 overflow-hidden">

                    <table class="table table-bordered align-middle text-center mb-0">

                        <thead>
                            <tr class="border-bottom">

                                <td rowspan="6" style="background:#F97316; width:300px;"></td>

                                <th rowspan="2">IDLAB</th>
                                <th rowspan="2">Cultivo</th>

                                <th colspan="4">Textura (%)</th>
                                <th colspan="8">(%)</th>

                            </tr>

                            <tr>
                                <th>Arena</th>
                                <th>Limo</th>
                                <th>Arcilla</th>
                                <th>Total</th>

                                <th>DA</th>
                                <th>DP</th>
                                <th>Por</th>
                                <th>HG</th>
                                <th>CH</th>
                                <th>Ret H</th>
                                <th>Est Agr</th>
                                <th>COEL</th>
                            </tr>
                        </thead>

                        <tbody>

                            {{-- DATOS --}}
                            <tr>
                                <td rowspan="8" class="text-start align-top p-4 border-end">

                                    <div class="fw-bold text-primary mb-3 fs-5">
                                        SOL. 97987
                                    </div>

                                    <div class="small text-muted">Cliente</div>
                                    <div class="fw-semibold">FLORES Y VERDES DEL IRAZU</div>

                                    <div class="small text-muted mt-2">Cantón</div>
                                    <div class="fw-semibold">CARTAGO</div>

                                    <div class="small text-muted mt-2">Fecha</div>
                                    <div class="fw-semibold">06/01/2026</div>

                                </td>

                                <td><span class="badge bg-primary-subtle text-primary">13</span></td>
                                <td class="fw-semibold text-primary">CAFE</td>

                                <td>38</td>
                                <td>42</td>
                                <td>20</td>
                                <td>100</td>

                                <td>1.25</td>
                                <td>2.60</td>
                                <td>18%</td>
                                <td>30%</td>
                                <td>5%</td>
                                <td>80%</td>
                                <td>0.12</td>
                                <td>0.85</td>
                            </tr>

                            <tr>
                                <td><span class="badge bg-primary-subtle text-primary">13</span></td>
                                <td class="fw-semibold text-primary">CAFE</td>

                                <td>39</td>
                                <td>41</td>
                                <td>20</td>
                                <td>100</td>

                                <td>1.35</td>
                                <td>2.58</td>
                                <td>15%</td>
                                <td>25%</td>
                                <td>8%</td>
                                <td>75%</td>
                                <td>0.10</td>
                                <td>0.78</td>
                            </tr>

                            <tr>
                                <td><span class="badge bg-primary-subtle text-primary">13</span></td>
                                <td class="fw-semibold text-primary">CAFE</td>

                                <td>37</td>
                                <td>43</td>
                                <td>20</td>
                                <td>100</td>

                                <td>1.30</td>
                                <td>2.59</td>
                                <td>17%</td>
                                <td>28%</td>
                                <td>6%</td>
                                <td>78%</td>
                                <td>0.11</td>
                                <td>0.82</td>
                            </tr>


                            {{-- PROMEDIO --}}
                            <tr class="table-light">

                                <td colspan="2" class="fw-semibold">
                                    PROMEDIO
                                </td>

                                <td>38</td>
                                <td>42</td>
                                <td>20</td>
                                <td>100</td>

                                <td>1.30</td>
                                <td>2.59</td>
                                <td>16.6%</td>
                                <td>27.6%</td>
                                <td>6.3%</td>
                                <td>77.6%</td>
                                <td>0.11</td>
                                <td>0.81</td>

                            </tr>


                            {{-- DESV EST --}}
                            <tr class="table-light">

                                <td colspan="2" class="fw-semibold">
                                    Desv.Est.
                                </td>

                                <td>1</td>
                                <td>1</td>
                                <td>0</td>
                                <td>-</td>

                                <td>0.05</td>
                                <td>0.01</td>
                                <td>1.5%</td>
                                <td>2%</td>
                                <td>1%</td>
                                <td>2%</td>
                                <td>0.01</td>
                                <td>0.03</td>

                            </tr>


                            {{-- CV --}}
                            <tr class="table-light">

                                <td colspan="2" class="fw-semibold">
                                    CV
                                </td>

                                <td>3%</td>
                                <td>2%</td>
                                <td>0%</td>
                                <td>-</td>

                                <td>3.8%</td>
                                <td>0.3%</td>
                                <td>9%</td>
                                <td>7%</td>
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



        {{-- TARJETA 2 --}}
        <div class="card shadow-sm rounded-3 mb-4">

            <div class="card-body p-0">

                <div class="table-responsive rounded-3 overflow-hidden">

                    <table class="table table-bordered align-middle text-center mb-0">

                        <thead>

                            <tr class="border-bottom">

                                <td rowspan="6" style="background:#F97316; width:300px;"></td>

                                <th rowspan="2">IDLAB</th>
                                <th rowspan="2">Cultivo</th>

                                <th colspan="4">Textura (%)</th>
                                <th colspan="8">(%)</th>

                            </tr>

                            <tr>

                                <th>Arena</th>
                                <th>Limo</th>
                                <th>Arcilla</th>
                                <th>Total</th>

                                <th>DA</th>
                                <th>DP</th>
                                <th>Por</th>
                                <th>HG</th>
                                <th>CH</th>
                                <th>Ret H</th>
                                <th>Est Agr</th>
                                <th>COEL</th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr>

                                <td rowspan="8" class="text-start align-top p-4 border-end">

                                    <div class="fw-bold text-primary mb-3 fs-5">
                                        SOL. 97988
                                    </div>

                                    <div class="mb-2">
                                        <div class="small text-muted">Cliente</div>
                                        <div class="fw-semibold">
                                            COOPEAGRI
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="small text-muted">Cantón</div>
                                        <div class="fw-semibold">
                                            SAN ISIDRO
                                        </div>
                                    </div>

                                    <div>
                                        <div class="small text-muted">Fecha</div>
                                        <div class="fw-semibold">
                                            10/01/2026
                                        </div>
                                    </div>

                                </td>

                                <td>
                                    <span class="badge bg-primary-subtle text-primary">
                                        14
                                    </span>
                                </td>

                                <td class="fw-semibold text-primary">CAFE</td>

                                <td>36</td>
                                <td>44</td>
                                <td>20</td>
                                <td>100</td>

                                <td>1.22</td>
                                <td>2.55</td>
                                <td>19%</td>
                                <td>31%</td>
                                <td>6%</td>
                                <td>79%</td>
                                <td>0.11</td>
                                <td>0.84</td>

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