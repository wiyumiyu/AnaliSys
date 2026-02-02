@extends('partials.layouts.master')

@section('title', 'Editar muestra - Densidad aparente')

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

            {{-- HEADER --}}
            <div class="card-header">
                <div class="d-flex flex-wrap gap-4 justify-content-between align-items-center">

                    <h5 class="mb-0 fw-semibold">
                        Editar muestra – Densidad aparente {{ $muestra->idlab }}
                    </h5>

                    <a href="{{ url()->previous() }}" class="btn btn-primary">
                        ← Volver
                    </a>

                </div>
            </div>

            {{-- BODY --}}
            <div class="card-body">

                <form method="POST"
                      action="{{ route('densidad_aparente.muestra.update', $muestra->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- ================= DATOS DE LA MUESTRA ================= --}}
                    <h5 class="fw-semibold mb-3">
                        Datos de la muestra
                    </h5>

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">ID Lab</label>
                            <input class="form-control"
                                   value="{{ $muestra->idlab }}"
                                   disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Repetición</label>
                            <input name="rep"
                                   class="form-control"
                                   value="{{ $muestra->rep }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Material</label>
                            <input name="material"
                                   class="form-control"
                                   value="{{ $muestra->material }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tipo de muestra</label>
                            <input name="tipo"
                                   class="form-control"
                                   value="{{ $muestra->tipo }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Posición</label>
                            <input name="posicion"
                                   class="form-control"
                                   value="{{ $muestra->posicion }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="1" @selected($muestra->estado == 1)>Activo</option>
                                <option value="0" @selected($muestra->estado == 0)>Anulado</option>
                            </select>
                        </div>

                    </div>

                    {{-- ================= RESULTADOS DE DENSIDAD ================= --}}
                    <br><br>
                    <h5 class="fw-semibold mb-3">
                        Resultados de densidad aparente
                    </h5>

                    <div class="row g-3">

                        @foreach($resultados as $r)
                            <div class="col-md-3">
                                <label class="form-label">
                                    {{ $r->analisis }}
                                    <small class="text-muted">({{ $r->siglas }})</small>
                                </label>

                                <input type="text"
                                       class="form-control"
                                       name="resultados[{{ $r->id_resultado }}]"
                                       value="{{ $r->resultado }}">
                            </div>
                        @endforeach

                    </div>

                    {{-- ================= BOTONES ================= --}}
                    <br><br>
                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary">
                            Guardar cambios
                        </button>

                        <a href="{{ url()->previous() }}"
                           class="btn btn-light-primary">
                            Cancelar
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

</div><!--End container-fluid-->
</main><!--End app-wrapper-->
@endsection

@section('js')
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script src="{{ asset('js/table/datatable.init.js') }}"></script>
<script src="{{ asset('js/table/buscarEnTabla.js') }}"></script>

<script src="{{ asset('libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
@endsection
