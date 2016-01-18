<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table posts alter column id type bigint');
		Schema::table('posts', function (Blueprint $table) {
			$table->foreign('category_id')->references('id')->on('categories')->onDelete('RESTRICT')->onUpdate('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('alter table posts alter column id type int');
		Schema::table('posts', function (Blueprint $table) {
			$table->dropForeign('posts_category_id_foreign');
		});
	}

}
