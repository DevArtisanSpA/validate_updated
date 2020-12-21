@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <a href="javascript:history.back()"><span><< Volver</span></a>
    <div class="d-flex justify-content-start">

        @if( $auth->user_type_id == 1 || $auth->id_company == $company->id )
            <h3>Editar detalle empresa</h3>
        @else
            <h3>Ver detalle empresa</h3>
        @endif
    </div>
    @csrf
    <company-form
        :company="{{ $company }}"
        :data-list="{{ $dataList }}"
        :auth="{{ $auth }}"
        :is_update="1"></company-form>
</div>
@endsection
