@extends('layouts.app')

@section('content')
<div class="col-md-9">
<a href="javascript:history.back()"><span><< Volver</span></a>
    <div class="d-flex justify-content-between">
        <h3>Nueva empresa</h3>
    </div>

    @csrf
    <company-form
        :data-list="{{ $dataList }}"
        :auth="{{ $auth }}"
        :is_update="0"></company-form>
</div>
@endsection
