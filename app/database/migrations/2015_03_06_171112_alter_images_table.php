<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table images alter column id type bigint');
		DB::statement('alter table images alter column width type varchar(4)');
		DB::statement('alter table images alter column height type varchar(4)');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('alter table images alter column id type int');
		DB::statement('alter table images alter column width type int');
		DB::statement('alter table images alter column height type int');
	}

}
