@extends('layouts.app')

@section('content')
<div class="col-md-9">
  <a href="javascript:history.back()"><span>
      << Volver</span> </a> <div class="d-flex justify-content-between">
        <h3>Validar documentos base de empresa</h3>
</div>

<!-- class="text-secondary">Para el servicio
  <strong>{{ $service->description }}</strong> entregado por la empresa contratista
  <strong>{{ $service->company->business_name }}</strong> para la sucursal
  <strong>{{ $service->branchOffice->name }}</strong> de la empresa principal
  <strong>{{ $service->branchOffice->company->business_name }}</strong>
</!--> 
@csrf
<review-form :auth="{{ $auth }}" :service="{{$service}}" :documents=" {{$documents}}" ></review-form>
</div>
@endsection