<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLikesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table likes alter column likeable_type type varchar(7)');
		Schema::table('likes', function (Blueprint $table) {
			$table->dropColumn('updated_at');
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
		DB::statement('alter table likes alter column likeable_type type varchar(255)');
		Schema::table('likes', function (Blueprint $table) {
			$table->timestamp('updated_at')->nullable();
			$table->dropForeign('likes_user_id_foreign');
		});
	}

}
