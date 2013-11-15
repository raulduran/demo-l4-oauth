@extends('layout')

@section('title')
	Home
@stop

@section('content')
	<p>Esto es una zona segura</p>
	@if ($url=='http://app1.dev')
		<a href="http://oauth.bintercanarias.dev/logout/{{Cookie::get('access_token')}}" class="btn btn-danger" role="button">Salir</a> de App1
	@elseif ($url=='http://app2.dev')
		<a href="http://oauth.bintercanarias.dev/logout/{{Cookie::get('access_token')}}" class="btn btn-info" role="button">Salir</a> de App2
	@elseif ($url=='http://app3.dev')
		<a href="http://oauth.bintercanarias.dev/logout/{{Cookie::get('access_token')}}" class="btn btn-warning" role="button">Salir</a> de App3
	@else
		No puedes salir
	@endif	
@stop