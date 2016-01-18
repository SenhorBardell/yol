<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table roles alter column id type smallint');
		DB::statement('alter table roles alter column name type varchar(20)');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('alter table roles alter column id type int');
		DB::statement('alter table roles alter column name type varchar(255)');
	}

}
