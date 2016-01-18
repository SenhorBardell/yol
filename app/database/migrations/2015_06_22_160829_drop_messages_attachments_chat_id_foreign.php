<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropMessagesAttachmentsChatIdForeign extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('messages_attachments', function(Blueprint $table) {
			$table->dropForeign('messages_attachments_chat_id_foreign');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('messages_attachments', function(Blueprint $table) {
			$table->foreign('chat_id')->references('id')->on('chats')->onDelete('RESTRICT')->onUpdate('RESTRICT');
		});
	}

}
