<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveTextSize extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		DB::statement('ALTER TABLE car_messages ALTER COLUMN text TYPE VARCHAR USING text::VARCHAR;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		DB::statement('ALTER TABLE car_messages ALTER COLUMN text TYPE VARCHAR(255) USING text::VARCHAR(255);');
	}

}
