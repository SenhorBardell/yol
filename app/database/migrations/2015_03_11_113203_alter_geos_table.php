<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGeosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table geos alter column id type bigint');
		DB::statement('alter table geos alter column long type varchar(16)');
		DB::statement('alter table geos alter column lat type varchar(16)');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('alter table geos alter column id type int');
		DB::statement('alter table geos alter column long type varchar(255)');
		DB::statement('alter table geos alter column lat type varchar(255)');
	}

}
