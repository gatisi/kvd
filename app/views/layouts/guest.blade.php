<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Shiftplaner</title>

		{{ HTML::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css') }}
		{{ HTML::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css') }}

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
					<li class="active"><a href="#">Home</a></li>
					<li><a href="#">About</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="{{action('UserController@getLogin')}}" data-toggle="modal" data-target="#loginFormPopup" >Login</a>
					</li>
					<li><a href="{{URL::action('UserController@getRegister')}}">Register</a></li>
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
			<div class="modal-content">
				
			</div>  
		</div>  
	</div>  
	<!-- /.modal --> 





	{{ HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js') }}
	{{ HTML::script('http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js') }}
</body>
</html>