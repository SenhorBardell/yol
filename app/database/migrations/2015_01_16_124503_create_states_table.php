<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('states', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('object');
			$table->integer('object_id');
			$table->string('event');
			$table->integer('user_id');
			$table->dateTime('timestamp')->default('now()');
			$table->integer('owner_id');
			$table->integer('subject_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('states');
	}

}
