<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId("comment_id")->constrained()
            ->onDelete('cascade')->onUpdate('cascade');
            $table->text("texts")->nullable();
            $table->text("pics")->nullable();
            $table->text("videos")->nullable();
            $table->text("miscs")->nullable();
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
        Schema::dropIfExists('replies');
    }
}
