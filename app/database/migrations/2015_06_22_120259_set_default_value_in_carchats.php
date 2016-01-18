<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetDefaultValueInCarchats extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
//		DB::statement('TRUNCATE TABLE car_chats');
		DB::statement('ALTER TABLE public.car_chats ALTER COLUMN deleted_by_owner SET DEFAULT FALSE;');
		DB::statement('ALTER TABLE public.car_chats ALTER COLUMN deleted_by_owner SET NOT NULL;');
		DB::statement('ALTER TABLE public.car_chats ALTER COLUMN deleted_by_receiver SET DEFAULT FALSE;');
		DB::statement('ALTER TABLE public.car_chats ALTER COLUMN deleted_by_receiver SET NOT NULL;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		DB::statement('ALTER TABLE public.car_chats ALTER COLUMN deleted_by_owner DROP NOT NULL;');
		DB::statement('ALTER TABLE public.car_chats ALTER COLUMN deleted_by_receiver DROP NOT NULL;');
	}

}
