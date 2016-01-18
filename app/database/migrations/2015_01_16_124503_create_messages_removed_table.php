<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesRemovedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('messages_removed', function(Blueprint $table)
		{
			$table->bigInteger('message_id');
			$table->dateTime('timestamp')->default('now()');
			$table->integer('user_id');
			$table->bigInteger('chat_id')->nullable();
			$table->primary(['message_id','user_id'], 'messages_removed_pkey');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('messages_removed');
	}

}
