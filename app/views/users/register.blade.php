@extends('layouts.guest')

@section('content')
{{Session::get('error_message')}}
{{ Form::open(array('url' => 'users/register')) }}
    <p>
    {{Form::label('email', 'E-Mail')}}
    {{Form::email('email')}}
    </p>
    <p>
    {{Form::label('firstname', 'Name');}}
    {{Form::text('firstname')}}
    </p>    
    <p>
    {{Form::label('lastname', 'Lastname');}}
    {{Form::text('lastname')}}
    </p>
    <p>
    {{Form::label('organization', 'Organization')}}
    {{Form::text('organization')}}
    </p>
    <p>
    {{Form::label('password', 'Password');}}
    {{Form::password('password')}}
    </p>
    <p>
    {{Form::submit('Login');}}
    </p>
{{ Form::close() }}
@stop

@section('scripts')

@stop







