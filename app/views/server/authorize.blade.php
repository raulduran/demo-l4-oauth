@extends('layout')

@section('title')
	Authorise {{$params['client_details']['name']}}
@stop

@section('content')

<p>La aplicación <strong>{{$params['client_details']['name']}}</strong> debería tener los siguientes permisos para acceder:</p>

<ul>
	@foreach ($params['scopes'] as $scope)
	<li>
		{{$scope['name']}}
	</li>
	@endforeach
</ul>

<p>
{{ Form::open(array('url' => '/authorize', 'method' => 'post', 'role'=>'form', 'class'=>'form-horizontal')) }}
	{{ Form::submit('Aprobar', array('name' => 'approve', 'class'=>'btn btn-success'))}}
	{{ Form::submit('Denegar', array('name' => 'deny', 'class'=>'btn btn-danger'))}}
{{ Form::close() }}

@stop