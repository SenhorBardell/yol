<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCarmessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_messages', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('chat_id');
			$table->bigInteger('user_id');
			$table->bigInteger('user_car_id')->nullable();
			$table->boolean('via_car')->default(false);
			$table->timestamp('created_at')->default(\Carbon\Carbon::now());
			$table->timestamp('delivered_at')->nullable();
			$table->time('viewed_at')->nullable();
			$table->bigInteger('image_id')->nullable();
			$table->string('lat')->limit(10)->nullable();
			$table->string('long')->limit(10)->nullable();
			$table->string('location')->nullable();
			$table->bigInteger('car_id')->nullable();
			$table->string('text')->nullable();
			$table->boolean('deleted_by_owner')->nullable();
			$table->boolean('deleted_by_receiver')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('car_messages');
	}

}
