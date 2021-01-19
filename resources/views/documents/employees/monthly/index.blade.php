@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <div class="d-flex justify-content-between">
        <h3>Documentos mensuales de empleados</h3>
    </div>
    <document-table-employee-monthly :auth="{{ $auth }}" :employees="{{ $employees }}"></document-table-employee-monthly>

</div>
@endsection