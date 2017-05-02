<?php
class Comment extends Eloquent {
	public static $commentRules = array(
		'commentText'=>'required|between:1,5000'
		);

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'comments';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */


	
	public function post()
	{
		return $this->belongsTo('Post', 'post_id', 'id');
	}

	public function user()
	{
		return $this->belongsTo('User', 'user_id', 'id');
	}

}