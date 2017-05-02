<?php
class Upload extends Eloquent {
	public static $descriptionRules = array(
		'title'=>'alpha_num_spaces|between:0,300',
		'description'=>'max:2000'
		);

	public static $extensionRules = array(
		'filename'=>'has_one_dot'
		);

	public static $fileRules = array(	
		'file'=>'mimes:jpg,jpeg,gif,bmp,tiff,asf,avi,divx,qt,mpeg,mpg,mp4,ogg,mkv,wav,wma,wmv,mp3,mpga,png,ogv,oga,webm'
		);

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'uploads';

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