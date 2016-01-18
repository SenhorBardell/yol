<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAttachablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table attachables alter column id type bigint');
		DB::statement('alter table attachables alter column postable_id type bigint');
		DB::statement('alter table attachables alter column attachable_id type bigint');
		DB::statement('alter table attachables alter column attachable_type type varchar(9)');
		Schema::table('attachables', function (Blueprint $table) {
			$table->foreign('postable_id')->references('id')->on('posts')->onDelete('RESTRICT')->onUpdate('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('alter table attachables alter column id type int');
		DB::statement('alter table attachables alter column postable_id type int');
		DB::statement('alter table attachables alter column attachable_id type int');
		DB::statement('alter table attachables alter column attachable_type type varchar(255)');
		Schema::table('attachables', function (Blueprint $table) {
			$table->dropForeign('attachables_postable_id_foreign');
		});
	}

}
