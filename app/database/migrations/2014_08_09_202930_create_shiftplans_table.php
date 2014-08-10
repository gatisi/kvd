<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShiftplansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shiftplans', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('month');
			$table->string('name');
			$table->integer('pattern_id');
			$table->text('plan');
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
		Schema::drop('shiftplans');
	}

}
