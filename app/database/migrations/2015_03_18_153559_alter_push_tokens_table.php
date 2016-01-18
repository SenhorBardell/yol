<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPushTokensTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('push_tokens', function (Blueprint $table) {
			$table->foreign('device_id')->references('id')->on('devices')->onDelete('RESTRICT')->onUpdate('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('push_tokens', function (Blueprint $table) {
			$table->dropForeign('push_tokens_device_id_foreign');
		});
	}

}
