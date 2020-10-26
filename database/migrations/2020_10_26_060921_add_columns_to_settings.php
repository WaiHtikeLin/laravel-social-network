<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
          $table->dropColumn('id');
          $table->string('friends_privacy')->default('public')->after('user_id');
          $table->string('followers_privacy')->default('public')->after('friends_privacy');
          $table->string('following_privacy')->default('public')->after('followers_privacy');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
        });
    }
}
