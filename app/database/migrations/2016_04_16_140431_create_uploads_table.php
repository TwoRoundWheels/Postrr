<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadsTable extends Migration {

	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('uploads', function($table)	
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('post_id')->nullable()->unsigned();
			$table->string('filename_saved_as', 20);
			$table->string('original_name', 250);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('uploads');	
	}

}
