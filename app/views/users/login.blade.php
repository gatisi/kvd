{{Session::get('error_message')}}
{{ Form::open(array('url' => 'users/login')) }}
    {{Form::email('email')}}
    {{Form::password('password')}}
    {{Form::submit('Login');}}
{{ Form::close() }}