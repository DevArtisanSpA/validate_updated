@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <div class="d-flex justify-content-between">
        <h3>Documentos mensuales de empresas</h3>
    </div>
    <document-table-company-monthly :auth="{{ $auth }}" :companies="{{ $companies }}"></document-table-company-monthly>

</div>
@endsection