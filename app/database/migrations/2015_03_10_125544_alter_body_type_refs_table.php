<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBodyTypeRefsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table body_type_refs drop column created_at');
		DB::statement('alter table body_type_refs drop column updated_at');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('alter table body_type_refs add column created_at timestamp');
		DB::statement('alter table body_type_refs add column updated_at timestamp');
	}

}
