<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToChatsMembersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('chats_members', function(Blueprint $table)
		{
			$table->foreign('chat_id')->references('id')->on('chats')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('chats_members', function(Blueprint $table)
		{
			$table->dropForeign('chats_members_chat_id_foreign');
		});
	}

}
