<div class="container">
	<div class="well well-lg col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
		{{ Form::open(array('url'=>'posts/update', 'class'=>'form-post')) }}
			<h2 class=>Make Changes To Your Post.</h2>
			<div class="container">
				@if(Session::has('message'))
					<p class="alert">{{ Session::get('message') }}</p>
					@foreach ($errors->all() as $error) 
						<li> {{$error}} </li>
					@endforeach
				@endif
			</div>
			{{ Form::label('title', 'Title :') }}
			{{ Form::text('title', $post->title, array('class'=>'form-control')) }}
			{{ Form::label('link', 'URL :') }}
			{{ Form::text('link', $post->link, array('class'=>'form-control')) }}
			{{ Form::label('description', 'Description') }}
			{{ Form::textarea('description', $post->description, array('class'=>'form-control')) }}
			{{ Form::label('nsfw', 'NSFW?') }}
			@if($post->nsfw == 1)
				{{ Form::checkbox('nsfw', '1', true) }}
			@else
				{{ Form::checkbox('nsfw', '1', null) }}
			@endif
			{{ Form::label('video', 'VIDEO?') }}
			@if($post->video == 1)
				{{ Form::checkbox('video', '1', true) }}
			@else 
				{{ Form::checkbox('video', '1', null) }}
			@endif
			{{ Form::hidden('post_id', $post->id) }}
			{{ Form::submit('Submit', array('class'=>'btn btn-large btn-primary pull-right top-space')) }}
		{{ Form::close() }}
	</div>
</div> 

