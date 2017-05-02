{{HTML::Script("/js/dropzone/dropzone.js")}}
{{HTML::Style("css/dropzone.css")}}
<div class="container">
	<div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
		<div class="well well-sm clearfix">	
			{{ Form::open(array('url'=>'posts/post-submit', 'class'=>'form-post', 'id'=>'Post')) }}
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#words"><h2>Post Some Words</h2></a></li>
				<li><a data-toggle="tab" href="#attach"><h2>Attach Some Files</h2></a></li>
			</ul>
			<div class="container">
				@if(Session::has('message'))
					<h4 class="errors">{{ Session::get('message') }}</h4>
					@foreach ($errors->all() as $error) 
						<li class='errors'>{{$error}}</li>
					@endforeach
				@endif
			</div>
			<div class="tab-content">
				<div class="tab-pane active left-pad right-pad" id="words">
					{{ Form::label('title', 'Title :') }}
					{{ Form::text('title', null, array('class'=>'form-control', 'placeholder'=>'Give your post a title.')) }}
					{{ Form::label('link', 'URL :') }}
					{{ Form::text('link', null, array('id'=>'url', 'class'=>'form-control', 'placeholder'=>'Type an URL here, or leave blank to create a "self" post.')) }}
					{{ Form::label('description', 'Description') }}
					{{ Form::textarea('description', null, array('class'=>'form-control', 'placeholder'=>'If you\'d like, type a description or comment here. (5000 Character Limit)')) }}
					{{ Form::label('nsfw', 'NSFW?') }}
					{{ Form::checkbox('nsfw', '1', null) }}
					{{ Form::label('video', 'VIDEO?') }}
					{{ Form::checkbox('video', '1', null) }}
					{{ Form::label('create-album', 'ADD TITLES AND DESCRIPTIONS?', array('class'=>'left-space')) }}
					{{ Form::checkbox('create-album', '1', null, ['class'=>'left-space create-album-checkbox']) }}
					{{ Form::submit('Submit', array('class'=>'btn btn-large btn-primary pull-right top-space')) }}
					{{ Form::close() }}
				</div>
				<div class="tab-pane" id="attach">
	            	{{ Form::open(array('action'=>'UploadsController@postStore', 'class'=>'dropzone', 'id'=>'dropzone-form', 'files'=>true)) }}
					{{ Form::close() }}
					{{ Form::label('create-album', 'ADD TITLES AND DESCRIPTIONS?', array('class'=>'left-space')) }}
					{{ Form::checkbox('create-album', '1', null, ['class'=>'left-space create-album-checkbox']) }}
	        	</div>
			</div> <!--/tab-content-->
		</div> <!--/.well-->
	</div> <!--/.col-->
</div> <!--/.container-->
{{ HTML::Script("/js/postHighlight.js") }}
{{ HTML::Script("/js/dzoptions.js") }}
{{ HTML::Script("/js/albumCheckboxToggle.js") }}

