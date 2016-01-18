<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPhonesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table phones alter column id type bigint');
		DB::statement('alter table phones alter column number type varchar(20)');
		Schema::table('phones', function (Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT')->onUpdate('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('alter table phones alter column id type int');
		DB::statement('alter table phones alter column number type varchar(255)');
		Schema::table('phones', function (Blueprint $table) {
			$table->dropForeign('phones_user_id_foreign');
		});
	}

}
