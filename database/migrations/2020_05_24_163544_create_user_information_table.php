<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->text("education")->nullable();
            $table->text("work")->nullable();
            $table->text("address")->nullable();
            $table->text("about")->nullable();
            $table->text("bio")->nullable();
            $table->text("emails")->nullable();
            $table->text("ph_numbers")->nullable();
            $table->text("websites")->nullable();
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
        Schema::dropIfExists('user_information');
    }
}
