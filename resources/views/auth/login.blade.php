@extends('layouts.app')

@section('content')

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center m-auto">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div style="border-right: 0.001rem solid lightgrey;" class="col-lg-6 d-none d-lg-flex">
                                <a href="https://www.validate.cl" style="display:flex;width:100%;"><img
                                        style="margin:auto;width:12rem;position:relative;"
                                        src={{asset('img/logo.png')}} /></a>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Ingresa Validate!</h1>
                                        <span id="error-blood"></span>
                                    </div>

                                    <form id="validatepo" method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="form-group row">
                                            <input id="email" type="email" placeholder="Email"
                                                class="form-control form-control-user @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email') }}" required autocomplete="email"
                                                autofocus>

                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group row">
                                            <input id="password" type="password" placeholder="Contraseña"
                                                class="form-control form-control-user @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="current-password">

                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group row mb-0">
                                            <div class="col-md-12 px-0 text-center">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ __('Login') }}
                                                </button>

                                                @if (Route::has('password.request'))
                                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                                        {{ __('Olvidaste tu contraseña?') }}
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    </div>
@endsection
