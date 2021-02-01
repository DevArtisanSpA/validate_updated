@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <div class="d-flex justify-content-between">
        <h3>Documentos
            @if ($monthly==1)
            bases
            @else
            mensuales
            @endif
            de empleados por servicio
        </h3>
    </div>
    <document-pre-table-employee :auth="{{ $auth }}" :services="{{ $services }}" :monthly="{{ $monthly}}" :period="{{$period}}"></document-pre-table-employee>

</div>
@endsection