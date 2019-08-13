<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProtocolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocols', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('protocol_types_id')->default(0);
            $table->string('title', 255)->default(null);
            $table->string('subject', 255)->default(null);
            $table->dateTime('start')->default(null);
            $table->dateTime('end')->default(null);
            $table->dateTime('register')->default(null);
            $table->string('number', 255)->default(null);
            $table->integer('employer_id')->default(0);
            $table->integer('contractor_id')->default(0);
            $table->integer('total')->default(0);
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
        Schema::dropIfExists('protocols');
    }
}
