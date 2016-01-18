<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarChatMessageAttachmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_chat_message_attachments', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('chat_id');
			$table->string('origin');
			$table->integer('width');
			$table->integer('height');
			$table->string('thumb');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('car_chat_message_attachments');
	}

}
