<?php

class CommentsController extends BaseController
{
    /* Define layout and make available to BaseController and CommentsController
    *  classes.
    */
    protected $layout = 'layouts.main';

    /* Set all POST requests to require CSRF token.  Also filter all requests
    *  to ensure the user is logged in.
    */

    public function __construct()
    {
        $this->beforeFilter('CSRF', array('on' => 'post'));
        $this->beforeFilter('auth');
    }
    
    /* Takes $post_id sent in array from index.blade.php, finds that post in the
    *  db, and gets all comments for that post_id.
    */
    public function getShow($post_id)
    {
        $post = Post::find($post_id);
        $comments = $post->comments()->get();
        $this->layout->content = View::make('comments/showComments')
                                     ->with(array(
                                     	'post' => $post, 
                                     	'comments' => $comments)
                                     );
    }

    /* Checks the input received from the form on showComments.blade.php and
    *  passes it to a validator, with rules defined in the Comment model.
    *  If the validator fails, users are sent back to the form with error
    *  messages.  Then the post is found in the database, post_id is passed as
    *  a hidden field in the form, and an Eloquent New Comment is created, with
    *  values updated with the corresponding info submitted from the form.
    *  Users with email_new_comment set true are then emailed a notification
    *  using Laravel's Mail::send.  Content section of main.blade.php is then
    *  altered with showComments.blade.php/ and a success message is given.
    */

    public function postNew()
    {
        $validator = Validator::make(Input::all(), Comment::$commentRules);
        if ($validator->passes()) {
            $post = Post::findorfail(Input::get('post_id'));
            $comment = new Comment();
            $comment->commentText = Input::get('commentText');
            $comment->post_id = $post->id;
            $comment->user_id = Auth::id();
            $post->increment('comments_count');
            $comment->save();

            /* This section sends an email alerting a new post to users who have
            *  the 'email_new_comment' flag set to true on the Users table.
            */
            App::make('EmailController')->send($post, 'Comment');

            return Redirect::action('CommentsController@getShow', array(
            	'post_id' => $post->id, 
            	'message' => 'Comment Saved!')
            );
        } else {
            return Redirect::back()->with('message', 'The following errors occured:')
                                   ->withErrors($validator)
                                   ->withInput();
        }
    }

    /* Checks the input received from the edit form on showComments.blade.php and
    *  passes it to a validator, with rules defined in the Comment model.  If the
    *  validator fails, users are sent back to the form with error messages.
    *  comment_id is sent as a hidden field in the form.  Then the comment_id is
    *  found in the database.  If the user id matches the user id on the comment
    *  table, (using Auth::id) the fields are updated through Eloquent to the
    *  submitted data from the form.  The edited field is set to true, and
    *  timestamp updated (touch()) to show the comment has been edited when
    *  displayed again on showcomments.blade.php.  Then the user is redirected 
    *  to the show action of the comments controller with a success message.
    */

    public function postEdit()
    {
        $validator = Validator::make(Input::all(), Comment::$commentRules);
        if ($validator->passes()) {
            $comment = Comment::findorfail(Input::get('id'));
            if ($comment->user_id == Auth::id()) {
                $comment->commentText = Input::get('commentText');
                $comment->edited = 1;
                $comment->touch();
                $comment->save();

                return Redirect::action('CommentsController@getShow', array(
                	'post_id' => $comment->post_id, 
                	'message' => 'Comment Saved!')
                );
            } else {
                return Redirect::to('posts/index')
                			   ->with('message', 'You cannot edit comments that are not yours.');
            }
        } else {
            return Redirect::back()->with('message', 'The following errors occured:')
                                   ->withErrors($validator)
                                   ->withInput();
        }
    }
    /* The comment_id is submitted via hidden field when the delete button is
    *  pressed on showComments.blade.php.  If the current user id (Auth::id)
    *  matches what is in the comment table, the user is allowed to delete the
    *  post.  comments_count on the post table is decremented using Eloquent to
    *  reflect that the comment has been deleted.  The user is redirected to the 
    *  show action in the comments controller.
    */

    public function postDelete()
    {
        $comment = Comment::findorfail(Input::get('id'));
        if ($comment->user_id == Auth::id()) {
            $comment->post->decrement('comments_count');
            $comment->delete();

            return Redirect::back()->with('message', 'Comment Deleted!');
        } else {
            return Redirect::to('posts/index')
            			   ->with('message', 'You cannot delete comments that are not yours.');
        }
    }
}
