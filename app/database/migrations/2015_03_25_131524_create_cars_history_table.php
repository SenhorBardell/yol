<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarsHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cars_history', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('user_id');
			$table->integer('mark')->nullable();
			$table->integer('model')->nullable();
			$table->integer('body_type')->nullable();
			$table->integer('vehicle_type')->nullable();
			$table->integer('year')->nullable();
			$table->integer('color')->nullable();
			$table->string('number')->nullable();
			$table->timestamp('deleted_at')->nullable();
		});
		Schema::table('cars_history', function (Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->foreign('mark')->references('id')->on('mark_refs')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->foreign('model')->references('id')->on('model_refs')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->foreign('body_type')->references('id')->on('body_type_refs')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->foreign('vehicle_type')->references('id')->on('vehicle_type_refs')->onDelete('RESTRICT')->onUpdate('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cars_history');
	}

}
