<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table posts alter column id type bigint');
		Schema::table('comments', function (Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->foreign('post_id')->references('id')->on('posts')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->dropColumn('attachment_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('alter table posts alter column id type int');
		Schema::table('comments', function (Blueprint $table) {
			$table->dropForeign('comments_post_id_foreign');
			$table->dropForeign('comments_user_id_foreign');
			$table->integer('attachment_id');
		});
	}

}
