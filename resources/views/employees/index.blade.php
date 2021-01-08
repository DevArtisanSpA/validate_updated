@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <div class="d-flex justify-content-between">
        @if ($auth->user_type_id == 1)
        <h3>Administración de empleados</h3>
        @endif
    </div>
    <employee-table :auth="{{ $auth }}" :employees="{{ $employees }}" />
</div>
@endsection