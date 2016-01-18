<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChatsMembersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chats_members', function(Blueprint $table)
		{
			$table->bigInteger('chat_id');
			$table->integer('user_id');
			$table->primary(['chat_id','user_id'], 'chats_members_pkey');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('chats_members');
	}

}
