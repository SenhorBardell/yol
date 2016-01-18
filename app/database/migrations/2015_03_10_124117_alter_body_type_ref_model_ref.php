<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBodyTypeRefModelRef extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('body_type_ref_model_ref', function (Blueprint $table) {
			$table->foreign('model_ref_id')->references('id')->on('model_refs')->onDelete('RESTRICT')->onUpdate('RESTRICT');
			$table->foreign('body_type_ref_id')->references('id')->on('body_type_refs')->onDelete('RESTRICT')->onUpdate('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('body_type_ref_model_ref', function (Blueprint $table) {
			$table->dropForeign('body_type_ref_model_ref_body_type_ref_id_foreign');
			$table->dropForeign('body_type_ref_model_ref_model_ref_id_foreign');
		});
	}

}
