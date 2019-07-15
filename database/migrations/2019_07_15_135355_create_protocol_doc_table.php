<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProtocolDocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocol_docs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('protocols_id')->default(0);
            $table->string('description', 255)->nullable();
            $table->dateTime('expire_date')->nullable();
            $table->string('file_path', 255)->nullable();
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
        Schema::dropIfExists('protocol_docs');
    }
}
