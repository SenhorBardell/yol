<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('truncate notifications cascade');
		Schema::table('notifications', function(Blueprint $table) {
			if (Schema::hasColumn('notifications', 'object')) $table->dropColumn('object');
			if (Schema::hasColumn('notifications', 'event')) $table->dropcolumn('event');
		});
		Schema::table('notifications', function(Blueprint $table) {
			$table->enum('object', ['post', 'comment']);
			$table->enum('event', ['liked', 'commented']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('truncate notifications cascade');
		Schema::table('notifications', function(Blueprint $table) {
			$table->dropcolumn('object');
			$table->dropcolumn('event');
		});
		Schema::table('notifications', function (Blueprint $table) {
			$table->string('object');
			$table->string('event');
		});
	}

}
