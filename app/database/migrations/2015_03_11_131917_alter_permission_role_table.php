<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPermissionRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('permission_role', function (Blueprint $table) {
			$table->foreign('role_id')->references('id')->on('roles')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->foreign('permission_id')->references('id')->on('permissions')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->dropColumn('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('permission_role', function (Blueprint $table) {
			$table->increments('id');
			$table->dropForeign('permission_role_permission_id_foreign');
			$table->dropForeign('permission_role_role_id_foreign');
		});
	}

}
