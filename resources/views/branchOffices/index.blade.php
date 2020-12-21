@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <div class="d-flex justify-content-between">
        <h3>Sucursales</h3>
        <button type="button" onclick="window.location.href='{{ url('/branch_offices/create') }}'"
            class="btn btn-success">Nueva sucursal</button>
    </div>
    <branch-office-table 
        :companies="{{ $companies }}" 
        :companies_branch="{{ $companies_branch }}" 
        :auth="{{ $auth }}" 
    />
</div>
@endsection