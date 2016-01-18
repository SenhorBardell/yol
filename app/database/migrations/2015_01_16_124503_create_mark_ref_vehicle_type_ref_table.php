<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMarkRefVehicleTypeRefTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mark_ref_vehicle_type_ref', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('vehicle_type_ref_id');
			$table->integer('mark_ref_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mark_ref_vehicle_type_ref');
	}

}
