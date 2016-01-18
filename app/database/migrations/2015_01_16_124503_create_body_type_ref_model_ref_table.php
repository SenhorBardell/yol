<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBodyTypeRefModelRefTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('body_type_ref_model_ref', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('model_ref_id');
			$table->integer('body_type_ref_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('body_type_ref_model_ref');
	}

}
