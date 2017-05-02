<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::controller('users', 'UsersController');

Route::controller('posts', 'PostsController');

Route::controller('comments', 'CommentsController');

Route::controller('uploads', 'UploadsController');

Route::controller('password', 'RemindersController');

Route::get('/', function()
{
	return Redirect::to('posts/index');
});




