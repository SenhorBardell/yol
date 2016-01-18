<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateModelRefsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('model_refs', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->bigInteger('mark_id');
			$table->string('name');
			$table->timestamps();
			$table->integer('vehicle_type_id')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('model_refs');
	}

}
