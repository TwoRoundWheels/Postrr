<?php
class Post extends Eloquent {
	public static $postRules = array(
		'title'=>'required|alpha_num_spaces|between:2,300',
		'link'=>'max:500',
		'description'=>'max:5000'
		);

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posts';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public function user()
	{
		return $this->belongsTo('User', 'user_id', 'id');
	}

	public function comments()
	{
		return $this->hasMany('Comment', 'post_id', 'id');
	}

	public function uploads()
	{
			return $this->hasMany('Upload', 'post_id', 'id');
	}
}
