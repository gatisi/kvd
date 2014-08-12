<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersShiftplansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_shiftplans', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('shiftpattern_id');
			$table->boolean('manager');
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
		Schema::drop('users_shiftplans');
	}

}
