{{ Form::open(array('url' => 'users/login', 'role'=>'form', 'data-target'=>'.modal-content', 'data-async')) }}

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Log in</h4>
</div>

<div class="modal-body">
	<span class="help-block">{{$error_message}}</span>
	<div class="form-group">
		<label for="email">Email address</label>
		{{Form::email('email', null, array('placeholder'=>'', 'class'=>'form-control '))}}
	</div>
	<div class="form-group">
		<label for="password">Password</label>
		{{Form::password('password', array('class'=>'form-control '))}}
	</div>
</div>

<div class="modal-footer">
	<button type="submit" class="btn btn-default btn-success">Submit</button>
	<button type="button" class="btn btn-default btn-danger" data-dismiss="modal">Close</button>
</div>

{{ Form::close() }}
{{ HTML::script('js/LoginAJAX.js') }}