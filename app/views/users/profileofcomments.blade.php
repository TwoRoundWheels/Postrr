<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
			<h1 class="white-text left-space">Profile of {{ $user->username }} </h1>
			<ul class="nav nav-tabs">
				<li class="profile-tab">{{HTML::linkAction('UsersController@getProfileOfPosts', 'POSTS', array($user->id))}} </li>
				<li class="profile-tab active">{{HTML::linkAction('UsersController@getProfileOfComments', 'COMMENTS', array($user->id)) }}</li>
				<li class="profile-tab"> {{HTML::linkAction('UsersController@getProfileOfUploads', 'UPLOADS', array($user->id)) }} </li>
			</ul>
			<br>
		</div>
	</div>
</div>
@foreach ($comments as $comment)
	<div class="container">
		<div class="panel panel-default"> 
			<div class="panel-heading">
				<h3 class="panel-title"> {{ HTML::link($comment->post->link, $comment->post->title) }} </h3>
				<span class="label label-default">Submitted by:  {{ HTML::linkAction('UsersController@getProfileOfComments', $comment->post->user->username, array($comment->post->user_id)) }} on {{ date("F d, Y",strtotime($comment->post->created_at)) }} at {{ date("g:ha",strtotime($comment->post->created_at)) }} 
				</span>
			</div> {{--/.panel-heading--}}
			<div class="panel-body post-text">	
				<p>  {{ HTML::linkAction('UsersController@getProfileOfComments', $comment->user->username, array($comment->user->id)) }} commented on {{ date("F d, Y",strtotime($comment->created_at)) }} at {{ date("g:ha",strtotime($comment->created_at)) }}:
				</p>
				<p>{{ nl2br(e($comment->commentText)) }}</p>
				<div>
					{{ HTML::linkAction('CommentsController@getShow', 'View Full Comments ('.$comment->post->comments_count.')', array($comment->post->id), array('class'=>'white-text btn btn-md btn-info')) }}
				</div>
				@if (Auth::id() == $comment->user->id)
				<br> 
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
			</div> {{--/.panel-body--}}
		</div> {{--/.panel--}}
	</div> {{--/.container--}}
@endforeach
<h3 class="text-center"> {{$comments->links();}} </h3>
