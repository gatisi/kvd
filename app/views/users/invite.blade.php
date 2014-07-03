@extends('layouts.full')

@section('content')

{{ Form::open(array('url' => 'users/invite', 'role'=>'form', 'class'=>'multiply_input')) }}
<div class="container-fluid">
    <div class="row">
        <div class="form-group container-fluid">
            <div class="col-md-2 pull-right">
                {{Form::submit('Send', array('class'=>'form-control btn btn-success'));}}
            </div>     
            <div class="col-md-2 pull-right multiply_trigger">
                {{Form::button('Add more invites', array('class'=>'form-control btn btn-success btn-info'));}}
            </div>
        </div>
    </div>
    <div class="multiply-target">
        <div class="row multiply_fields">
            <div class="form-group  col-md-4">
            {{Form::label('email[]', 'E-Mail', array('class'=>'sr-only'))}}
                {{Form::email('email[]', null, array('placeholder'=>'Enter email', 'class'=>'form-control '))}}
            </div>
            <div class="form-group col-md-4">
                {{Form::label('first_name[]', 'First name', array('class'=>'sr-only'));}}
                {{Form::text('first_name[]', null, array('placeholder'=>'First name', 'class'=>'form-control'))}}
            </div>
            <div class="form-group col-md-4">
                {{Form::label('last_name[]', 'Last name', array('class'=>'sr-only'));}}
                {{Form::text('last_name[]', null, array('placeholder'=>'Last name', 'class'=>'form-control'))}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            {{Form::label('message', 'Message', array('class'=>'sr-only'));}}
            {{Form::text('message', null, array('placeholder'=>'Message', 'class'=>'form-control'))}}
        </div>
    </div>
    <div class="row">
        <div class="form-group container-fluid">
            <div class="col-md-2 pull-right">
                {{Form::submit('Send', array('class'=>'form-control btn btn-success'));}}
            </div>     
            <div class="col-md-2 pull-right multiply_trigger">
                {{Form::button('Add more invites', array('class'=>'form-control btn btn-success btn-info'));}}
            </div>
        </div>
    </div>

</div>
{{ Form::close() }}

@stop

@section('scripts')
{{ HTML::script('js/users/inviteFormMultiply.js') }}
@stop