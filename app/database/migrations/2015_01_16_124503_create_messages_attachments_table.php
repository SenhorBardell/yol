<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesAttachmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('messages_attachments', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('chat_id');
			$table->string('origin');
			$table->string('thumb');
			$table->integer('width')->nullable();
			$table->integer('height')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('messages_attachments');
	}

}
