@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <a href="javascript:history.back()"><span><< Volver</span></a>
    <div class="d-flex justify-content-between">
        <h3>Editar usuario</h3>
    </div>
    @csrf
    <user-form :user="{{ $user }}" :create="{{ $create }}" :is_update="1"></user-form>
</div>
@endsection
