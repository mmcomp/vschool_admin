<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeDefaultToNullForStringsProtocolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('protocols', function (Blueprint $table) {
            $table->string('title', 255)->nullable()->change();
            $table->string('subject', 255)->nullable()->change();
            $table->dateTime('start')->nullable()->change();
            $table->dateTime('end')->nullable()->change();
            $table->dateTime('register')->nullable()->change();
            $table->string('number', 255)->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('protocols', function (Blueprint $table) {
            //
        });
    }
}
