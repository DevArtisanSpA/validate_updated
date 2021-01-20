@extends('layouts.app')

@section('content')
<div class="col-md-9">
  <a href="javascript:history.back()"><span>
      << Volver</span> </a>
  <div class="d-flex justify-content-start align-items-end">
    <h3>Validar documentos mensuales de empleado</h3>
    <h4 class="text-info" style="font-size: 1.3em;">&ensp; al periodo {{$monthYear}}</h4>
  </div>

  <h5 class="text-secondary">Para el servicio
  <strong>{{ $service->description }}</strong> entregado por la empresa contratista
  <strong>{{ $service->company->business_name }}</strong> para la sucursal
  <strong>{{ $service->branchOffice->name }}</strong> de la empresa principal
  <strong>{{ $service->branchOffice->company->business_name }}</strong>
</h5>
  @csrf
  <review-form :auth="{{ $auth }}" :service="{{$service}}" :documents=" {{$documents}}"
    
   :monthly="{{true}}"
   :employee="{{$employee}}"

   ></review-form>
</div>
@endsection