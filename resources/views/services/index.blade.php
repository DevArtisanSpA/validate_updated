@extends('layouts.app')

@section('content')
    <div class="col-md-9">
        <div class="d-flex justify-content-between">
            <h3>Lista de servicios</h3>
            <button type="button" onclick="window.location.href='{{ url('/services/associate') }}'"
                class="btn btn-success">Asociar servicio</button>
        </div>
        @if ($auth->user_type_id == 1)
            <service-table
            :services="{{ $services }}"
            :auth="{{ $auth }}"
            ></service-table>
        @else
            <service-multitable
            :services="{{ $services }}"
            :auth="{{ $auth }}"
            ></service-multitable>
        @endif
    </div>
@endsection
