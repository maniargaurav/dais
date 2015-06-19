<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVanityRouteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vanity_route', function(Blueprint $table)
		{
			$table->increments('route_id');
			$table->string('route', 55)->index('route_idx');
			$table->string('query');
			$table->string('slug')->index('slug_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vanity_route');
	}

}
