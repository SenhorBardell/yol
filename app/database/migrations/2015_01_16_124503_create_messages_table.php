<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('messages', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('chat_id');
			$table->integer('user_id');
			$table->dateTime('timestamp')->default('now()');
			$table->text('text')->nullable();
			$table->bigInteger('image_id')->nullable();
			$table->integer('car_id')->nullable();
			$table->string('car_number', 16)->nullable();
			$table->string('lat', 10)->nullable();
			$table->string('lng', 10)->nullable();
			$table->text('location')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('messages');
	}

}
