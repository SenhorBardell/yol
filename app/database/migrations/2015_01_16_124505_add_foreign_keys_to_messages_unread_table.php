<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMessagesUnreadTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('messages_unread', function(Blueprint $table)
		{
			$table->foreign('chat_id')->references('id')->on('chats')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('message_id')->references('id')->on('messages')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('messages_unread', function(Blueprint $table)
		{
			$table->dropForeign('messages_unread_chat_id_foreign');
			$table->dropForeign('messages_unread_message_id_foreign');
		});
	}

}
