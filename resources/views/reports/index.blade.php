@extends('layouts.app')

@section('content')
    <report-by-company 
        :data="{{$data}}"
    ></-table>
@endsection