<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fname', 255)->nullable();
            $table->string('lname', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('birth_certificate', 20)->nullable();
            $table->string('national_number', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('tells', 100)->nullable();
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
        Schema::dropIfExists('agents');
    }
}
