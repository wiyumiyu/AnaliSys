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