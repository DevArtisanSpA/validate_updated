@extends('layouts.app')

@section('content')
<div class="col-md-9">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card border-dark w-75 mx-auto">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted text-center">Cantidad de empresas</h6>
                    <h4 class="card-title text-center">{{ $total_companies }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-dark w-75 mx-auto">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted text-center">Cantidad de empleados</h6>
                    <h4 class="card-title text-center">{{ $total_employees }}</h4>
                </div>
            </div>
        </div>
        @if(Auth::user()->user_type_id == 1)
        <div class="col-md-4">
            <div class="card border-dark w-75 mx-auto">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted text-center">Cantidad de usuarios</h6>
                    <h4 class="card-title text-center">{{ $total_users }}</h4>
                </div>
            </div>
        </div>
        @endif
    </div>
    <hr class="mt-5 mb-3">
    <div class="row">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body row mb-0">
                    <div class="col-md-8">
                        <h4 class="card-title">Consultar estado de pago</h4>
                        <p class="card-subtitle mt-2 mb-4 text-muted">de tus contratistas</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <a class="btn btn-success pt-2 my-1" href="{{url('/review/general')}}">
                            <span>Ver lista</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @if(Auth::user()->user_type_id != 1)
            <div class="col-md-6">
                <employee-search />
            </div>
        @endif
    </div>
    <hr class="my-3">
    <h4 class="mb-3">Empresa</h4>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body row mb-0">
                    <div class="col-md-8">
                        <h4 class="card-title">Documentos base</h4>
                    </div>
                    <div class="col-md-4 text-center">
                        <a class="btn btn-success pt-2 my-1" href="{{url('/documents/company/fixed')}}">
                            <span>Ver lista</span>
                        </a>
                        @if(Auth::user()->user_type_id == 1)
                        <a class="btn btn-primary pt-2 my-1" href="{{url('/review/company/fixed')}}">
                            <span>Validar</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body row mb-0">
                    <div class="col-md-8">
                        <h4 class="card-title">Documentos mensuales</h4>
                    </div>
                    <div class="col-md-4 text-center">
                        <a class="btn btn-success pt-2 my-1" href="{{url('/documents/company/monthly')}}">
                            <span>Ver lista</span>
                        </a>
                        @if(Auth::user()->user_type_id == 1)
                        <a class="btn btn-primary pt-2 my-1" href="{{url('/review/company/monthly')}}">
                            <span>Validar</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="my-3">
    <h4 class="mb-3">Empleados</h4>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body row mb-0">
                    <div class="col-md-8">
                        <h4 class="card-title">Documentos base</h4>
                    </div>
                    <div class="col-md-4 text-center">
                        <a class="btn btn-success pt-2 my-1" href="{{url('/documents/employee/fixed')}}">
                            <span>Ver lista</span>
                        </a>
                        @if(Auth::user()->user_type_id == 1)
                        <a class="btn btn-primary pt-2 my-1" href="{{url('/review/employee/fixed')}}">
                            <span>Validar</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body row mb-0">
                    <div class="col-md-8">
                        <h4 class="card-title">Documentos mensuales</h4>
                    </div>
                    <div class="col-md-4 text-center">
                        <a class="btn btn-success pt-2 my-1" href="{{url('/documents/employee/monthly')}}">
                            <span>Ver lista</span>
                        </a>
                        @if(Auth::user()->user_type_id == 1)
                        <a class="btn btn-primary pt-2 my-1" href="{{url('/review/employee/monthly')}}">
                            <span>Validar</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection