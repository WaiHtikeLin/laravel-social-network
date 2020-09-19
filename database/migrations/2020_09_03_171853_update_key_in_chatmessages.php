<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateKeyInChatmessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ChatMessages', function (Blueprint $table) {
          $table->dropPrimary('ChatMessages_id_primary');
          $table->primary(['id','user_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ChatMessages', function (Blueprint $table) {
            //
        });
    }
}
