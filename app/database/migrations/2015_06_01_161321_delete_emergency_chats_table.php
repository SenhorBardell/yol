<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class DeleteEmergencyChatsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('emergency_chats');
		Schema::drop('emergency_messages');
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('emergency_chats', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('sender_id');
			$table->bigInteger('car_id');
			$table->boolean('hidden')->default(true);
			$table->bigInteger('receiver_id');
		});

		Schema::create('emergency_messages', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('text');
			$table->string('status');
			$table->timestamps();
			$table->enum('who', ['sender', 'receiver']);
			$table->bigInteger('emergency_chat_id');
		});
	}

}
