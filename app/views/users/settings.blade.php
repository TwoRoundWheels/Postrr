<div class="container">
	<div class="well well-lg col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2 clearfix">
		{{ Form::open(array('url'=>'users/settings-update', 'class'=>'form-settings')) }}
		<h2 class='form-settings-header'>Change your settings and click Save</h2>
		<div>
			@if(Session::has('usermessage'))
				<h4 class="errors">{{ Session::get('usermessage') }}</h4>
				@foreach ($errors->all() as $error) 
					<li class="errors">{{ $error }}</li>
				@endforeach
			@endif
		</div><br>
			{{ Form::label('username', 'Your Username:  ')}}
			{{ Form::text('username', Auth::user()->username, array('class'=>'form-control')) }}
			{{ Form::label('emailnewpost', 'Send me an e-mail when there is a new post.  ') }}
			@if (Auth::user()->email_new_post) 
				{{ Form::checkbox('emailnewpost', 1, true) }}
			@else 
				{{ Form::checkbox('emailnewpost', 1, null) }}
			@endif
			{{ Form::label('emailnewcomment', 'Send me an e-mail when there is a new comment.  ') }}
			@if (Auth::user()->email_new_comment) 
				{{ Form::checkbox('emailnewcomment', 1, true) }}
			@else 
				{{ Form::checkbox('emailnewcomment', 1, null) }}
			@endif
			<br>
			{{ Form::submit('Save', array('class'=>'btn btn-large btn-primary pull-right')) }}
		{{ Form::close() }} 
		{{ Form::open(array('url'=>'users/change-password', 'class'=>'form-changepassword')) }}
			<h2 class='form-settings-header'>Change Your Password</h2>
			<div class="container">
				@if(Session::has('passwordmessage'))
					<h4 class="errors">{{ Session::get('passwordmessage') }}</h4>
					<ul>
						@foreach ($errors->all() as $error)
							<li class='errors'>{{ $error }}</li>
						@endforeach
					</ul>
				@endif
			</div>
			{{ Form::label('oldPassword', 'Current Password:  ') }}
			{{ Form::password('oldPassword', array('class'=>'form-control', 'placeholder'=>'Password')) }}
			{{ Form::label('password', 'New Password:  ') }}
			{{ Form::password('password', array('class'=>'form-control', 'placeholder'=>'Letters and Numbers, 6-18 characters long.')) }}
			{{ Form::label('password_confirmation', 'Confirm New Password:  ') }}
			{{ Form::password('password_confirmation', array('class'=>'form-control', 'placeholder'=>'Letters and Numbers, 6-18 characters long.')) }}
			{{ Form::submit('Change Password', array('class'=>'btn btn-large btn-primary pull-right top-space')) }}
		{{ Form::close() }}
	</div>
</div>
{{ HTML::Script("/js/settingsHighlight.js") }}


