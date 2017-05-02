<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{ $data['username'] }}, there's a new Postrr comment!</h2>
		<div>
			Click the link to view the new comment: <a href="http://checkdis.info/comments/show/{{ $data['post'] }}">HERE.</a>
		</div>
	</body>
</html>
