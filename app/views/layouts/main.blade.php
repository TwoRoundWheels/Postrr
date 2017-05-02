<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		{{ HTML::script("http://code.jquery.com/jquery-2.0.3.min.js") }}
		{{ HTML::script("js/XCSRF.js") }}
		{{ HTML::script("css/bootstrap-3.2.0-dist/js/bootstrap.js") }} 
		{{ HTML::style('css/bootstrap-3.2.0-dist/css/bootstrap.min.css') }}
		{{ HTML::style('css/main.css') }}	
		{{ HTML::style('css/bootstrap-custom.css') }}
		@if (Auth::check()) 
		<title>Postrr -- Share pictures, videos, and links with people.</title>	
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed navbar-right" data-toggle="collapse" data-target="#navbar-collapse">	
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="collapse navbar-collapse" id="navbar-collapse">
					<ul class="nav navbar-nav">
						<li id="homeButton"> {{ HTML::link('posts/index', 'HOME') }} </li>
						<li id="postButton"> {{ HTML::link('posts/post-new', 'POST') }} </li>
						<li id="settingsButton"> {{ HTML::link('users/settings', 'SETTINGS') }} </li>	
					</ul>
					<div class="navbar-right">
						<p class="navbar-text">Signed in as: {{ HTML::linkAction('UsersController@getProfileOfPosts', Auth::user()->username, array(Auth::id())) }}</p>
						<ul class="nav navbar-nav">	
							<li>{{ HTML::link('users/logout','LOGOUT') }}</li>
						</ul>
					</div>
				</div> {{--navbar-collapse--}}
			</div> {{-- /.container-fluid--}}
		</nav>
		@endif	
	</head>	
	<body>		
		{{ $content }}
	</body>
</html>


