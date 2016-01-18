<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToNotificationsUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('notifications_users', function(Blueprint $table)
		{
			$table->foreign('notification_id')->references('id')->on('notifications')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('notifications_users', function(Blueprint $table)
		{
			$table->dropForeign('notifications_users_notification_id_foreign');
		});
	}

}
