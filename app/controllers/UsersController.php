<?php

class UsersController extends BaseController
{
    /* Define layout and make available to BaseController and UsersController
    *  classes.
    */
    protected $layout = 'layouts.main';

    /* Set all POST requests to require CSRF token.   Also filter all requests
    *  to ensure the user is logged in, with the exception of the 
    *  login page and register page, and the signin and create methods.
    */
    public function __construct()
    {
        $this->beforeFilter('CSRF', array('on' => 'post'));
        $this->beforeFilter('auth', array('except' => array('getLogin', 'postSignin', 'getRegister', 'postCreate')));
    }

    /* Return a view to the user to login*/
    public function getLogin()
    {
        $this->layout->content = View::make('users/login');
    }

    /* Receive input from login page and attempt to validate, true value passed 
    *  at end of Auth::attempt is needed to allow the "remember me" action to work.
    *  If attempt passes redirect to posts/index with a success message.
    */
    public function postSignin()
    {
        if (Auth::attempt(array('email' => Input::get('email'), 
                                'password' => Input::get('password')),
                                 true)
            ) {
            return Redirect::to('posts/index')
                           ->with('message', 'You are now logged in!');
        } else {
            return Redirect::to('users/login')
                           ->with('message', 'Your username/password was incorrect')
                           ->withInput();
        }
    }

    /* Log a user out, redirects to logout page with success message.*/
    public function getLogout()
    {
        Auth::logout();

        return Redirect::to('users/login')
                       ->with('message', 'You have been logged out.');
    }

    /* Return a view to create new users.*/
    
    public function getRegister() {
    if ($_ENV['NEW_USERS']) {
        $this->layout->content = View::make('users.register');
        }
    else {
        return Redirect::to('users/login');
        }
    }
    
    /* Save database information for new users and return a success message.  
    */
    
    public function postCreate() {
        if ($_ENV['NEW_USERS']) {
            $user = new User;
            $user->username = Input::get('username');
            $user->email = Input::get('email');
            $user->password = Hash::make(Input::get('password'));
            $user->save();

            return Redirect::to('users/login')->with('message', 'Thanks for registering!')
                                              ->with('$user');
        }
    }
    

    /* Return a view displaying the settings page for a user.*/
    public function getSettings()
    {
        $this->layout->content = View::make('users.settings');
    }

    /* Check if username has been changed, and don't pass it to the validator if 
    *  it hasn't been changed. This prevents the user from getting an error for 
    *  the username already being taken if they change settings without changing
    *  username.  The values from the form are checked against the validation 
    *  rules defined in the User model.  If validation is successful, database 
    *  update database values with values from the corresponding form fields.  
    *  Return a success message if successful.  
    */
    public function postSettingsUpdate()
    {
        if (Input::get('username') == Auth::user()->username) {
            $validator = Validator::make(Input::except('username'), User::$userRules);
        } else {
            $validator = Validator::make(Input::all(), User::$userRules);
        }
        if ($validator->passes()) {
            $user = Auth::user();
            $user->username = Input::get('username');
            $user->email_new_post = Input::get('emailnewpost');
            $user->email_new_comment = Input::get('emailnewcomment');
            $user->save();

            return Redirect::to('users/settings')
                           ->with('usermessage', 'Your settings have been saved '.$user->username.'!');
        } else {
            return Redirect::to('users/settings')
                           ->with('usermessage', 'The following errors occured:')
                           ->withErrors($validator)
                           ->withInput();
        }
    }

    /* Check the old password to make sure it is correct, if that passes check 
    *  password against the validation rules defined in the User model.  
    *  If valdiation passes, update password field and save. User is redirected 
    *  to settings page with a success message.
    */
    public function postChangePassword()
    {
        if (Hash::check(Input::get('oldPassword'), Auth::user()->password)) {
            $validator = Validator::make(Input::all(), User::$passwordRules);

            if ($validator->passes()) {
                $user = Auth::user();
                $user->password = Hash::make(Input::get('password'));
                $user->save();

                return Redirect::to('users/settings')
                               ->with('passwordmessage', 'Your password has been changed.');
            } else {
                return Redirect::to('users/settings')
                               ->with('passwordmessage', 'The following errors occured:')
                               ->withErrors($validator)
                               ->withInput();
            }
        } else {
            return Redirect::to('users/settings')
                           ->with('passwordmessage', 'Incorrect password.');
        }
    }

    /* Retrieve all posts for a given user_id in descending order and paginated.
    *  Alter content section of main.blade.php with profileofposts.blade.php.
    */
    public function getProfileOfPosts($user_id)
    {
        $user = User::find($user_id);
        $posts = $user->posts()->orderBy('created_at', 'desc')->paginate(20);
        $this->layout->content = View::make('users/profileofposts')
                              ->with(array('posts' => $posts, 'user' => $user));
    }

    /* Retrieve all comments for a given user_id in descending order and paginated.  
    *  Alter content section of main.blade.php with profileofcomments.blade.php view.
    */
    public function getProfileOfComments($user_id)
    {
        $user = User::find($user_id);
        $comments = $user->comments()->orderBy('created_at', 'desc')->paginate(20);
        $this->layout->content = View::make('users/profileofcomments')
                                     ->with(array('comments' => $comments, 
                                                  'user' => $user)
                                    );
    }

    /* Retrieve all uploads for a given user_id in descending order and paginated.  
    *  Alter content section of main.blade.php with profileofuploads.blade.php view.
    */
    public function getProfileOfUploads($user_id)
    {
        $user = User::find($user_id);
        $uploads = $user->uploads()->orderBy('created_at', 'desc')->paginate(12);
        $this->layout->content = View::make('users/profileofuploads')
                                    ->with(array('uploads' => $uploads, 
                                                 'user' => $user)
                                    );
    }
}
