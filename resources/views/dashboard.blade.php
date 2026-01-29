@extends('partials.layouts.master')

@section('title', 'Lista de Usuarios')
@section('content')



    <h1>Dashboard</h1>

    <p>Bienvenido {{ session('nombre') }} {{ session('apellido1') }}</p>
    <p>Rol: {{ session('rol') }}</p>
        </div><!--End container-fluid-->
    </main><!--End app-wrapper-->
@endsection

@section('js')
<!-- Bootstrap -->
<!-- Bootstrap -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('libs/simplebar/simplebar.min.js') }}"></script>
<!-- FabKin App -->
<script src="{{ asset('js/app.js') }}"></script>
@endsection




