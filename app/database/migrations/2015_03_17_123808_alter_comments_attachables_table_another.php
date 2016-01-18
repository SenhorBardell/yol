<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCommentsAttachablesTableAnother extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table comments_attachables ALTER column attachable_type type varchar(10)');
		DB::statement('alter table attachables alter column attachable_type type varchar(10)');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('alter table attachables alter column attachable_type type varchar(10)');
		DB::statement('alter table comments_attachables alter column attachable_type type varchar (9)');
	}

}
