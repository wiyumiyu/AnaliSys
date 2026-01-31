@extends('partials.layouts.master')

@section('title', 'Densidad Aparente - Archivos')

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
                        Densidad Aparente
                    </h5>

                    {{-- ACCIONES --}}
                    <div class="d-flex gap-3 align-items-center">

                        {{-- AÑO --}}
                        <select class="form-select w-auto"
                                onchange="location='?anio='+this.value">
                            @for($i = date('Y'); $i >= date('Y')-10; $i--)
                                <option value="{{ $i }}" @selected($anio==$i)>
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
                        <a href="#"
                           class="btn btn-primary">
                            <i class="ri-upload-2-line me-1"></i>
                            Importar
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
                            <th>Archivo</th>
                            <th>Fecha</th>
                            <th>Analista</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($archivos as $l)
                        <tr>

                            {{-- ARCHIVO --}}
                            <td>
                                <h6 class="mb-0">
                                    <a href="{{ route('densidad_aparente.muestras', $l->id_archivo) }}">
                                        {{ $l->archivo }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    ID {{ $l->id_archivo }}
                                </small>
                            </td>

                            {{-- FECHA --}}
                            <td>{{ $l->fecha }}</td>

                            {{-- ANALISTA --}}
                            <td>{{ $l->analista }}</td>

                            {{-- ACCIONES --}}
                            <td class="text-end">
                                <div class="hstack gap-2 fs-15 justify-content-end">

                                    <a href="{{ route('densidad_aparente.muestras', $l->id_archivo) }}"
                                       class="btn bg-primary-subtle text-primary btn-sm">
                                        <i class="ri-eye-line"></i>
                                    </a>

                                    <button class="btn bg-danger-subtle text-danger btn-sm">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="4"
                                class="text-center text-muted py-4">
                                No hay archivos registrados para este año.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>

    </div>
</div>

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
