@extends('layouts.app')

@section('content')
<div class="col-md-9">
<a href="javascript:history.back()"><span><< Volver</span></a>
    <div class="d-flex justify-content-between">
        <h3>Nuevo Empleado</h3>
    </div>
    <h5 class="text-secondary">{{$service->description}}</h5>

    @csrf
    <employee-form
        :data-list="{{ $dataList }}"
        :auth="{{ $auth }}"
        :is_update="0"
        :service="{{ $service}}"
        ></employee-form>
</div>
@endsection