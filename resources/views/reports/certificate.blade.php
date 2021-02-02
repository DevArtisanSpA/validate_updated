@extends('layouts.app')

@section('content')
    <generate-certificate
        :data="{{$data}}"
    ></generate-certificate>
@endsection