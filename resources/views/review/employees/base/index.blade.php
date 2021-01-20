@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <div class="d-flex justify-content-between">
        <h3>Revisión de documentos base de empleados</h3>
    </div>
    <review-table-employee-base :auth="{{ $auth }}" :employees="{{ $employees }}"></review-table-employee-base>
</div>
@endsection