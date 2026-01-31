
@extends('partials.layouts.master')

@section('title', 'Editar muestra')

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

            {{-- HEADER --}}
            <div class="card-header">
                <div class="d-flex flex-wrap gap-4 justify-content-between align-items-center">

                    <h5 class="mb-0 fw-semibold">
                        Editar muestra {{ $muestra->idlab }}
                    </h5>

                    <div class="d-flex gap-3 align-items-center">
                        <a href="{{ url()->previous() }}"
                           class="btn btn-primary">
                            ← Volver
                        </a>
                    </div>

                </div>
            </div>

            {{-- BODY --}}
            <div class="card-body">

                <form method="POST"
                      action="{{ route('pa.muestra.update', $muestra->id) }}">
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
                            <label class="form-label">Método</label>
                            <input name="metodo"
                                   class="form-control"
                                   value="{{ $muestra->metodo }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tipo de muestra</label>
                            <input name="tipomuestra"
                                   class="form-control"
                                   value="{{ $muestra->tipomuestra }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Longitud</label>
                            <input name="longitud"
                                   class="form-control"
                                   value="{{ $muestra->longitud }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Diámetro interno</label>
                            <input name="diametrointerno"
                                   class="form-control"
                                   value="{{ $muestra->diametrointerno }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Área transversal</label>
                            <input name="areatransversal"
                                   class="form-control"
                                   value="{{ $muestra->areatransversal }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Volumen</label>
                            <input name="volumen"
                                   class="form-control"
                                   value="{{ $muestra->volumen }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Temperatura del aire</label>
                            <input name="temperaturaaire"
                                   class="form-control"
                                   value="{{ $muestra->temperaturaaire }}">
                        </div>

                    </div>

                    {{-- ================= BOTONES ================= --}}
                    <br><br>
                    <hr class="my-4">

                    <div class="mt-4 d-flex gap-2">
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