<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DefaultSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		DB::statement("ALTER TABLE users ALTER COLUMN show_car_number SET DEFAULT FALSE");
		DB::statement("ALTER TABLE users ALTER COLUMN show_phone SET DEFAULT FALSE");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		DB::statement("ALTER TABLE users ALTER COLUMN show_car_number SET DEFAULT TRUE");
		DB::statement("ALTER TABLE users ALTER COLUMN show_phone SET DEFAULT FALSE");
	}

}
