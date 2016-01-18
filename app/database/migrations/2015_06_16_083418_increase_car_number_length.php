<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseCarNumberLength extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		DB::statement('ALTER TABLE public.cars ALTER COLUMN number TYPE VARCHAR(8) USING number::VARCHAR(8);');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		DB::statement('ALTER TABLE public.cars ALTER COLUMN number TYPE VARCHAR(8) USING number::VARCHAR(7);');
	}

}
