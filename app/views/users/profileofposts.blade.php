<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
			<h1 class="white-text left-space">Profile of {{ $user->username }} </h1>
			<ul class="nav nav-tabs">
				<li class="active profile-tab">{{HTML::linkAction('UsersController@getProfileOfPosts', 'POSTS', array($user->id))}} </li>
				<li class="profile-tab">{{HTML::linkAction('UsersController@getProfileOfComments', 'COMMENTS', array($user->id)) }}</li>
				<li class="profile-tab"> {{HTML::linkAction('UsersController@getProfileOfUploads', 'UPLOADS', array($user->id)) }} </li>
			</ul>
			<br>
		</div>
	</div>
</div>
@foreach ($posts as $post) 
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"> {{ HTML::link($post->link, $post->title) }}</h3>
			</div>  {{--/.panel-heading--}}
			<div class="panel-body post-text">	
				<p>Submitted by:  {{ HTML::linkAction('UsersController@getProfileOfPosts', $post->user->username, array($post->user->id)) }} on {{ date("F d, Y",strtotime($post->created_at)) }} at {{ date("g:ha",strtotime($post->created_at)) }} </p>
				<p class="badge"> {{ HTML::linkAction('CommentsController@getShow', $post->comments_count.' Comments', array($post->id), array("class"=>"white-text")) }}
				</p>
				<div>
					@if (Auth::id() == $post->user->id) 
					<div class="btn-group btn-group-sm">
						{{ HTML::linkAction('PostsController@getEdit', 'Edit', array($post->id), array('class'=>'btn btn-warning')) }} 
						<a data-toggle="modal" href="#confirm-delete-{{$post->id}}" class="btn btn-danger">Delete</a>
					</div>
					<div class="modal fade" id="confirm-delete-{{$post->id}}">
						<div class="modal-dialog">
							<div class="modal-content">
							    <div class="modal-header">
							    	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							        <h4 class="modal-title alert alert-danger">Are you sure?</h4>
							    </div>
							    <div class="modal-body">
							    	<p>Deleting this post will cause it to never seen by the world again.  This could be good or bad.</p>
							    </div>
							    <div class="modal-footer">
							    	{{ Form::open(array('url'=>'posts/delete')) }}	
									{{ Form::hidden('id', $post->id ) }}
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									{{ Form::submit('Delete', array('type'=>'button', 'class'=>'btn btn-danger')) }}
									{{ Form::close() }}
							    </div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					@endif 	
				</div>
			</div>  {{--panel-body--}}
		</div>  {{--/.panel--}}
	</div>  {{--/.container--}}
@endforeach
<h3 class="text-center"> {{$posts->links();}} </h3>
