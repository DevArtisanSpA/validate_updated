@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <div class="d-flex justify-content-between">
        <h3>Revisión de documentos base de empresas</h3>
    </div>
    <review-table-company-base :auth="{{ $auth }}" :companies="{{ $companies }}"
></review-table-company-base>
</div>
@endsection