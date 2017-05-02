<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	public static $passwordRules = array(
		'password'=>'required|alpha_num|between:6,18|confirmed',
		'password_confirmation'=>'required|alpha_num|between:6,18'
		);

	public static $userRules =  array(
		'username'=>'sometimes|required|alpha_num_spaces|between:1,50|Unique:users',
		'emailnewpost'=>'boolean',
		'emailnewcomment'=>'boolean',
		'viewStyle'=>'boolean'
		);

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public function posts()
	{
		return $this->hasMany('Post');
	}


	public function comments()
	{
		return $this->hasMany('Comment', 'user_id', 'id');
	}

	public function uploads()
	{
		return $this->hasMany('Upload', 'user_id', 'id');
	}

}
