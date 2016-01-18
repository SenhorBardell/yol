<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name');
			$table->string('email')->nullable();
			$table->integer('phone_id')->default(0);
			$table->integer('car_id')->default(0);
			$table->date('birthday')->nullable();
			$table->integer('city')->nullable();
			$table->smallInteger('sex')->nullable();
			$table->text('about')->nullable();
			$table->string('password');
			$table->boolean('completed')->default('0');
			$table->boolean('blocked')->default('0');
			$table->dateTime('last_login')->nullable();
			$table->integer('urgent_calls')->default(0);
			$table->string('img_origin')->default('https://s3-us-west-2.amazonaws.com/yolanothertest/placeholder_128.png');
			$table->string('img_middle')->default('https://s3-us-west-2.amazonaws.com/yolanothertest/placeholder_128.png');
			$table->string('img_small')->default('https://s3-us-west-2.amazonaws.com/yolanothertest/placeholder_64.png');
			$table->timestamps();
			$table->boolean('show_phone')->default(true);
			$table->boolean('show_email')->default(false);
			$table->boolean('show_car_number')->default(true);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
