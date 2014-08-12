@extends('layouts.full')

@section('content')

<div ng-app="shiftWishesApp">
<div ng-view></div>
</div>

@stop

@section('stylesheets')
	{{ HTML::style('css/shiftplanerApp.css') }}
@stop


@section('scripts')
<!-- Routs config for JS -->
<script>
	if (!window.grafixapp) {
    window.grafixapp = {};
    window.grafixapp.usersURL = '<?php echo URL::to('users');?>';
    window.grafixapp.shiftplanerURL = '<?php echo URL::to('shiftplaner');?>';
    window.grafixapp.shiftplansURL = '<?php echo URL::to('shiftplans');?>';

}
</script>

{{HTML::script('js/angular/angular.min.js')}}
{{HTML::script('js/angular/angular-route.min.js')}}
{{HTML::script('js/shiftplan/shiftWishesApp.js')}}
{{HTML::script('bower_resources/moment/min/moment.min.js')}}
@stop