<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmergencyMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
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


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('emergency_messages');
	}

}
