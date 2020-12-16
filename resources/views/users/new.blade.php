@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <a href="javascript:history.back()"><span><< Volver</span></a>
    <div class="d-flex justify-content-between">
        <h3>Nuevo usuario</h3>
    </div>
    @csrf
    <user-form :create="{{ $create }}" :is_update="0"></user-form>

</div>
@endsection
