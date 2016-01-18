<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE users ALTER COLUMN id TYPE BIGINT');
		Schema::table('users', function (Blueprint $table) {
			$table->foreign('city')->references('id')->on('city_refs')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('phone_id')->references('id')->on('phones')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('alter table users alter column id type int');
		Schema::table('users', function (Blueprint $table) {
			$table->dropForeign('users_phone_id_foreign');
			$table->dropForeign('users_city_foreign');
		});
	}

}
