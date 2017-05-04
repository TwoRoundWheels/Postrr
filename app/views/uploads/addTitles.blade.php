<h1 class="text-center white-text">Enter Some Titles and Descriptions</h1>
<h3 class="text-center white-text">Leaving them blank is ok too!</h3>
<h6 class="text-center white-text">Seriously, it's ok.</h6>
<div class="container">
	<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-10 col-lg-offset-1">
		@if(Session::has('message'))
			<div class="well well-sm">
				<h4 class="errors">{{ Session::get('message') }}</h4>
				@foreach ($errors->all() as $error) 
					<li class='errors'> {{$error}} </li>
				@endforeach
			</div>
		@endif

		{{ Form::open(array('url'=>'uploads/save-album', 'class'=>'create-album', 'id'=>'Album')) }}
		@foreach ($attachments as $attachment)
			<div class="well well-lg">
				{{ Form::label('title'.'-'.$attachment->id, 'Title :') }}
				{{ Form::text('title'.'-'.$attachment->id, null, array('class'=>'form-control', 'placeholder'=>'Give your file a title if you\'d like.')) }}
					@if (starts_with($attachment->mimetype, "image/"))
						<h4 class="text-center">{{{ $attachment->original_name }}}</h4>
						<img src={{URL::asset('/uploads/'.$attachment->filename_saved_as)}} alt="{{ $attachment->original_name }}" class="img-responsive img-rounded upload-image">
					@elseif (starts_with($attachment->mimetype, "video/"))
						<div align="center" class="embed-responsive embed-responsive-16by9">
							<h4 class="text-center">{{{ $attachment->original_name }}}</h4>
	    					<video class="embed-responsive-item" controls="controls"  preload="auto" alt="{{ $attachment->original_name }}">
	        					<source src={{ URL::asset('/uploads/'.$attachment->filename_saved_as) }} type={{ $attachment->mimetype }} />
	    					</video>
						</div>	
					@elseif (starts_with($attachment->mimetype, "audio/"))
						<div align="center">
							<h4 class="text-center">{{{ $attachment->original_name }}}</h4>
							<audio controls="controls"  preload="none">
								<source src={{ URL::asset('/uploads/'.$attachment->filename_saved_as) }} type={{ $attachment->mimetype }} />
							</audio>
						</div>
					@else 
						<h4 class="text-center">{{{ $attachment->original_name }}}</h4>
						<img src={{URL::asset('/images/FileIcon.png')}} alt="{{ $attachment->original_name }}" class="img-responsive img-rounded upload-image">	
					@endif
				{{ Form::label('description'.'-'.$attachment->id, 'Description') }}
				{{ Form::textarea('description'.'-'.$attachment->id, null, array('size'=>'30x5', 'class'=>'form-control', 'placeholder'=>'If you\'d like, type a description or some other words here. (Keep it under 2000 characters, ok? ;)')) }}	
			</div> <!--/.well-->
		@endforeach
			{{ Form::hidden('post_id', $post->id) }}
			<div id="title-submit" class="col-sm-8 col-sm-offset-4 col-md-8 col-md-offset-4 col-lg-10 col-lg-offset-2">
				{{ Form::submit('Submit', array('class'=>'btn btn-large btn-primary pull-right')) }}
			</div>
		{{ Form::close() }}
		<br>
		<br>
	</div> <!--/.container-->
</div> <!--/.col-->
{{ HTML::Script("/js/postHighlight.js") }}
