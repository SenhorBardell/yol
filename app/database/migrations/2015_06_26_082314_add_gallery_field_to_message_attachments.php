<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddGalleryFieldToMessageAttachments extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('messages_attachments', function(Blueprint $table) {
			$table->string('gallery')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('messages_attachments', function(Blueprint $table) {
			$table->dropColumn('gallery');
		});
	}

}
