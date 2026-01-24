@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard</h1>

    <p>Bienvenido {{ session('nombre') }} {{ session('apellido1') }}</p>
    <p>Rol: {{ session('rol') }}</p>
    
    <button id="toggleSidebar">
  <i class="bi bi-arrow-bar-left header-icon"></i>
</button>
@endsection

