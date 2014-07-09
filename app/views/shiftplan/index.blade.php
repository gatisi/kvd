@extends('layouts.full')

@section('content')
<div ng-app="createShiftplanApp">

	<!-- Date Picker -->
	<div ng-controller="datePicker">
		<button type="button" class="btn btn-default" ng-click="changeMonth(-1)">
			<span class="glyphicon glyphicon-chevron-left"></span>
		</button>
		<% months[selectedMonth] %>
		<button type="button" class="btn btn-default" ng-click="changeMonth(1)">
			<span class="glyphicon glyphicon-chevron-right"></span>
		</button>
		<button type="button" class="btn btn-default" ng-click="changeYear(-1)">
			<span class="glyphicon glyphicon-chevron-left"></span>
		</button>
		<% selectedYear %>
		<button type="button" class="btn btn-default" ng-click="changeYear(1)">
			<span class="glyphicon glyphicon-chevron-right"></span>
		</button>
	</div> 	

	<!-- Shift Planer -->
	<div ng-controller="shiftPlaner">
		<% datestring %>
	</div>
</div>



@stop

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.0/angular.min.js"></script>
{{HTML::script('js/shiftplan/createShiftplanApp.js')}}
@stop
