


@extends('partials.layouts.master')

@section('title', 'Vista solicitud')

@section('css')
<!-- Datatables CSS (FabKin style) -->
<link rel="stylesheet"
      href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css"/>
<link rel="stylesheet"
      href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css"/>
@endsection

@section('content')

<div class="table-responsive">
<table class="table table-bordered table-sm align-middle mb-0 text-center">

    {{-- HEADER NEGRO --}}
    <thead style="background:#000;color:#fff" class="small">
        <tr>
            <th style="min-width:200px">INFORMACIÓN</th>
            <th>IDLAB</th>
            <th>TEXT</th>
            <th>DA</th>
            <th>DP</th>
            <th>HG</th>
            <th>Ret.H</th>
            <th>C_RetH</th>
            <th>Frac.A</th>
            <th>Est.Agr</th>
            <th>COEL</th>
            <th>Con.Por</th>
        </tr>
    </thead>

    <tbody>

        {{-- ================= BLOQUE IDLAB 13 ================= --}}
        <tr>
            {{-- COLUMNA IZQUIERDA FIJA --}}
            <td rowspan="6" class="text-start align-top bg-light">
                <strong class="text-success">SOL.97987</strong><br>
                OSA<br>
                PROYECTO VI-733-C5604<br>
                06/01/2026
            </td>

            <td><strong>13</strong></td>
            <td>Fr.Ar</td>
            <td>1.25</td>
            <td>2.60</td>
            <td>18%</td>
            <td>30%</td>
            <td>A</td>
            <td>5%</td>
            <td>80%</td>
            <td>0.12</td>
            <td>0.85</td>
        </tr>

        <tr>
            <td><strong>13</strong></td>
            <td>Fr.Ar</td>
            <td>1.35</td>
            <td>2.58</td>
            <td>15%</td>
            <td>25%</td>
            <td>B</td>
            <td>8%</td>
            <td>75%</td>
            <td>0.10</td>
            <td>0.78</td>
        </tr>

        <tr>
            <td><strong>13</strong></td>
            <td>Fr.Ar</td>
            <td>1.30</td>
            <td>2.59</td>
            <td>17%</td>
            <td>28%</td>
            <td>A</td>
            <td>6%</td>
            <td>78%</td>
            <td>0.11</td>
            <td>0.82</td>
        </tr>

        {{-- PROMEDIO --}}
        <tr style="background:#f2f2f2;font-weight:600">
            <td>PROMEDIO</td>
            <td>Fr.Ar</td>
            <td>1.30</td>
            <td>2.59</td>
            <td>16.6%</td>
            <td>27.6%</td>
            <td>A</td>
            <td>6.3%</td>
            <td>77.6%</td>
            <td>0.11</td>
            <td>0.81</td>
        </tr>

        {{-- DESV EST --}}
        <tr>
            <td class="text-success">Desv.Est.</td>
            <td>-</td>
            <td>0.05</td>
            <td>0.01</td>
            <td>1.5%</td>
            <td>2%</td>
            <td>-</td>
            <td>1%</td>
            <td>2%</td>
            <td>0.01</td>
            <td>0.03</td>
        </tr>

        {{-- CV --}}
        <tr>
            <td class="text-success">CV</td>
            <td>-</td>
            <td>3.8%</td>
            <td>0.3%</td>
            <td>9%</td>
            <td>7%</td>
            <td>-</td>
            <td>15%</td>
            <td>2%</td>
            <td>9%</td>
            <td>3%</td>
        </tr>

        {{-- ================= FIN BLOQUE ================= --}}

    </tbody>
</table>
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