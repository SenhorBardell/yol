<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddFieldsToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table) {
			$table->boolean('enable_carchats')->default(false);
			$table->boolean('push_pm')->default(false);
			$table->boolean('push_comments')->default(false);
			$table->boolean('push_comment_likes')->default(false);
			$table->boolean('push_post_likes')->default(false);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table) {
			$table->dropColumn('enable_carchats');
			$table->dropColumn('push_pm');
			$table->boolean('push_comments');
			$table->boolean('push_comment_likes');
			$table->boolean('push_post_likes');
		});
	}

}
