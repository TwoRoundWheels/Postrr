<div class="container">
	<div class="panel">
		<div class="panel-heading">	
			<h1 class="panel-title">{{ HTML::link($post->link, $post->title) }}
				@if ($post->nsfw == 1) 
					<span class='label label-danger'>NSFW</span>
				@endif
				@if ($post->video == 1)
					<span class='label label-warning'>VIDEO</span>
				@endif
				@if ($post->has_attachment == 1)
					<span class='label label-warning'>ATTACHMENT</span>
				@endif
			</h1>
		</div>
		<div class="panel-body post-text">
			<p>Submitted by:  {{ HTML::linkAction('UsersController@getProfileOfPosts', $post->user->username, array($post->user->id)) }} on {{ date("F d, Y",strtotime($post->created_at)) }} at {{ date("g:ha",strtotime($post->created_at)) }}. </p>
			@if ($post->description)
				<p> {{ nl2br(e($post->description)) }} </p>
			@endif
		</div>
	</div>
</div>
<div class="container">
	<div class="well well-lg">
		<h3>Comments</h3>
		@if (count($comments) >= 1)
			@foreach ($comments as $comment)
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							@if ($comment->edited)
								<h4 class="comment-title">{{ HTML::linkAction('UsersController@getProfileOfPosts', $comment->user->username, array($comment->user->id)) }}
								made changes on {{ date("F d, Y",strtotime($comment->updated_at)) }} at {{ date("g:ha",strtotime($comment->updated_at)) }}:</h4> 
							@else
								<h4 class="comment-title">{{ HTML::linkAction('UsersController@getProfileOfPosts', $comment->user->username, array($comment->user->id)) }}
								commented on {{ date("F d, Y",strtotime($comment->created_at)) }} at {{ date("g:ha",strtotime($comment->created_at)) }}:</h4> 
							@endif
						</div>
					</div> <!--/.panel-heading -->
				<div class="panel-body comment-text">
				<p class = "word-wrap">{{ nl2br(e($comment->commentText)) }}</p>
				@if (Auth::id()==$comment->user_id) 
					<div>
						<div class="btn-group btn-group-sm">
							<a data-toggle="collapse" href="#{{$comment->id}}" class="btn btn-warning">Edit</a>
							<a data-toggle="modal" href="#confirm-delete-{{$comment->id}}" class="btn btn-danger">Delete</a>
					  	</div> <!--/.btn-group-->
						{{--DIV opens form when 'edit' button is clicked, to allow inline comment editing--}}
						<div class="collapse" id="{{$comment->id}}">
							{{ Form::open(array('url'=>'comments/edit', 'class'=>'form-comment')) }}	
							{{ Form::textarea('commentText', $comment->commentText, array('class'=>'form-control')) }}
							{{ Form::hidden('id', $comment->id ) }}	
							{{ Form::submit('Save', array('class'=>'btn btn-small btn-primary')) }}
							{{ Form::close() }} 
						</div>
						<div class="modal fade" id="confirm-delete-{{$comment->id}}">
						  <div class="modal-dialog">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						        <h4 class="modal-title alert alert-danger">Are you sure?</h4>
						      </div>
						      <div class="modal-body">
						        <p>Deleting this comment will cause it to never seen by the world again.  This could be good or bad.</p>
						      </div>
						      <div class="modal-footer">
						        {{ Form::open(array('url'=>'comments/delete')) }}	
								{{ Form::hidden('id', $comment->id ) }}
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								{{ Form::submit('Delete', array('type'=>'button', 'class'=>'btn btn-danger')) }}
								{{ Form::close() }}
						      </div>
						    </div><!-- /.modal-content -->
						  </div><!-- /.modal-dialog -->
						</div><!-- /.modal -->
					</div>
				@endif
					</div> <!--panel-body-->
				</div><!-- /.panel-heading -->
			@endforeach
		@else
		<p>No comments yet.</p>
		@endif
		<div class="container">
			@if(Session::has('message'))
				<h4 class="errors"> {{ Session::get('message') }} </h4>
				@foreach ($errors->all() as $error) 
					<li class="errors"> {{ $error }} </li>
				@endforeach
			@endif
		</div>
		<div>
			<h3>Have something to say?</h3>
			{{ Form::open(array('url'=>'comments/new', 'class'=>'form-comment')) }}
				{{ Form::hidden('post_id', $post->id) }}
				{{ Form::label('commentText', 'Type a comment.')}}
				{{ Form::textarea('commentText',null , array('placeholder'=>'Click here and press keys that correspond with how you feel about the post above.', 'class'=>'form-control', 'rows'=>'4' )) }}
				<div class="form-group">	
					{{ Form::submit('Save', array('class'=>'btn btn-large btn-primary pull-right')) }}
				</div>
			{{ Form::close() }} 
		</div>
	</div> <!-- /.well -->
</div> <!-- /.container -->





