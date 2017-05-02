<?php

class PostsController extends BaseController
{
    /*
    * Define layout and make available to BaseController and CommentsController
    * classes.
    */
    protected $layout = 'layouts.main';

    /*
    * Set all POST requests to require CSRF token.  Auth filter all requests
    * to ensure the user is logged in.
    */
    public function __construct()
    {
        $this->beforeFilter('CSRF', array('on' => 'post'));
        $this->beforeFilter('auth');
    }

    /* Create the new post view when the 'post' nav button on main.blade.php is 
    *  pressed.  The method alters the content section of main.blade.php.
    */
    public function getPostNew()
    {
        $this->layout->content = View::make('posts/new');
    }

    /* Save submitted post information as well as assign a relationship to any
    *  uploads and comments that were included.
    */
    public function postPostSubmit()
    {
        $validator = Validator::make(Input::all(), Post::$postRules);
        if ($validator->passes()) {
            /* Repost variable is set to false initially, and will later be set 
            *  to true if the link submitted matches a link already stored in the 
            *  database.
            */
            $repost = false;
            $post = new Post();
            /*  If the input has a description count the description as the first
            *  comment by setting 'comments_count' to 1. 
            */
            if (Input::get('description')) {
                $post->description = Input::get('description');
                $post->comments_count = 1;
            }
            $post->user_id = Auth::id();
            $post->nsfw = Input::get('nsfw', 0);
            $post->video = Input::get('video', 0);
            /* Call save() to set $post->id, which needs to be set to generate 
            *  the url saved in $post->link if $post->link is left blank by the 
            *  user.
            */
            $post->save();
            /*Save link to begin with http:// if it is not already present 
            *at the beginning of the string.
            */
            if (Input::has('link')) {
                $post->link = Input::get('link');
                if (!starts_with($post->link, 'http://') && !starts_with($post->link, 'https://')) {
                    $post->link = 'http://'.$post->link;
                }
                /* Test if link matches a link already in the database. and set 
                *  $repost to true if that condition is met.
                */
                $oldPosts = Post::all();
                foreach ($oldPosts as $oldPost) {
                    if ($post->link == $oldPost->link) {
                        $repost = true;
                    }
                }
            }
            /* If no link is present set link to show Comments when clicked.*/
            else {
                $post->link = action('CommentsController@getShow', $post->id);
            }
            $post->title = Input::get('title');
            /*  If new post is a repost Repost Warning- will be added to the 
            *  beginning of the title.
            */
            if ($repost) {
                $post->title = 'REPOST WARNING- '.$post->title;
            }
            /* Check for included attachments and save.  Attachments will already
            *  be saved to disk and database, this is just to assign a related 
            *  post_id and set the display order.
            */
            if (Input::get('attached')) {
                $post->has_attachment = 1;
                $filenames = Input::get('attached');
                $displayOrder = 0;
                foreach ($filenames as $filename) {
                    $upload = Upload::where('filename_saved_as', '=', $filename)
                                    ->first();
                    $upload->post_id = $post->id;
                    $upload->display_order = $displayOrder;
                    $upload->save();
                    ++$displayOrder;
                }
                $post->link = action('UploadsController@getShow', $post->id);
            }
            $post->save();
            /* If user selected to add titles and descriptions to their attachments
            *  they will be redirected to the Upload controller to complete their
            *  post.
            */
            if (Input::get('create-album')) {
                return Redirect::action('UploadsController@getCreateAlbum', $post->id);
            }
            /* Send an email to all users who have 'email_new_post' on the Users 
            *  table set to True that there is a new post on the site.
            */
            App::make('EmailController')->send($post, 'Post');
        } else {
            return Redirect::to('posts/post-new')
                           ->with('message', 'The following errors occured:')
                           ->withErrors($validator)
                           ->withInput();
        }
        /* Return a different view based on the true or false of $repost*/
        if ($repost) {
            return Redirect::to('posts/index')
                           ->with('message', 'WARNING: REPOST - You really could not find anything original? :)');
        } else {
            return Redirect::to('posts/index')
                           ->with('message', 'Post Saved');
        }
    }

    /* Retrieve all posts from database in descending order and alter the content
    *  section of main.blade.php with index.blade.php. 
    */
    public function getIndex()
    {
        $posts = Post::with('user')->orderBy('id', 'desc')->paginate(20);
        $this->layout->content = View::make('posts/index')->with('posts', $posts);
    }

    /* Retrieve the post by the post_id number which is passed from a hidden 
    *  field on the delete button on index.blade.php.  Then check if the user_id 
    *  of the post matches the Auth::id to prevent being able to use the address 
    *  bar to delete posts the user did not create.  If passes, the comments for
    *  the post are retrieved and deleted and the post is deleted.  User is sent 
    *  back with a success message. All related uploads are also gathered and 
    *  deleted from the database and disk.
    */
    public function postDelete()
    {
        $post = Post::findorfail(Input::get('id'));
        if ($post->user_id == Auth::id()) {
            // Find and delete all related comments.
            $comments = $post->comments();
            $comments->delete();
            // Find and delete all related uploads.
            $uploads = $post->uploads()->get();
            $uploads->each(function ($upload) {
                $path = public_path('uploads');
                $filename = $upload->filename_saved_as;
                File::delete($path.'/'.$filename);
                $upload->delete();
            });
            $post->delete();
            return Redirect::back()->with('message', 'Post deleted!');
        } else {
            return Redirect::to('posts/index')
                           ->with('message', 'You cannot delete posts that do not belong to you.');
        }
    }

    /* Retrieve the post by the post_id, which is passed from a hidden field when
    *  the edit button on index.blade.php is pressed.  Check to see if the user_id
    *  of the post matches Auth::id to prevent being able to use the address bar 
    *  to edit posts which the user did not create. If passes, the content section
    *  of main.blade.php is altered with edit.blade.php.  Data from the post
    *  is also sent as $post, to populate the fields of the edit form.
    */
    public function getEdit($post_id)
    {
        $post = Post::find($post_id);
        if ($post->user_id == Auth::id()) {
            $this->layout->content = View::make('posts/edit')
                                   ->with('post', $post);
        } else {
            return Redirect::to('posts/index')
                           ->with('message', 'You cannot edit posts that are not yours.');
        }
    }

    /* Retrieve post in database by post_id which is passed from index.blade.php.
    *  Then check if user_id field of post matches Auth::id value, to prevent 
    *  using the address bar to delete posts which the user did not create.  
    *  The Input from edit.blade.php is retrieved, and checked to pass the 
    *  validation rules defined in the Post model.  If validation passes, the 
    *  database fields are updated with values from the corresponding form fields 
    *  and the user is redirected back to index.blade.php with a success message.  
    */
    public function postUpdate()
    {
        $post = Post::find(Input::get('post_id'));
        if ($post->user_id == Auth::id()) {
            $validator = Validator::make(Input::all(), Post::$postRules);
            if ($validator->passes()) {
                $post->title = Input::get('title');
                $post->link = Input::get('link');
                $post->description = Input::get('description');
                $post->nsfw = Input::get('nsfw');
                $post->video = Input::get('video');
                $post->save();

                return Redirect::action('PostsController@getIndex')
                               ->with('message', 'Your post was successfully updated.');
            } else {
                return Redirect::back()
                               ->with('message', 'The following errors occured:')
                               ->withErrors($validator)
                               ->withInput();
            }
        } else {
            return Redirect::to('posts/index')
                           ->with('message', 'You cannot edit posts that are not yours.');
        }
    }

    /* If the user opts to remove an upload while creating a new post
    *  this method handles the ajax request and redirects to the UploadsController
    *  to remove the upload.
    */
    public function postRemoveAttachment()
    {
        if (Request::ajax() && Input::has('savedName')) {
            $filename_saved_as = Input::get('savedName');

            return Redirect::action('UploadsController@getRemove')
                           ->with('filename_saved_as', $filename_saved_as);
        } else {
            return Response::json('error', 400);
        }
    }
}
