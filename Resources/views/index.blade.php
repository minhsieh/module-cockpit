@extends('cockpit::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>This view is loaded from module: {!! config('cockpit.name') !!}</p>
    <p>Hi {{ Auth::user()->name }}</p>
    <p>Role: {{  Auth::user()->getRoleNames()->implode(',') }}</p>
    
@stop
