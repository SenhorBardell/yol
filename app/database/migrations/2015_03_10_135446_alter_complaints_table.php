<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterComplaintsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('complaints', function (Blueprint $table) {
			$table->foreign('owner_id')->references('id')->on('users')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->foreign('post_id')->references('id')->on('posts')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->foreign('comment_id')->references('id')->on('comments')->onDelete('RESTRICT')->onUpdate('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('complaints', function (Blueprint $table) {
			$table->dropForeign('complaints_comment_id_foreign');
			$table->dropForeign('complaints_owner_id_foreign');
			$table->dropForeign('complaints_post_id_foreign');
			$table->dropForeign('complaints_user_id_foreign');
		});
	}

}
