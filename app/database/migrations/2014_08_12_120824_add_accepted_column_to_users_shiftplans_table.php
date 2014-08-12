<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddAcceptedColumnToUsersShiftplansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users_shiftplans', function(Blueprint $table)
		{
			$table->boolean('accepted');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users_shiftplans', function(Blueprint $table)
		{
			$table->dropColumn('accepted');
		});
	}

}
