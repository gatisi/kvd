

{{ Form::open(array('url' => 'users/login', 'role'=>'form', 'data-target'=>'.modal-content', 'data-async')) }}
<span class="help-block">{{$error_message}}</span>
<div class="form-group">
	<label for="email">Email address</label>
	{{Form::email('email')}}
</div>
<div class="form-group">
	<label for="password">Password</label>
	{{Form::password('password')}}
</div>
<button type="submit" class="btn btn-default">Submit</button>
{{ Form::close() }}

{{ HTML::script('js/LoginAJAX.js') }}