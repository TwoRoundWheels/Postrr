<div class="container">
	<div class="well well-lg col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2 clearfix">
		<h1 class="text-center">If you'd like to, change the order your files will be displayed.</h1>
		<h3 class="text-center">Descriptions and titles can be added on the next page.</h3>
		@if(Session::has('message'))
			<h4 class="errors">{{ Session::get('message') }}</h4>
			@foreach ($errors->all() as $error) 
				<li class="errors"> {{$error}} </li>
			@endforeach
		@endif
		<?php $i = 0 ?>
		@foreach ($attachments as $attachment)
			<div id={{ $attachment->display_order }} class="item-container text-center">
				<input name="id" type="hidden" value="{{ $attachment->id }}"> 	
				@if (starts_with($attachment->mimetype, "image/"))
					<h4>{{{ $attachment->original_name }}}</h4>
					<img src={{URL::asset('/uploads/'.$attachment->filename_saved_as)}} alt="{{ $attachment->original_name }}" class="img-responsive img-rounded upload-image">
				@elseif (starts_with($attachment->mimetype, "video/"))
					<div align="center" class="embed-responsive embed-responsive-16by9">
						<h4>{{{ $attachment->original_name }}}</h4>
						<video class="embed-responsive-item" controls="controls"  preload="auto" alt="{{ $attachment->original_name }}">
	    					<source src={{ URL::asset('/uploads/'.$attachment->filename_saved_as) }} type={{ $attachment->mimetype }} />
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
					<h4>{{{ $attachment->original_name }}}</h4>
					<img src={{URL::asset('/images/FileIcon.png')}} alt="{{ $attachment->original_name }}" class="img-responsive img-rounded upload-image">
				@endif
				<div class="btn btn-toolbar order-position-arrows">	
					<button class="glyphicon glyphicon-arrow-up up-arrow btn btn-lg"></button>
					<button class="glyphicon glyphicon-arrow-down down-arrow btn btn-lg"></button>
				</div>
			</div>	
			<?php $i++?>
		@endforeach
		{{ Form::hidden('count', $i, array('id' => 'count')) }}
		{{ Form::hidden('post', $post->id, array('id' => 'post')) }}
		{{ HTML::linkAction('UploadsController@getAddTitles', "Save", $post->id, array("method"=>"GET", "type"=>"button", "class"=>"btn btn-large btn-primary pull-right top-space")) }}
	</div>
</div> 
{{ HTML::Script("/js/postHighlight.js") }}
{{ HTML::Script("/js/uploadMovementButtons.js") }}

