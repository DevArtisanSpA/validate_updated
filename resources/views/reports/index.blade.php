@extends('layouts.app')

@section('content')
    <report-by-company 
        :data="{{$data}}"
    ></report-by-company>
@endsection