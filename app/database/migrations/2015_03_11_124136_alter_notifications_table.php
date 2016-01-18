<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNotificationsTable2 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('notifications', function (Blueprint $table) {
			$table->foreign('owner_id')->references('id')->on('users')->onDelete('RESTRICT')->onUpdate('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('notifications', function (Blueprint $table) {
			$table->dropForeign('notifications_owner_id_foreign');
		});
	}

}
