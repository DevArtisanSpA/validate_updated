@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <div class="d-flex justify-content-between">
        <h3>Revisión de documentos mensuales de empleados</h3>
    </div>
    <review-table-employee-monthly :auth="{{ $auth }}" :employees="{{ $employees }}"></review-table-employee-monthly>

</div>
@endsection