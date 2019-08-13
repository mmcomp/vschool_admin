<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProtocolPaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('protocol_pays', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('protocols_id')->default(0);
			$table->integer('allowed_amount')->default(0);
			$table->integer('amount')->default(0);
			$table->integer('register_users_id')->default(0);
			$table->enum('status', array('created','payed'))->default('created');
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
		Schema::drop('protocol_pays');
	}

}
