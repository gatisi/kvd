@extends('layouts.full')

@section('content')

<div ng-app="createShiftplanApp">
<div ng-view></div>
</div>



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
{{HTML::script('js/shiftplan/createShiftplanApp.js')}}

@stop
