<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title class="text-center">Postrr -- Change Password.</title>
{{ HTML::style("css/bootstrap-3.2.0-dist/css/bootstrap.min.css") }}
{{ HTML::style("css/main.css") }}
<h2 class="text-center">Password Reset</h2>
<h4 class="text-center">Enter your email and a reset link will be sent to you</h4>
<div class="container">
	<form action="{{ action('RemindersController@postRemind') }}" method="POST">
	    <input class="form-control" type="email" name="email" placeholder="Email">
	    <input class="form-control btn btn-block btn-primary" type="submit" value="Send Reminder">
	</form>
</div>