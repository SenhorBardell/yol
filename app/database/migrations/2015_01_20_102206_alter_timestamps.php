<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterTimestamps extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('chats_history', function (Blueprint $table) {
			if (Schema::hasColumn('chats_history', 'timestamp')) $table->dropColumn('timestamp');
		});
		Schema::table('chats', function (Blueprint $table) {
			if (Schema::hasColumn('chats', 'timestamp')) $table->dropColumn('timestamp');
		});
		Schema::table('complaints', function (Blueprint $table) {
			if (Schema::hasColumn('complaints', 'timestamp')) $table->dropColumn('timestamp');
		});
		Schema::table('messages_removed', function (Blueprint $table) {
			if (Schema::hasColumn('messages_removed', 'timestamp')) $table->dropColumn('timestamp');
		});
		Schema::table('messages', function (Blueprint $table) {
			if (Schema::hasColumn('messages', 'timestamp')) $table->dropColumn('timestamp');
		});
		Schema::table('notifications', function (Blueprint $table) {
			if (Schema::hasColumn('notifications', 'timestamp')) $table->dropColumn('timestamp');
		});
		Schema::table('notifications_users', function (Blueprint $table) {
			if (Schema::hasColumn('notifications_users', 'timestamp')) $table->dropColumn('timestamp');
		});
		Schema::table('states', function (Blueprint $table) {
			if (Schema::hasColumn('states', 'timestamp')) $table->dropColumn('timestamp');
		});

		Schema::table('chats_history', function (Blueprint $table) {
			$table->dateTime('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
		Schema::table('chats', function (Blueprint $table) {
			$table->dateTime('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
		Schema::table('complaints', function (Blueprint $table) {
			$table->dateTime('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
		Schema::table('messages_removed', function (Blueprint $table) {
			$table->dateTime('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
		Schema::table('messages', function (Blueprint $table) {
			$table->dateTime('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
		Schema::table('notifications', function (Blueprint $table) {
			$table->dateTime('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
		Schema::table('notifications_users', function (Blueprint $table) {
			$table->dateTime('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
		Schema::table('states', function (Blueprint $table) {
			$table->dateTime('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	}

}
