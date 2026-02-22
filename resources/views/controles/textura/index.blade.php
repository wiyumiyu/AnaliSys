@extends('partials.layouts.master')

@section('title', 'Textura - Archivos')

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

        {{-- CARD --}}
        <div class="card">
            <div class="card-header">
                <div class="d-flex gap-4 justify-content-between align-items-center">

                    {{-- TÍTULO --}}
                    <h5 class="mb-0 fw-semibold">
                        Controles de Textura
                    </h5>

                    {{-- ACCIONES --}}
                    {{-- ACCIONES --}}
                    <div class="d-flex align-items-center gap-3 flex-nowrap">

                        {{-- AÑO --}}
                        <select class="form-select w-auto"
                                onchange="location = '?periodo=' + this.value">
                            @for($i = date('Y'); $i >= date('Y')-10; $i--)
                            <option value="{{ $i }}" @selected($periodo==$i)>
                                {{ $i }}
                            </option>
                            @endfor
                        </select>

                        {{-- BUSCAR --}}
                        <div class="form-icon">
                            <input type="text"
                                   class="form-control form-control-icon"
                                   placeholder="Buscar ...">
                            <i class="ri-search-2-line text-muted"></i>
                        </div>

                        {{-- IMPORTAR --}}



<a href=""
   class="btn btn-primary mb-0">
    <i class="ri-add-large-line me-1"></i>
    Nuevo
</a>

                        
                      

                    </div>
                </div>
            </div>


            <div class="card-body">

                {{-- TABLE --}}
                <table id="default_datatable"
                       class="table table-nowrap align-middle">

                    <thead>
                        <tr>
                            <th>Consecutivo</th>
                            <th>Fecha</th>
                            <th>Analista</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($consecutivosControles as $l)
                        <tr>

                            {{-- ARCHIVO --}}
                            <td>
                                <h6 class="mb-0">
                                    <a href=""
                                       class="text-reset fw-semibold text-decoration-none fs-6">
                                        {{ $l->consecutivo }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    ID {{ $l->id}}
                                </small>
                            </td>

                            {{-- FECHA --}}
                            <td>{{ $l->fecha }}</td>

                            {{-- ANALISTA --}}
                            <td>{{ $l->responsable }}</td>

                            {{-- ACCIONES --}}
                            <td class="text-end">
                                <div class="hstack gap-2 fs-15 justify-content-end">

                                    <a href=""
                                       class="btn bg-primary-subtle text-primary btn-sm">
                                        <i class="ri-eye-line"></i>
                                    </a>

                                    <button class="btn bg-danger-subtle text-danger btn-sm">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>

                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>


                </table>

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