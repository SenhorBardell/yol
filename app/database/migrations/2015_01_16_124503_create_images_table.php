<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('images', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('thumbnail');
			$table->string('regular');
			$table->softDeletes();
			$table->integer('width')->nullable();
			$table->integer('height')->nullable();
			$table->integer('imageable_id')->nullable();
			$table->string('imageable_type')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('images');
	}

}
