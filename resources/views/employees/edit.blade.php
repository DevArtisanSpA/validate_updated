@extends('layouts.app')

@section('content')
<div class="col-md-9">
  <a href="javascript:history.back()"><span>
      << Volver</span> </a> <div class="d-flex justify-content-between">
        <h3>Editar Empleado</h3>
</div>
<h5 class="text-secondary text-justify">Para el servicio
  <strong>{{ $service->description }}</strong> entregado por
  <strong>{{ $service->company->business_name }}</strong> para la sucursal
  <strong>{{ $service->branchOffice->name }}</strong> de la compañia
  <strong>{{ $service->branchOffice->company->business_name }}</strong>
</h5>

@csrf
<employee-form :data-list="{{ $dataList }}" :auth="{{ $auth }}" 
:is_update="0" :service="{{ $service }}" :employee="{{ $employee }}">
</employee-form>
</div>
@endsection