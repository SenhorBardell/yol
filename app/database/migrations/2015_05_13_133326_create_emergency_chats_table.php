<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmergencyChatsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('emergency_chats', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('sender_id');
			$table->bigInteger('car_id');
			$table->boolean('hidden')->default(true);
			$table->bigInteger('receiver_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('emergency_chats');
	}

}
