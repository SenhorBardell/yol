<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddMessageIdFieldToEmergencies extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('emergencies', function(Blueprint $table) {
			$table->bigInteger('message_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('emergencies', function(Blueprint $table) {
			$table->dropColumn('message_id');
		});
	}

}
