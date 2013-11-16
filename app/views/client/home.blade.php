@extends('layout')

@section('title')
	Demo Client Home
@stop

@section('content')
	<p>Esto es una zona segura</p>
	@if ($url=='http://app1.dev')
		<a href="{{Config::get('app.server_url')}}logout/1" class="btn btn-danger" role="button">Salir</a> de App1
	@elseif ($url=='http://app2.dev')
		<a href="{{Config::get('app.server_url')}}logout/2" class="btn btn-info" role="button">Salir</a> de App2
	@elseif ($url=='http://app3.dev')
		<a href="{{Config::get('app.server_url')}}logout/3" class="btn btn-warning" role="button">Salir</a> de App3
	@else
		No puedes salir
	@endif	
@stop