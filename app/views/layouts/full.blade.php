<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Grafix</title>

	{{ HTML::style('bower_resources/bootstrap/dist/css/bootstrap.min.css') }}
	

	@yield('stylesheets')

</head>

<body>
	<nav class="navbar navbar-default" role="navigation">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
				</button>
				<a class="navbar-brand" href="{{ URL::to('/') }}">Grafix</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li class="active"><a href="#">New graf</a></li>
					<li><a href="{{URL::action('UserController@getInvite')}}">Invite</a></li>
					<li><a href="{{URL::action('ShiftPatternController@getIndex')}}">Shifrplans</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="{{URL::action('UserController@getLogout')}}">Logout</a></li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>

	<div class="container">
		<div class="content">
			@yield('content')
		</div>
	</div><!-- /.container -->


	
	<!-- Modal -->  
	<div class="modal fade" id="loginFormPopup" tabindex="-1" role="dialog" aria-labelledby="loginForm" aria-hidden="true">  
		<div class="modal-dialog">  
			<div class="modal-content"></div>  
		</div>  
	</div>  
	<!-- /.modal --> 






	{{ HTML::script('bower_resources/jquery/jquery.min.js') }}
	{{ HTML::script('bower_resources/bootstrap/dist/js/bootstrap.min.js') }}


	@yield('scripts')

</body>
</html>
