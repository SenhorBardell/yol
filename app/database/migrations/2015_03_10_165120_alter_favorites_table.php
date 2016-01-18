<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFavoritesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('favorites', function (Blueprint $table) {
			$table->dropColumn('updated_at');
			$table->foreign('post_id')->references('id')->on('posts')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT')->onUpdate('RESTRICT');
		});
		DB::statement('alter table favorites drop column id');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('favorites', function (Blueprint $table) {
			$table->timestamp('updated_at');
			$table->dropForeign('favorites_post_id_foreign');
			$table->dropForeign('favorites_user_id_foreign');
		});
		DB::statement('alter table favorites add column id serial;'.
			' update favorites set id = default;' .
			' alter table favorites add primary key (id)');
	}

}
