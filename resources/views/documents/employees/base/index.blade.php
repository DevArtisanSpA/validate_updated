@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <div class="d-flex justify-content-between">
        <h3>Documentos base de empleados</h3>
    </div>
    <document-table-employee-base :auth="{{ $auth }}" :employees="{{ $employees }}"></document-table-employee-base>
</div>
@endsection