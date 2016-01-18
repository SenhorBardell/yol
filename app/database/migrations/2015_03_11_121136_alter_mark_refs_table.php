<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMarkRefsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table mark_refs alter column vehicle_type_id drop not null');
		DB::statement('update mark_refs set vehicle_type_id=null where vehicle_type_id = 0');
		Schema::table('mark_refs', function (Blueprint $table) {
			$table->dropTimestamps();
			$table->foreign('vehicle_type_id')->references('id')->on('vehicle_type_refs')->onDelete('RESTRICT')->onUpdate('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('alter table mark_refs alter column vehicle_type_id set not null');
		Schema::table('mark_refs', function(Blueprint $table) {
			$table->timestamps();
			$table->dropForeign('mark_refs_vehicle_type_id_foreign');
		});
	}

}
