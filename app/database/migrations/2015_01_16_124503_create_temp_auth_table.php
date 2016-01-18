<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTempAuthTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('temp_auth', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('token');
			$table->string('device');
			$table->bigInteger('phone');
			$table->integer('code');
			$table->dateTime('sent_at')->nullable();
			$table->boolean('verified')->default('0');
			$table->string('captcha')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('temp_auth');
	}

}
