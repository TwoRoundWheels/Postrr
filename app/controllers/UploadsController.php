<?php

class UploadsController extends BaseController
{
    /*
    * Define layout and make available to BaseController and CommentsController
    * classes.
    */

    protected $layout = 'layouts.main';

    /*
    * Set all POST requests to require CSRF token.  Also filter all requests
    * to ensure the user is logged in.
    */
    public function __construct()
    {
        $this->beforeFilter('CSRF', array('on' => 'post'));
        $this->beforeFilter('auth');
    }

    /* Receive file upload from dropzone.js on new post page.  If file passes
    *  validation it is saved to the disk as well as the database and a json 
    *  response is returned.
    */
    public function postStore()
    {
        $file = Input::file('file');
        if ($file->isValid()) {
            // Validate that file only has one "." in name. No double file extensions allowed.
            $validator = Validator::make(array(
                                  'filename' => $file->getClientOriginalName()), 
                                  Upload::$extensionRules);
            if ($validator->passes()) {
                //Validate that file is of one of the allowed mimetypes.
                $validator = Validator::make(array(
                                            'file' => $file), 
                                            Upload::$fileRules);
                if ($validator->passes() || $file->getMimeType() == 'application/ogg') {
                    $uploadedFile = new Upload();
                    $uploadedFile->mimetype = Input::file('file')->getMimeType();
                    $extension = $file->getClientOriginalExtension();
                    //If file is an image, check orientation and rotate correctly.
                    $rotated = false;
                    if (starts_with($uploadedFile->mimetype, 'image/') && strtolower($extension) =='jpg') {
                        $exifData = read_exif_data(Input::file('file'));
                        if (isset($exifData['Orientation']) && $exifData['Orientation'] != 1) {
                                $image = imagecreatefromjpeg(Input::file('file'));
                                switch ($exifData['Orientation']) {
                                    case 3:
                                        $rotatedFile = imagerotate($image, 180, 0);
                                        $rotated = true;
                                        break;
                                    case 6:
                                        $rotatedFile = imagerotate($image, 270, 0);
                                        $rotated = true;
                                        break;
                                    case 8:
                                       $rotatedFile = imagerotate($image, 90, 0);
                                        $rotated = true;
                                        break;
                                }
                        }
                    }
                    $uploadedFile->user_id = Auth::id();
                    $uploadedFile->original_name = $file->getClientOriginalName();
                    $destinationPath = public_path('uploads');
                    $filename = 'CD'.str_random(12).'.'.$extension;
                    if ($rotated === true) {
                        $upload_success = imagejpeg($rotatedFile, $destinationPath.'/'.$filename, 100);
                    } else {
                        $upload_success = Input::file('file')->move($destinationPath, $filename);
                    }
                    $uploadedFile->filename_saved_as = $filename;
                    $uploadedFile->save();

                    if ($upload_success) {
                        if ($rotated === true) {
                            imagedestroy($image);
                        }
                        return Response::json(array('filename' => $filename, 'success' => 200));
                    } else {
                        return Response::json('error', 400);
                    }
                } else {
                    $messages = $validator->messages();
                    $error = $messages->first();
                    return Response::json($error, 400);
                }
            } else {
                $messages = $validator->messages();
                $error = $messages->first();
                return Response::json($error, 400);
            }
        } else {
            $text = 'Something went wrong, your file may be too large.';

            return Response::json($text, 400);
        }
    }
    /* Get all the related uploads for a given $post_id parameter and alter the
    *  layout of main.blade.php with the showUploads view to display the uploads.
    */
    public function getShow($post_id)
    {
        $post = Post::find($post_id);
        $attachments = $post->uploads->sortBy('display_order');
        $this->layout->content = View::make('uploads/showUploads')
                                     ->with(array('post' => $post, 
                                                  'attachments' => $attachments)
                                            );
    }

    /* Check the input received from the edit form on showUploads.blade.php and 
    *  pass it to a validator with rules defined in the Upload model.  If the 
    *  validator fails, users are sent back to the form with error messages.  If
    *  it passes, the upload_id is found in the database (sent as hidden field 
    *  in the form).  If the user id matches the user id on the upload table,
    *  update the database fields with the submitted data.
    */
    public function postEdit()
    {
        $validator = Validator::make(Input::all(), Upload::$descriptionRules);
        if ($validator->passes()) {
            $upload = Upload::findorfail(Input::get('id'));
            if ($upload->user_id == Auth::id()) {
                if (Input::has('title')) {
                    $upload->title = Input::get('title');
                } elseif (Input::has('description')) {
                    $upload->description = Input::get('description');
                }
                $upload->touch();
                $upload->save();

                return Redirect::action('UploadsController@getShow', array(
                                        'post_id' => $upload->post_id, 
                                        'message' => 'Changes Saved!')
                                        );
            } else {
                return Redirect::to('posts/index')
                               ->with('message', 'You cannot edit items that are not yours.');
            }
        } else {
            return Redirect::back()
                           ->with('message', 'The following errors occured:')
                           ->withErrors($validator)
                           ->withInput();
        }
    }

    /* Delete an upload that has been saved in both the database and on disk.
    *  Check if user id matches user_id in the Upload table and delete the file
    *  from the disk and the database.  Return a success or failure messae to the 
    *  user.
    */
    public function postDelete()
    {
        $upload = Upload::findorfail(Input::get('id'));
        if ($upload->user_id == Auth::id()) {
            $path = public_path('uploads');
            $filename = $upload->filename_saved_as;
            File::delete($path.'/'.$filename);
            if (!File::exists($path.'/'.$filename)) {
                $upload->delete();
                return Redirect::back()->with('message', 'File deleted!');
            } else {
                return Redirect::back()
                               ->with('message', 'Unfortunately, your file may not have deleted correctly.');
            }
        } else {
            return Redirect::to('posts/index')
                           ->with('message', 'You cannot delete files that do not belong to you.');
        }
    }

    /* When a user deletes a file from the new post form the PostsController will
    *  redirect to here.  The filename will be retrieved from the session info. 
    *  If the user id matches the user_id in the uploads table, the file will be
    *  deleted from the disk and the database.  A success or failure json response
    *  will then be returned to the user.
    */
    public function getRemove()
    {
        $filename_saved_as = Session::get('filename_saved_as');
        $upload = Upload::where('filename_saved_as', '=', $filename_saved_as)->first();
        if ($upload->user_id == Auth::id()) {
            $path = public_path('uploads');
            $filename = $upload->filename_saved_as;
            File::delete($path.'/'.$filename);
            if (!File::exists($path.'/'.$filename)) {
                $upload->delete();
                return Response::json(array('success' => 200));
            } else {
                return Response::json(array('error' => 400));
            }
        } else {
            return Redirect::to('posts/index')
                           ->with('message', 'You cannot delete files that do not belong to you.');
        }
    }

    /* If the user elects to add titles and descriptions to their uploads, 
    *  the postSubmit method in the PostsController will redirect to here.
    *  Find all the related uploads for a given post_id and alter the layout of
    *  main.blade.php with the setDisplayOrder view.
    */
    public function getCreateAlbum($post_id)
    {
        $post = Post::find($post_id);
        $attachments = DB::table('uploads')->where('post_id', '=', $post_id)
                         ->orderBy('display_order', 'asc')->get();
        $this->layout->content = View::make('uploads/setDisplayOrder')
                               ->with(array('post' => $post, 'attachments' => $attachments));
    }

    /* After the display order has been set in the setDisplayOrder view, the user
    *  will given the option to add titles and descriptions to their uploads.  
    *  Find all the uploads for a given post_id and alter the layout of 
    *  main.blade.php with the addTitles view.
    */
    public function getAddTitles($post_id)
    {
        $post = Post::find($post_id);
        $attachments = DB::table('uploads')
                         ->where('post_id', '=', $post_id)
                         ->orderBy('display_order', 'asc')->get();
        $this->layout->content = View::make('uploads/addTitles')
                                     ->with(array('post' => $post, 'attachments' => $attachments));
    }

    /* Once the user has submitted any titles or descriptions for their uploads,
    *  validate the files against the rules defined in the Upload model.  Save the
    *  titles and descriptions to the database and send a new post email to users.
    *  Redirect the user to posts/index with a success message.
    */
    public function postSaveAlbum()
    {
        $validator = Validator::make(Input::all(), Upload::$descriptionRules);
        if ($validator->passes()) {
            $post_id = Input::get('post_id');
            $post = Post::findorfail($post_id);
            $attachments = $post->uploads()->get();

            foreach ($attachments as $attachment) {
                if (Input::has('title'.'-'.$attachment->id)) {
                    $attachment->title = (Input::get('title'.'-'.$attachment->id));
                    $attachment->save();
                }
                if (Input::has('description'.'-'.$attachment->id)) {
                    $attachment->description = (Input::get('description'.'-'.$attachment->id));
                    $attachment->save();
                }
            }
            App::make('EmailController')->send($post, 'Post');

            return Redirect::to('posts/index')->with('message', 'Post Saved');
        } else {
            $post_id = Input::get('post_id');

            return Redirect::back()->with('message', 'The following errors occured: ')
                                   ->withErrors($validator)->withInput();
        }
    }

    /* Receive and ajax request from the setDisplayOrder view.  The database will
    *  be queried and the appropriate display_order fields will be updated. 
    *  Return a json response indicating success.
    */
    public function postMoveUp()
    {
        if (Request::ajax()) {
            $id = Input::get('id');
            $post_id = Input::get('postId');
            if ($id != 0) {
                $upload_to_increment = Upload::where('post_id', '=', $post_id)
                                             ->where('display_order', '=', $id - 1)
                                             ->first();
                $upload_to_decrement = Upload::where('post_id', '=', $post_id)
                                             ->where('display_order', '=', $id)
                                             ->first();
                if ($upload_to_increment->user_id == Auth::id() && $upload_to_decrement->user_id == Auth::id()) {
                    $upload_to_increment->display_order = $id;
                    $upload_to_increment->save();

                    $upload_to_decrement->display_order = $id - 1;
                    $upload_to_decrement->save();

                    return Response::json(array('success' => 200));
                }
            }
        }
    }

    /* Receive and ajax request from the setDisplayOrder view.  The database will
    *  be queried and the appropriate display_order fields will be updated.
    *  Return a json response indicating success.
    */
    public function postMoveDown()
    {
        if (Request::ajax()) {
            $id = Input::get('id');
            $post_id = Input::get('postId');
            $max = Input::get('max');
            if ($id <= $max) {
                $upload_to_increment = Upload::where('post_id', '=', $post_id)
                                             ->where('display_order', '=', $id)
                                             ->first();
                $upload_to_decrement = Upload::where('post_id', '=', $post_id)
                                             ->where('display_order', '=', $id + 1)
                                             ->first();
                if ($upload_to_increment->user_id == Auth::id() && $upload_to_decrement->user_id == Auth::id()) {
                    $upload_to_increment->display_order = $id + 1;
                    $upload_to_increment->save();

                    $upload_to_decrement->display_order = $id;
                    $upload_to_decrement->save();

                    return Response::json(array('success' => 200));
                }
            }
        }
    }

    /* Check that the user is logged in and return a download response for the 
    *  requested file.
    */

    public function getDownload($filename)
    {
        if (Auth::check()) {
            $file = Upload::where('filename_saved_as', '=', $filename)->first();
            $download = public_path('uploads').'/'.$filename;

            return Response::download($download, $file->original_name);
        }
    }
}
