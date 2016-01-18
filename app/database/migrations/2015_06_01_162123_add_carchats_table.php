<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCarchatsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_chats', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('owner_id');
			$table->bigInteger('receiver_id');
			$table->bigInteger('receiver_car_id');
			$table->bigInteger('last_message_id')->nullable();
			$table->string('number');
			$table->timestamps();
			$table->boolean('deleted_by_owner')->nullable();
			$table->boolean('deleted_by_receiver')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('car_chats');
	}

}
