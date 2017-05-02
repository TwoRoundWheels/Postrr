

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Postrr - Change Password</title>
{{ HTML::style("css/bootstrap-3.2.0-dist/css/bootstrap.min.css") }}
{{ HTML::style("css/main.css") }}
<div class='container'>
	<h2>Change Your Password</h2>
	<div class="container">
		@if(Session::has('error'))
			<h4 class="errors">{{ Session::get('error') }}</h4>
		@endif	
	</div>
	{{ Form::open(array('action'=>'RemindersController@postReset', 'role'=>'form')) }}
		{{ Form::hidden('token', $token)}}
		{{ Form::label('email', 'Email:  ') }}
		{{ Form::text('email', null, array('class'=>'form-control', 'placeholder'=>'Email')) }}
		{{ Form::label('password', 'New Password:  ') }}
		{{ Form::password('password', null, array('class'=>'form-control', 'placeholder'=>'Letters and Numbers, 6-18 characters long.')) }}
		{{ Form::label('password_confirmation', 'Confirm New Password:  ') }}
		{{ Form::password('password_confirmation', null, array('class'=>'form-control', 'placeholder'=>'Letters and Numbers, 6-18 characters long.')) }}
		{{ Form::submit('Change Password', array('class'=>'btn btn-large btn-primary btn-block')) }}
	{{ Form::close() }}
</div>
