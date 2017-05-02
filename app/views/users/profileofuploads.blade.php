
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
			<h1 class="white-text left-space">Profile of {{ $user->username }} </h1>
			<ul class="nav nav-tabs">
				<li class="profile-tab">{{HTML::linkAction('UsersController@getProfileOfPosts', 'POSTS', array($user->id))}} </li>
				<li class="profile-tab">{{HTML::linkAction('UsersController@getProfileOfComments', 'COMMENTS', array($user->id)) }}</li>
				<li class="profile-tab active"> {{HTML::linkAction('UsersController@getProfileOfUploads', 'UPLOADS', array($user->id)) }} </li>
			</ul>
			<br>
		</div>
	</div>
</div>
<br>
@if(Session::has('message'))
	<div class="container">
		<h4 class="errors">{{ Session::get('message') }}</h4>
		@foreach ($errors->all() as $error) 
			<li class="errors"> {{$error}} </li>
		@endforeach
	</div>
@endif
<div class="container">
	{{--Displaying 4 uploads per row.  Start a new row everytime i is divisible by 4.--}}
	<?php $i=0 ?>
	<?php $j=0 ?>
	@foreach ($uploads as $upload)
		@if ($i === 0 || $i%4 === 0)
			<div class="row">
		@endif
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 upload-image-thumbnail">
					@if (starts_with($upload->mimetype, "image/"))
						<img src={{ URL::asset('/uploads/'.$upload->filename_saved_as) }} alt="{{{ $upload->original_name }}}" class="img-responsive upload-image" id="{{ $upload->id }}">
						<h6 class="white-text text-center">{{{ $upload->original_name }}}</h6>
						<a href={{ URL::asset('/uploads/'.$upload->filename_saved_as) }} class="btn btn-default view"><span class="glyphicon glyphicon-eye-open"></span></a>
					@elseif (starts_with($upload->mimetype, "audio/"))
						<img src={{ URL::asset('/images/AudioIcon.png') }} alt="{{ $upload->original_name }}" class="img-responsive upload-image" id="{{ $upload->id }}">
						<h6 class="white-text text-center">{{{ $upload->original_name }}}</h6>
						<a href={{ URL::asset('/uploads/'.$upload->filename_saved_as) }} class="btn btn-default view"><span class="glyphicon glyphicon-play"></span></a>					
					@elseif (starts_with($upload->mimetype, "video/"))
						<img src={{ URL::asset('/images/VideoIcon.png') }} alt="{{ $upload->original_name }}" class="img-responsive upload-image" id="{{ $upload->id }}">					
						<h6 class="white-text text-center">{{{ $upload->original_name }}}</h6>
						<a href={{ URL::asset('/uploads/'.$upload->filename_saved_as) }} class="btn btn-default view"><span class="glyphicon glyphicon-play"></span></a>
					@else
						<img src={{ URL::asset('/images/FileIcon.png') }} alt="{{ $upload->original_name }}" class="img-responsive upload-image" id="{{ $upload->id }}">
						<h6 class="white-text text-center">{{{ $upload->original_name }}}</h6>
					@endif
					@if (Auth::id() == $upload->user->id) 
						<button type="button" data-toggle="modal" href="#confirm-delete-{{$upload->id}}" class="delete btn btn-default"><span class="glyphicon glyphicon-trash"></span></button>
					@endif	
					<a href={{action('UploadsController@getDownload', $upload->filename_saved_as) }} class="btn btn-default download"><span class="glyphicon glyphicon-download"></span></a>
				</div>
				@if (Auth::id() == $upload->user->id)
					<div class="modal fade" id="confirm-delete-{{$upload->id}}">
						<div class="modal-dialog">
							<div class="modal-content">
							    <div class="modal-header">
							    	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							        <h4 class="modal-title alert alert-danger">Are you sure?</h4>
							    </div>
							    <div class="modal-body">
							        <p>Deleting this upload will cause it to never seen by the world again!</p>
							    </div>
							    <div class="modal-footer"> 
							        {{ Form::open(array('url'=>'uploads/delete')) }}	
									{{ Form::hidden('id', $upload->id ) }}
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									{{ Form::submit('Delete', array('type'=>'button', 'class'=>'btn btn-danger')) }}
									{{ Form::close() }}
							    </div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
				@endif
			{{--Increment j by 1, then everytime j equals 4, close the row and reset j to 0--}}
			<?php $j++ ?>
			@if	($j === 4)	
				</div>
				<?php $j=0 ?>
			@endif
		<?php $i++ ?>
	@endforeach
</div>
<h3 class="text-center"> {{$uploads->links();}} </h3>
{{ HTML::script('js/uploadPlayButton.js') }}
{{ HTML::script('js/uploadDownloadButton.js') }}
@if (count($uploads) > 0 && Auth::id() == $upload->user->id) 
	{{ HTML::script('/js/uploadDeleteButtons.js') }}
@endif
