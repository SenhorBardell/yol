<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterStatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('states', function(Blueprint $table)
		{
			DB::table('states')->truncate();
			Schema::table('states', function(Blueprint $table) {
				$table->dropcolumn('object');
				$table->dropcolumn('event');
			});
			Schema::table('states', function(Blueprint $table) {
				$table->enum('object', ['post', 'comment']);
				$table->enum('event', ['liked', 'commented']);
			});
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('states', function(Blueprint $table)
		{
			DB::table('states')->truncate();
			Schema::table('states', function(Blueprint $table) {
				$table->dropcolumn('object');
				$table->dropcolumn('event');
			});
			Schema::table('states', function (Blueprint $table) {
				$table->string('object');
				$table->string('event');
			});
		});
	}

}
