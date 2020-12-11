@extends('layouts.app')

@section('content')
<div class="col-md-9">
<a href="javascript:history.back()"><span><< Volver</span></a>
    <div class="d-flex justify-content-between">
        <h3>Asociar servicio contratista</h3>
    </div>

    @csrf
    <associate-service
        :data-list="{{ $dataList }}"
        :auth="{{ $auth }}"
        :is_update="0"
        :service_types="{{ $serviceTypes }}"
        :branchOffices="{{ $branchOffices }}"></associate-service>
</div>
@endsection
