@extends('layout')

@section('title')
	Login con Oauth Binter
@stop

@section('content')
	@if ($url=='http://app1.dev')
		<a href="http://oauth.bintercanarias.dev/oauth?client_id=1&redirect_uri=http://app1.dev/login/1&response_type=code&scope=user&state=xyz" class="btn btn-danger" role="button">Login</a>
	@elseif ($url=='http://app2.dev')
		<a href="http://oauth.bintercanarias.dev/oauth?client_id=2&redirect_uri=http://app2.dev/login/2&response_type=code&scope=user&state=xyz" class="btn btn-info" role="button">Login</a>
	@elseif ($url=='http://app3.dev')
		<a href="http://oauth.bintercanarias.dev/oauth?client_id=3&redirect_uri=http://app3.dev/login/3&response_type=code&scope=user&state=xyz" class="btn btn-warning" role="button">Login</a>
	@else
		No puedes hacer login desde aquí.
	@endif
@stop