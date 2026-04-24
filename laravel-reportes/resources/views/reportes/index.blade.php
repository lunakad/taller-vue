@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Reportes del sistema</h1>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Descargas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportes as $reporte)
                <tr>
                    <td>{{ $reporte->codigo }}</td>
                    <td>{{ $reporte->nombre }}</td>
                    <td>{{ $reporte->descripcion }}</td>
                    <td class="d-flex gap-2">
                        <a class="btn btn-success btn-sm" href="{{ route('reportes.exportar', ['sistema' => $sistemaId, 'reporte' => $reporte->id, 'formato' => 'xlsx']) }}">Excel</a>
                        <a class="btn btn-primary btn-sm" href="{{ route('reportes.exportar', ['sistema' => $sistemaId, 'reporte' => $reporte->id, 'formato' => 'ods']) }}">ODS</a>
                        <a class="btn btn-secondary btn-sm" href="{{ route('reportes.exportar', ['sistema' => $sistemaId, 'reporte' => $reporte->id, 'formato' => 'csv']) }}">CSV</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay reportes activos para este sistema.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
