@extends('layout')

@section('title')
	Home
@stop

@section('content')
	<p>Esto es una zona segura</p>
	@if ($url=='http://app1.dev')
		<a href="http://oauth.bintercanarias.dev/logout/1" class="btn btn-danger" role="button">Salir</a> de App1
	@elseif ($url=='http://app2.dev')
		<a href="http://oauth.bintercanarias.dev/logout/2" class="btn btn-info" role="button">Salir</a> de App2
	@elseif ($url=='http://app3.dev')
		<a href="http://oauth.bintercanarias.dev/logout/3" class="btn btn-warning" role="button">Salir</a> de App3
	@else
		No puedes salir
	@endif	
@stop