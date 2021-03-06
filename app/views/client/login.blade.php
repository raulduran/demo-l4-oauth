@extends('layout')

@section('title')
	Demo Client Login
@stop

@section('content')
	@if ($url=='http://app1.dev')
		<a href="{{Config::get('app.server_url')}}oauth?client_id=1&redirect_uri=http://app1.dev/login/1&response_type=code&scope=user&state={{$state}}" class="btn btn-danger" role="button">Login</a>
	@elseif ($url=='http://app2.dev')
		<a href="{{Config::get('app.server_url')}}oauth?client_id=2&redirect_uri=http://app2.dev/login/2&response_type=code&scope=user&state={{$state}}" class="btn btn-info" role="button">Login</a>
	@elseif ($url=='http://app3.dev')
		<a href="{{Config::get('app.server_url')}}oauth?client_id=3&redirect_uri=http://app3.dev/login/3&response_type=code&scope=user&state={{$state}}" class="btn btn-warning" role="button">Login</a>
	@else
		No puedes hacer login desde aquí.
	@endif
@stop