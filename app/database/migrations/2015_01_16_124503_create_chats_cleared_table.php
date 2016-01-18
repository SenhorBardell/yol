<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChatsClearedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chats_cleared', function(Blueprint $table)
		{
			$table->bigInteger('chat_id');
			$table->integer('user_id');
			$table->bigInteger('message_id');
			$table->primary(['chat_id','user_id'], 'chats_cleared_pkey');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('chats_cleared');
	}

}
