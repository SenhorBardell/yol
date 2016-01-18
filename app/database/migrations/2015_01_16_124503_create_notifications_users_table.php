<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications_users', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('notification_id');
			$table->dateTime('timestamp')->default('now()');
			$table->integer('user_id');
			$table->integer('subject_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notifications_users');
	}

}
