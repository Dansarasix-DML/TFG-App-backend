@extends('master')

@section('pageTitle', 'Login')

@stop
    
@section('main-content')
    <div id="app" data-errors="{{$errors}}">
    </div>
    @php
        var_dump(Session::all())
    @endphp
@stop