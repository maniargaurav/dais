<?php

use Illuminate\Database\Seeder;

class ProductToLayoutTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('product_to_layout')->delete();
        
	}

}
