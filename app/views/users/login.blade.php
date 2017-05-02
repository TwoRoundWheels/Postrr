{{ Form::open(array('url' =>'users/signin' ,'class'=>'form-signin' )) }}
	<h2 class="white-text">Please Login</h2>
	<div class="container">
		@if(Session::has('message'))
			<p class="errors">{{ Session::get('message') }}</p>
		@endif
	</div>
	{{ Form::text('email', null, array('class'=>'form-control','placeholder'=>'Email Address')) }}
	{{ Form::password('password', array('class'=>'form-control','placeholder'=>'Password')) }}
	{{ Form::submit('Login', array('class'=>'btn btn-large btn-primary btn-block')) }}
	{{ Form::label('rememberme', 'Remember Me', array('class'=>'white-text'))}}
	{{ Form::checkbox('remember_token',  1, true) }}
	{{ HTML::linkAction('RemindersController@getRemind', 'Forgot your password?') }}
{{ Form::close() }}