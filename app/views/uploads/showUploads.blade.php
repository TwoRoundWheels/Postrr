
<div class="container">
	@if(Session::has('message'))
		<h4 class="errors">{{ Session::get('message') }}</h4>
		@foreach ($errors->all() as $error) 
			<li class="errors"> {{$error}} </li>
		@endforeach
	@endif
</div>
<div class="container">
	<div class="panel">
		<div class="panel-heading">	
			<h1 class="panel-title">{{{ $post->title }}}
				@if ($post->nsfw == 1) 
					<span class='label label-danger'>NSFW</span>
				@endif
				@if ($post->video == 1)
					<span class='label label-warning'>VIDEO</span>
				@endif
			</h1>
		</div>
		<div class="panel-body post-text">
			<p>Submitted by:  {{ HTML::linkAction('UsersController@getProfileOfPosts', $post->user->username, array($post->user->id)) }} on {{ date("F d, Y",strtotime($post->created_at)) }} at {{ date("g:ha",strtotime($post->created_at)) }}. </p>
		</div>		
	</div>
</div>
<div>
	<div class="container">
		@foreach ($attachments->all() as $attachment)
			<div class="well well-lg clearfix">
				@if ($attachment->title)
					<h3 class="text-center">{{{ $attachment->title }}}</h3>
					@if (Auth::id()==$attachment->user_id) 
						<div>
							<div class="btn-group btn-group-sm pull-right">
								<a data-toggle="collapse" href="#Title-{{$attachment->id}}" class="btn btn-warning">Edit</a>
						  	</div> <!--/.btn-group-->
							{{--DIV opens form when 'edit' button is clicked, to allow inline title editing--}}
							<div class="collapse" id="Title-{{$attachment->id}}">
								{{ Form::open(array('url'=>'uploads/edit', 'class'=>'form-comment')) }}	
								{{ Form::text('title', $attachment->title, array('class'=>'form-control')) }}
								{{ Form::hidden('id', $attachment->id ) }}
								{{ Form::submit('Save', array('class'=>'btn btn-small btn-primary pull-right text-right')) }}
								{{ Form::close() }} 
							</div>
						</div>
					@endif
				@endif			
				@if (starts_with($attachment->mimetype, "image/"))
					<img src={{URL::asset('/uploads/'.$attachment->filename_saved_as)}} alt="{{{ $attachment->original_name }}}" class="img-responsive img-rounded upload-image">
				@elseif (starts_with($attachment->mimetype, "video/"))
					<div align="center" class="embed-responsive embed-responsive-16by9">
						<h4>{{{ $attachment->original_name }}}</h4>
    					<video class="embed-responsive-item" controls="controls"  preload="auto" alt="{{{ $attachment->original_name }}}">
        					<source src={{ URL::asset('/uploads/'.$attachment->filename_saved_as) }} type={{ $attachment->mimetype }} />
        					Your browser does cannot play this filetype.
    					</video>
					</div>
				@elseif (starts_with($attachment->mimetype, "audio/"))
					<div align="center">
						<h4>{{{ $attachment->original_name }}}</h4>
						<audio controls="controls"  preload="none">
							<source src={{ URL::asset('/uploads/'.$attachment->filename_saved_as) }} type={{ $attachment->mimetype }} />
						</audio>
					</div>
				@else
					<h3 class="text-center"> {{{ $attachment->original_name }}} </h3>
				@endif	
				@if (Auth::id()==$attachment->user_id) 
					<div>
						<div class="btn-group btn-group-sm pull-right">
							<a data-toggle="collapse" href="#Description-{{$attachment->id}}" class="btn btn-warning">Edit</a>
					  	</div> 
						{{--DIV opens form when 'edit' button is clicked, to allow inline title editing--}}
						<div class="collapse" id="Description-{{$attachment->id}}">
							{{ Form::open(array('url'=>'uploads/edit', 'class'=>'form-comment')) }}	
							{{ Form::textarea('description', $attachment->description, array('class'=>'form-control')) }}
							{{ Form::hidden('id', $attachment->id ) }}
							{{ Form::submit('Save', array('class'=>'btn btn-small btn-primary text-right pull-right')) }}
							{{ Form::close() }} 
						</div>
					</div>
				@endif	
				@if ($attachment->description)	
					<p class="text-center">{{ nl2br(e($attachment->description)) }}</p>					
				@endif
				<a class="btn btn-sm btn-primary glyphicon glyphicon-download pull-right" href="../download/{{ $attachment->filename_saved_as }}" method="GET"></a>
			</div><!-- /.well -->
		@endforeach
	</div> <!-- /.container-->
</div> 