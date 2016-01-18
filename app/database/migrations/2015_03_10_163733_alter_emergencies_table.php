<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEmergenciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table emergencies alter column id type bigint');
		DB::statement('alter table emergencies alter column number type varchar(7)');
		Schema::table('emergencies', function (Blueprint $table) {
			$table->foreign('sender')->references('id')->on('users');
			$table->dropColumn('updated_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('alter table emergencies alter column id type int');
		DB::statement('alter table emergencies alter column number type varchar(255)');
		Schema::table('emergencies', function (Blueprint $table) {
			$table->dropForeign('emergencies_sender_foreign');
			$table->timestamp('updated_at');
		});
	}

}
