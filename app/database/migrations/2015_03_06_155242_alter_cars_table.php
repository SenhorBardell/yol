<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCarsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE cars ALTER COLUMN id TYPE BIGINT');
		DB::statement('alter table cars alter column number type varchar(7)');
		Schema::table('cars', function (Blueprint $table) {
			$table->foreign('mark')->references('id')->on('mark_refs')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('model')->references('id')->on('model_refs')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('body_type')->references('id')->on('body_type_refs')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('vehicle_type')->references('id')->on('vehicle_type_refs')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->dropColumn('primary');
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
		DB::statement('alter table cars alter column number type int');
		DB::statement('alter table cars alter column number type varchar(255)');
		Schema::table('cars', function (Blueprint $table) {
			$table->dropForeign('cars_body_type_foreign');
			$table->dropForeign('cars_mark_foreign');
			$table->dropForeign('cars_model_foreign');
			$table->dropForeign('cars_vehicle_type_foreign');
			$table->boolean('primary');
			$table->timestamps();
		});
	}

}
