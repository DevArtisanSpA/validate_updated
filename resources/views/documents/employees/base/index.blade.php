@extends('layouts.app')

@section('content')
<div class="col-md-9">
<a href="javascript:history.back()"><span>
      << Volver</span> </a>
    <div class="d-flex justify-content-between">
        <h3>Documentos base de empleados</h3>
    </div>
    <h5 class="text-secondary text-justify">Para el servicio
  <strong>{{ $service->description }}</strong> entregado por la empresa contratista
  <strong>{{ $service->company->business_name }}</strong> para la sucursal
  <strong>{{ $service->branchOffice->name }}</strong> de la empresa principal
  <strong>{{ $service->branchOffice->company->business_name }}</strong>
</h5>
    <document-table-employee-base :auth="{{ $auth }}" :employees="{{ $employees }}"></document-table-employee-base>
</div>
@endsection