@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <a href="javascript:history.back()"><span><< Volver</span></a>
    <div class="d-flex justify-content-between">
        <h3>Finiquitar empleado</h3>
    </div>
    @csrf
    <terminate-employee :datas_list="{{ $datas_list }}" :auth="{{ $auth }}"></terminate-employee>
</div>
@endsection
