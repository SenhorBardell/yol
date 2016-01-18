<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDevicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table devices alter column phone type varchar(20)');
		Schema::table('devices', function (Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->dropTimestamps();
			$table->dropColumn('deleted_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('alter table devices alter column phone type bigint using phone::int');
		Schema::table('devices', function (Blueprint $table) {
			$table->dropForeign('devices_user_id_foreign');
			$table->timestamps();
			$table->timestamp('deleted_at');
		});
	}

}
