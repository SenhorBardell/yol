<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterNotificationsUsersTable extends Migration {

    public function up() {
        DB::table('notifications_users')
          ->truncate();

        Schema::table('notifications_users', function (Blueprint $table) {
            $table->dropForeign('notifications_users_notification_id_foreign');
        });

        DB::table('notifications')
          ->truncate();

        Schema::table('notifications_users', function (Blueprint $table) {
            $table->foreign('notification_id')
                  ->references('id')
                  ->on('notifications')
                  ->onDelete('restrict');
            $table->enum('subject', array('comment', 'like'));
        });
    }

    public function down() {
        Schema::table('notifications_users', function (Blueprint $table) {
            $table->dropColumn('subject');
        });
    }
}
