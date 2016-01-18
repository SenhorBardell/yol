<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCityRefsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table city_refs drop column created_at');
		DB::statement('alter table city_refs drop column updated_at');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('alter table city_refs add column created_at timestamp');
		DB::statement('alter table city_refs add column updated_at timestamp');
	}

}
