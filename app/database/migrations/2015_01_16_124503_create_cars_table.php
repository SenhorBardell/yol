<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cars', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id');
			$table->integer('mark')->nullable();
			$table->integer('model')->nullable();
			$table->integer('year')->nullable();
			$table->integer('color')->nullable();
			$table->integer('body_type')->nullable();
			$table->integer('vehicle_type')->nullable();
			$table->string('number')->nullable();
			$table->boolean('primary')->default('0');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cars');
	}

}
