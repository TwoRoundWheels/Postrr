<?php
/*Custom validators
*/

/*  Custom validator to allow spaces.  Laravel's alpha_num doesn't 
allow spaces.  \pL allows any unicode character, \s allows spaces, 
0-9 allows 0-9.  Commas, periods, quotes, and apostrophes added too.  
*/

Validator::extend('alpha_num_spaces', function($attribute, $value)
	{
	    return preg_match('/^[\pL\s0-9,\.\'\"\?\!\_]+$/u', $value);
	});

/*  Custom validator to prevent uploading of files with more than one "." in
* in the file name.
*/

Validator::extend('has_one_dot', function($attribute, $value, $parameters)
	{	
		return preg_match('/^[a-zA-Z0-9\-\_ ,]*\.[a-zA-Z0-9,]*$/', $value);
	});