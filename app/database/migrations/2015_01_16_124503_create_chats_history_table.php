<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChatsHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chats_history', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('chat_id');
			$table->string('event');
			$table->dateTime('timestamp')->default('now()');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('chats_history');
	}

}
