<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCommentsAttachablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table comments_attachables alter column id type bigint');
		DB::statement('alter table comments_attachables alter column postable_id type bigint');
		DB::statement('alter table comments_attachables alter column attachable_id type bigint');
		DB::statement('alter table comments_attachables alter column attachable_type type varchar(9)');
		Schema::table('comments_attachables', function (Blueprint $table) {
			$table->foreign('postable_id')->references('id')->on('comments')->onDelete('RESTRICT')->onUpdate('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('alter table comments_attachables alter column id type int');
		DB::statement('alter table comments_attachables alter column postable_id type int');
		DB::statement('alter table comments_attachables alter column attachable_id type int');
		DB::statement('alter table comments_attachables alter column attachable_type type varchar(255)');
		Schema::table('comments_attachables', function (Blueprint $table) {
			$table->dropForeign('comments_attachables_postable_id_foreign');
		});
	}

}
