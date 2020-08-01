<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ChatMessages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('room_id');
            $table->foreignId("user_id")->constrained();
            $table->foreignId("sender_id")->constrained('users');
            $table->string('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ChatMessages');
    }
}
