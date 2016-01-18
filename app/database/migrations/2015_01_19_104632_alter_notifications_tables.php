<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterNotificationsTables extends Migration {

    public function up() {
        Schema::table('notifications', function (Blueprint $table) {
            $table->tinyInteger('is_removed')
                  ->default('0');
        });

        Schema::table('notifications_users', function (Blueprint $table) {
            $table->tinyInteger('is_removed')
                  ->default('0');
        });
    }

    public function down() {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('is_removed');
        });

        Schema::table('notifications_users', function (Blueprint $table) {
            $table->dropColumn('is_removed');
        });
    }
}
