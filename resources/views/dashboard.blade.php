@extends('partials.layouts.master')

@section('title', 'Academy | FabKin Admin & Dashboards Template')
@section('title-sub', 'E-Commerce ')
@section('pagetitle', 'Academy')
@section('content')



    <h1>Dashboard</h1>

    <p>Bienvenido {{ session('nombre') }} {{ session('apellido1') }}</p>
    <p>Rol: {{ session('rol') }}</p>
        </div><!--End container-fluid-->
    </main><!--End app-wrapper-->
@endsection

@section('js')
    <script src="/libs/apexcharts/apexcharts.min.js"></script>

    <script src="/js/dashboard/academy.init.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <!-- App js -->
    <script src="/js/app.js"></script>
@endsection




