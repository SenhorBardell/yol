<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateComplaintsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('complaints', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('owner_id');
			$table->integer('post_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('comment_id')->nullable();
			$table->dateTime('timestamp')->default('now()');
			$table->string('type')->default('spam');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('complaints');
	}

}
