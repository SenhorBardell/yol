<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToChatsClearedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('chats_cleared', function(Blueprint $table)
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
		Schema::table('chats_cleared', function(Blueprint $table)
		{
			$table->dropForeign('chats_cleared_chat_id_foreign');
			$table->dropForeign('chats_cleared_message_id_foreign');
		});
	}

}
