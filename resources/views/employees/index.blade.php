@extends('layouts.app')

@section('content')
    <div class="col-md-9">
        <div class="d-flex justify-content-between">
            @if ($auth->user_type_id == 1)
                <h3>Administración de empleados</h3>
                {{-- <button type="button" onclick="window.location.href='{{ url('/companies/create') }}'"
                    class="btn btn-success">Nueva empresa</button> --}}
            @endif
        </div>
        <employee-table/>
    </div>
@endsection
