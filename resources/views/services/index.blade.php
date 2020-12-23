@extends('layouts.app')

@section('content')
    <div class="col-md-9">
        <div class="d-flex justify-content-between">
            <h3>Lista de servicios</h3>
            <button type="button" onclick="window.location.href='{{ url('/services/associate') }}'"
                class="btn btn-success">Asociar servicio</button>
        </div>
        <service-table
         :services="{{ $services }}"
         :auth="{{ $auth }}"
        ></service-table>
    </div>
@endsection
