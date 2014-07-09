@extends('layouts.full')

@section('content')

{{Session::get('error_message')}}

<div class="row">

    <div class="col-md-4">
    <h2>Create your account</h2>
        Join ???organisations?? shift planing comunity.
        <p>{{$invite->message}}</p>
    </div>

    <div class="col-md-8">
        {{ Form::open(array('url' => 'users/accept/', 'role' => 'form')) }}
        <div class="form-group">
            {{Form::label('firstname', 'First Name');}}
            {{Form::text('firstname', null, array( 'placeholder'=>'John', 'class'=>'form-control '))}}
        </div>    
        <div class="form-group">
            {{Form::label('lastname', 'Last Name');}}
            {{Form::text('lastname', null, array('placeholder'=>'Smith', 'class'=>'form-control '))}}
        </div>
        <div class="form-group">
            {{Form::label('password', 'Password');}}
            {{Form::password('password', array('class'=>'form-control '))}}
        </div>
        {{Form::hidden('email', $invite->email)}}
        {{Form::hidden('code', $code)}}
        {{Form::hidden('invite', $invite->id)}}
        <div class="form-group">
            {{Form::submit('Login', array('class'=>'form-control btn btn-success'));}}
        </div>
        {{ Form::close() }}
    </div>
</div>

@stop

@section('scripts')

@stop