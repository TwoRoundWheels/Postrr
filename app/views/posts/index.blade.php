<div class="container">
	<div>
		@if(Session::has('message'))
			<h3 class="text-info">{{ Session::get('message') }}</h3>
		@endif
	</div>
	<div>
	@foreach ($posts as $post)
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title post-title"> {{ HTML::link($post->link, $post->title) }} 
				@if ($post->nsfw == 1) 
					<span class='label label-danger'>NSFW</span>
				@endif
				@if ($post->video == 1)
					<span class='label label-warning'>VIDEO</span>
				@endif
				@if ($post->has_attachment == 1)
					<span class='label label-warning'>ATTACHMENT</span>
				@endif
				</h3>
			</div> <!-- /.panel-heading -->
			<div class="panel-body post-text">
				<p>Submitted by:  {{ HTML::linkAction('UsersController@getProfileOfPosts', $post->user->username, array($post->user->id)) }} on {{ date("F d, Y",strtotime($post->created_at)) }} at {{ date("g:ha",strtotime($post->created_at)) }} </p>
				@if ($post->comments_count == 1)
					<p class="badge"> {{ HTML::linkAction('CommentsController@getShow', $post->comments_count.' Comment', array($post->id), array('class'=>'white-text')) }}
					</p>
				@else
					<p class="badge"> {{ HTML::linkAction('CommentsController@getShow', $post->comments_count.' Comments', array($post->id), array('class'=>'white-text')) }}
					</p>
				@endif	
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
			</div> <!-- /.panel-body -->
		</div> <!-- /.panel-->
	@endforeach
	<h3 class="text-center"> {{$posts->links();}} </h4>
	</div>
</div> <!-- /.container -->
{{ HTML::Script("/js/homeHighlight.js") }}


