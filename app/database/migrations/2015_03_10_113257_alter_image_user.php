<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterImageUser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('image_user', function (Blueprint $table) {
			$table->foreign('image_id')->references('id')->on('images')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT')->onUpdate('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('image_user', function (Blueprint $table) {
			$table->dropForeign('image_user_image_id_foreign');
			$table->dropForeign('image_user_user_id_foreign');
		});
	}

}
