<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRoleUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('role_user', function (Blueprint $table) {
			$table->dropColumn('id');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->foreign('role_id')->references('id')->on('roles')->onDelete('RESTRICT')->onUpdate('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('role_user', function (Blueprint $table) {
			$table->increments('id');
			$table->dropForeign('role_user_role_id_foreign');
			$table->dropForeign('role_user_user_id_foreign');
		});
	}

}
