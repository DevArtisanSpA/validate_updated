@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <div class="d-flex justify-content-between">
        <h3>Revisión de los documentos
            @if ($monthly==1)
            bases
            @else
            mensuales
            @endif
            de los empleados por servicio </h3>
    </div>
    <review-pre-table-employee :auth="{{ $auth }}" :services="{{ $services }}" :monthly="{{ $monthly}}" :period="{{$period}}"></review-pre-table-employee>

</div>
@endsection