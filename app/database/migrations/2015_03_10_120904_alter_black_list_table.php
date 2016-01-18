<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBlackListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('black_list', function (Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->foreign('blocked_user_id')->references('id')->on('users')->onDelete('RESTRICT')->onUpdate('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('black_list', function (Blueprint $table) {
			$table->dropForeign('black_list_blocked_user_id_foreign');
			$table->dropForeign('black_list_user_id_foreign');
		});
	}

}
