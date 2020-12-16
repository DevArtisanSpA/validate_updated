@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <a href="javascript:history.back()"><span><< Volver</span></a>
    <div class="d-flex justify-content-between">
        <h3>Editar sucursal de empresa</h3>
    </div>
    @csrf
    <branch-office-new-edit
        :branch="{{ $branch }}"
        :regions="{{ $regions }}"
        :auth="{{ $auth }}"
        :companies="{{ $companies }}"
        :is_update="1"
    > </branch-office-new-edit>
</div>
@endsection