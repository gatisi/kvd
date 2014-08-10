@extends('layouts.full')

@section('content')

<div ng-app="shiftplanerApp">
<div ng-view></div>
</div>



@stop

@section('stylesheets')
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
{{HTML::script('js/shiftplan/shiftplanerApp.js')}}

@stop