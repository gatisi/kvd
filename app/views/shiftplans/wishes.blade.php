@extends('layouts.full')

@section('content')

<div ng-app="shiftWishesApp">
	<div ng-view></div>
</div>

@stop

@section('stylesheets')
{{ HTML::style('css/shiftplanerApp.css') }}
<style type="text/css">
	@-moz-document url-prefix() {
		fieldset { display: table-cell; }
	}

	.even{
		background-color: #ded;
	}
	.weekend{
		background-color: #f00;
	}
	.shift-time{
    -ms-transform: rotate(-90deg); /* IE 9 */
    -webkit-transform: rotate(-90deg); /* Chrome, Safari, Opera */
    transform: rotate(-90deg);

	}
	.table-responsive
{
    overflow-x: auto;
}

.table-responsive { width: 100%; overflow-y: hidden; overflow-x: scroll; -ms-overflow-style: -ms-autohiding-scrollbar; -webkit-overflow-scrolling: touch; } 

</style>

@stop


@section('scripts')
<!-- Routs config for JS -->
<script>
	if (!window.grafixapp) {
		window.grafixapp = {};
		window.grafixapp.usersURL = '<?php echo URL::to('users');?>';
		window.grafixapp.shiftplanerURL = '<?php echo URL::to('shiftplaner');?>';
		window.grafixapp.shiftplansURL = '<?php echo URL::to('shiftplans');?>';
		window.grafixapp.user = '<?php echo $user_unsecure?>';

	}
</script>

{{HTML::script('js/angular/angular.min.js')}}
{{HTML::script('js/angular/angular-route.min.js')}}
{{HTML::script('js/shiftplan/shiftWishesApp.js')}}
{{HTML::script('bower_resources/moment/min/moment.min.js')}}
@stop