@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <a href="javascript:history.back()"><span><< Volver</span></a>
    <div class="d-flex justify-content-start">
      <h3>Editar servicio</h3>
    </div>
    @csrf
    <service-form
      :rut_company="{{ $companyRut }}"
      :company_id="{{ $companyId }}"
      :service_types="{{ $serviceTypes }}"
      :companies="{{ $companies }}"
      :service="{{ $service }}"
      :auth="{{ $auth }}"></service-form>
</div>
@endsection
