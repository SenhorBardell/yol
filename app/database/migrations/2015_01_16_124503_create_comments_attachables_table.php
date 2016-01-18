<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsAttachablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments_attachables', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('postable_id');
			$table->integer('attachable_id');
			$table->string('attachable_type');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comments_attachables');
	}

}
