@extends('layout')

@section('title')
	Login
@stop

@section('content')
	@if (Session::has('message'))
		<div class="alert alert-danger">{{ Session::get('message') }}</div>
	@endif

	{{ Form::open(array('url' => '/signin', 'method' => 'post', 'role'=>'form', 'class'=>'form-horizontal')) }}
		<div class="form-group">
			{{ Form::label('Username', null, array('class'=>'col-sm-2 control-label')) }}
			<div class="col-sm-10">
				{{ Form::text('username', null, array('class'=>'form-control', 'placeholder'=>'Nombre de usuario')) }}
			</div>
		</div>
		<div class="form-group">
			{{ Form::label('Contraseña', null,array('class'=>'col-sm-2 control-label')) }}
			<div class="col-sm-10">
				{{ Form::password('password', array('class'=>'form-control', 'placeholder'=>'Contraseña')) }}
			</div>
		</div>
		<div class="form-group">
			 <div class="col-sm-offset-2 col-sm-10">
				{{ Form::submit('Login', array('name' => 'signin', 'class'=>'btn btn-primary'))}}
			</div>
		</div>
	{{ Form::close() }}
@stop