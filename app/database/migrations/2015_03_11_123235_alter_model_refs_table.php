<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterModelRefsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('model_refs', function (Blueprint $table) {
			$table->foreign('mark_id')->references('id')->on('mark_refs')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->foreign('vehicle_type_id')->references('id')->on('vehicle_type_refs')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->dropTimestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('model_refs', function (Blueprint $table) {
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
			$table->dropForeign('model_refs_mark_id_foreign');
			$table->dropForeign('model_refs_vehicle_type_id_foreign');
		});
	}

}
