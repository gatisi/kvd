@extends('layouts.full')

@section('content')

<div ng-app="createShiftpatternApp">
<div ng-view></div>
</div>



@stop

@section('stylesheets')
{{ HTML::style('bower_resources/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}
@stop


@section('scripts')
<!-- Routs config for JS -->
<script>
	if (!window.grafixapp) {
    window.grafixapp = {};
    window.grafixapp.usersURL = '<?php echo URL::to('users');?>';

}
</script>

{{HTML::script('js/angular/angular.min.js')}}
{{HTML::script('js/angular/angular-route.min.js')}}
{{HTML::script('js/shiftplan/createShiftpatternApp.js')}}
{{HTML::script('bower_resources/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}

@stop
