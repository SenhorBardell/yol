<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesUnreadTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('messages_unread', function(Blueprint $table)
		{
			$table->bigInteger('chat_id');
			$table->bigInteger('message_id');
			$table->integer('user_id');
			$table->primary(['message_id','user_id'], 'messages_unread_pkey');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('messages_unread');
	}

}
