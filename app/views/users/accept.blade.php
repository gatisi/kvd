{{Session::get('error_message')}}
{{ Form::open(array('url' => 'users/accept')) }}
    <p>
        Join ???organisations?? shift planing comunity.
    </p>
    <p>{{$invite->message}}</p>
    <p>
    {{Form::label('firstname', 'Name');}}
    {{Form::text('firstname')}}
    </p>    
    <p>
    {{Form::label('lastname', 'Lastname');}}
    {{Form::text('lastname')}}
    </p>
    <p>
    {{Form::label('password', 'Password');}}
    {{Form::password('password')}}
    </p>
    {{Form::hidden('email', $invite->email)}}
    {{Form::hidden('code', $code)}}
    <p>
    {{Form::submit('Login');}}
    </p>
{{ Form::close() }}