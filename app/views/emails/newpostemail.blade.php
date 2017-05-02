<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{ $data['username'] }}, there's a new Postrr post.</h2>
		<div>
			View the new post:<a href="http://checkdis.info/comments/show/{{ $data['post'] }}">HERE.</a>
		</div>
	</body>
</html>
