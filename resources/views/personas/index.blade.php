@extends('layouts.master')

@section('title', 'Personas')

@section('content')
    <h1>Personas</h1>

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Rol</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($personas as $p)
                <tr>
                    <td>{{ $p->id_persona }}</td>
                    <td>{{ $p->nombre_completo }}</td>
                    <td>{{ $p->cedula }}</td>
                    <td>{{ $p->rol }}</td>
                    <td>{{ $p->id_estado == 1 ? 'Activo' : 'Inactivo' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
