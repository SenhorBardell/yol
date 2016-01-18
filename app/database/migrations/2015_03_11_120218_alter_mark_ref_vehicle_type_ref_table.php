<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMarkRefVehicleTypeRefTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('mark_ref_vehicle_type_ref', function (Blueprint $table) {
			$table->foreign('vehicle_type_ref_id')->references('id')->on('vehicle_type_refs')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->foreign('mark_ref_id')->references('id')->on('mark_refs')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->dropColumn('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('mark_ref_vehicle_type_ref', function (Blueprint $table) {
			$table->increments('id');
			$table->dropForeign('mark_ref_vehicle_type_ref_mark_ref_id_foreign');
			$table->dropForeign('mark_ref_vehicle_type_ref_vehicle_type_ref_id_foreign');
		});
	}

}
