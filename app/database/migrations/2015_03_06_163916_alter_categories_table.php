<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table categories alter column parent_id drop not null');
		DB::statement('update categories set parent_id = NULL where parent_id = 0');
		Schema::table('categories', function (Blueprint $table) {
			$table->dropTimestamps();
			$table->foreign('parent_id')->references('id')->on('categories')->onUpdate('RESTRICT')->onDELETE('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('alter table categories alter column parent_id set not null');
		DB::statement('update categories set parent_id = 0 where parent_id is NULL ');
		Schema::table('categories', function (Blueprint $table) {
			$table->timestamps();
			$table->dropForeign('categories_parent_id_foreign');
		});
	}

}
