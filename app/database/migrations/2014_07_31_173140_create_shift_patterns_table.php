<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShiftPatternsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shift_patterns', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->string('name');
			$table->text('workers');
			$table->text('pattern');
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
		Schema::drop('shift_patterns');
	}

}
