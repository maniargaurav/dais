<?php

/*
|--------------------------------------------------------------------------
|   Dais
|--------------------------------------------------------------------------
|
|   This file is part of the Dais Framework package.
|	
|	(c) Vince Kronlein <vince@dais.io>
|	
|	For the full copyright and license information, please view the LICENSE
|	file that was distributed with this source code.
|	
*/

namespace App\Plugin\Example\Admin\Language\English;

class ExampleHook {
	public static function lang() {
		// Heading
		$_['lang_heading_title'] = 'Hook Overwritten Heading Title';

		return $_;
	}
}
