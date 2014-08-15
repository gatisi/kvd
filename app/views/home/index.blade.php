@extends('layouts.full')

@section('content')
<div ng-app="homeApp">
	<div ng-controller="acceptNewPlans">
		<div ng-repeat="plan in newPlans">
			<h2><%plan.name%></h2>
			<button ng-click="acceptPlan(plan.id)">Accept</button>
			<button ng-click="rejectPlan(plan.id)">Reject</button>
		</div>
	</div>
</div>
@stop






@section('stylesheets')
@stop
@section('scripts')
{{HTML::script('js/angular/angular.min.js')}}
{{HTML::script('js/shiftplan/HomeApp.js')}}
<!-- Routs config for JS -->
<script>
	if (!window.grafixapp) {
    window.grafixapp = {};
    window.grafixapp.usersURL = '<?php echo URL::to('users');?>';
    window.grafixapp.shiftplanerURL = '<?php echo URL::to('shiftplaner');?>';
    window.grafixapp.shiftplansURL = '<?php echo URL::to('shiftplans');?>';
    window.grafixapp.shiftpatternURL = '<?php echo URL::to('shiftplan');?>';

}
</script>
@stop