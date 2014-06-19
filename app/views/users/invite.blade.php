{{Session::get('error_message')}}
{{ Form::open(array('url' => 'users/invite')) }}
	<p>
	{{Form::label('email', 'E-Mail')}}
    {{Form::email('email')}}
    </p>
    <p>
    {{Form::label('message', 'Message');}}
    {{Form::text('message')}}
    </p>   
    <p>
    {{Form::submit('Send');}}
    </p>
{{ Form::close() }}