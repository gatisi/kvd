@extends('layouts.full')

@section('content')
<div ng-app="createShiftplanApp">
<% aaa%>
<div ng-view></div>
</div>



@stop

@section('scripts')

{{HTML::script('js/angular/angular.min.js')}}
{{HTML::script('js/angular/angular-route.min.js')}}
{{HTML::script('js/shiftplan/createShiftplanApp.js')}}

@stop
