@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <div class="d-flex justify-content-between">
        <h3>Administración de usuarios</h3>
        <button type="button" onclick="window.location.href='{{ url('/users/create') }}'"
            class="btn btn-success">Nuevo usuario</button>
    </div>
    <user-table :users="{{ $users }}"></user-table>
</div>
@endsection
