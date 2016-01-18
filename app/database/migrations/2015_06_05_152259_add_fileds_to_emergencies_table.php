<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddFiledsToEmergenciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('emergencies', function(Blueprint $table) {
			$table->bigInteger('receiver');
			$table->timestamp('delivered_at')->nullable();
			$table->string('status');
			$table->dropColumn('text');
			$table->boolean('via_sms')->default(false);
//			$table->timestamp('complained_at')->nullable();
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
			$table->string('text');
			$table->dropColumn('delivered_at');
			$table->dropColumn('status');
			$table->dropColumn('receiver');
			$table->dropColumn('via_sms');
			$table->dropColumn('complained_at');
		});
	}

}
